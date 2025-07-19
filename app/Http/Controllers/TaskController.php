<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::query();

        // Using native PHP filter functions
        if ($search = $request->get('search')) {
            // Using native strpos() and strtolower()
            $tasks->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        if ($status = $request->get('status')) {
            $tasks->where('status', $status);
        }

        if ($priority = $request->get('priority')) {
            $tasks->where('priority', $priority);
        }

        // Using native array sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Validate sort parameters using native in_array()
        $allowedSorts = ['title', 'priority', 'due_date', 'created_at', 'status'];
        $allowedDirections = ['asc', 'desc'];

        if (in_array($sortBy, $allowedSorts) && in_array($sortDirection, $allowedDirections)) {
            $tasks->orderBy($sortBy, $sortDirection);
        }

        $tasks = $tasks->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'tags' => 'nullable|string',
            'estimated_hours' => 'nullable|integer|min:1',
            'attachment' => 'nullable|file|max:2048'
        ]);

        $data = $request->only(['title', 'description', 'priority', 'due_date', 'estimated_hours']);

        // Using native PHP string functions
        if ($request->tags) {
            // Using explode() and array_map() with trim()
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        // File handling with native PHP functions
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            // Using pathinfo() and uniqid()
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $data['attachment'] = $file->storeAs('attachments', $filename, 'public');
        }

        Task::create($data);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed',
            'tags' => 'nullable|string',
            'estimated_hours' => 'nullable|integer|min:1'
        ]);

        $data = $request->only(['title', 'description', 'priority', 'due_date', 'status', 'estimated_hours']);

        // Mark completion time using native time()
        if ($request->status === 'completed' && $task->status !== 'completed') {
            $data['completed_at'] = date('Y-m-d H:i:s');
        } elseif ($request->status !== 'completed') {
            $data['completed_at'] = null;
        }

        // Process tags using native string functions
        if ($request->tags) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        $task->update($data);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        // Clean up attachment using native file functions
        if ($task->attachment && Storage::disk('public')->exists($task->attachment)) {
            Storage::disk('public')->delete($task->attachment);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    public function export()
    {
        $tasks = Task::all();

        // Create CSV using native PHP functions
        $filename = 'tasks_export_' . date('Y_m_d_H_i_s') . '.csv';
        $handle = fopen('php://output', 'w');

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Write CSV header using fputcsv()
        fputcsv($handle, ['ID', 'Title', 'Description', 'Priority', 'Status', 'Due Date', 'Tags', 'Created At']);

        // Write data using native loops and functions
        foreach ($tasks as $task) {
            fputcsv($handle, [
                $task->id,
                $task->title,
                $task->description,
                ucfirst($task->priority),
                ucfirst(str_replace('_', ' ', $task->status)),
                $task->due_date ? date('Y-m-d', strtotime($task->due_date)) : '',
                $task->tags_string,
                date('Y-m-d H:i:s', strtotime($task->created_at))
            ]);
        }

        fclose($handle);
        exit;
    }
}
