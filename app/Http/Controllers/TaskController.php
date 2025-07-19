<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Native\Laravel\Dialog;
use Native\Laravel\Facades\Alert;
use Native\Laravel\Facades\Notification;

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

        Alert::new()
            ->show('Task created successfully!');

        Notification::title('Hello from NativePHP')
            ->message('This is a detail message coming from your Laravel app.')
            ->show();

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

    // Additional methods showcasing native PHP functions

    public function dashboard()
    {
        $tasks = Task::all();

        // Using native PHP functions for dashboard statistics
        $stats = [
            'total' => count($tasks),
            'completed' => 0,
            'pending' => 0,
            'in_progress' => 0,
            'overdue' => 0
        ];

        // Using foreach() and native date functions
        foreach ($tasks as $task) {
            $stats[$task->status]++;

            if ($task->is_overdue) {
                $stats['overdue']++;
            }
        }

        // Using array_filter() and array_map() for priority analysis
        $highPriorityTasks = array_filter($tasks->toArray(), function ($task) {
            return $task['priority'] === 'high';
        });

        $stats['high_priority'] = count($highPriorityTasks);

        // Using array_sum() and array_column() for time analysis
        $estimatedTimes = array_filter(array_column($tasks->toArray(), 'estimated_hours'));
        $stats['total_estimated_hours'] = array_sum($estimatedTimes);
        $stats['avg_estimated_hours'] = !empty($estimatedTimes) ?
            round(array_sum($estimatedTimes) / count($estimatedTimes), 1) : 0;

        return view('tasks.dashboard', compact('tasks', 'stats'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen(trim($query)) < 2) {
            return response()->json(['tasks' => [], 'message' => 'Query too short']);
        }

        $tasks = Task::all();
        $results = [];

        // Using native PHP string functions for advanced search
        foreach ($tasks as $task) {
            $score = 0;
            $searchContent = $task->searchable_content;

            // Using strpos() for exact matches (higher score)
            if (strpos(strtolower($task->title), strtolower($query)) !== false) {
                $score += 10;
            }

            if (strpos(strtolower($task->description ?? ''), strtolower($query)) !== false) {
                $score += 5;
            }

            // Using array_search() for tag matches
            if (is_array($task->tags)) {
                foreach ($task->tags as $tag) {
                    if (stripos($tag, $query) !== false) {
                        $score += 3;
                    }
                }
            }

            // Using similar_text() for fuzzy matching
            $similarity = 0;
            similar_text(strtolower($query), strtolower($task->title), $similarity);
            if ($similarity > 60) {
                $score += 2;
            }

            if ($score > 0) {
                $results[] = [
                    'task' => $task,
                    'score' => $score,
                    'relevance' => $similarity
                ];
            }
        }

        // Using usort() with custom comparison function
        usort($results, function ($a, $b) {
            if ($a['score'] === $b['score']) {
                return $b['relevance'] <=> $a['relevance'];
            }
            return $b['score'] <=> $a['score'];
        });

        // Using array_slice() to limit results
        $limitedResults = array_slice($results, 0, 10);

        return response()->json([
            'tasks' => array_map(function ($result) {
                return $result['task'];
            }, $limitedResults),
            'total_found' => count($results)
        ]);
    }

    public function analytics()
    {
        $tasks = Task::all();

        // Using native PHP functions for comprehensive analytics
        $analytics = [];

        // Status distribution using array_count_values()
        $statuses = array_column($tasks->toArray(), 'status');
        $analytics['status_distribution'] = array_count_values($statuses);

        // Priority distribution
        $priorities = array_column($tasks->toArray(), 'priority');
        $analytics['priority_distribution'] = array_count_values($priorities);

        // Time-based analysis using date functions
        $analytics['daily_creation'] = [];
        $analytics['monthly_completion'] = [];

        foreach ($tasks as $task) {
            // Using date() for day grouping
            $createdDay = date('Y-m-d', strtotime($task->created_at));
            $analytics['daily_creation'][$createdDay] =
                ($analytics['daily_creation'][$createdDay] ?? 0) + 1;

            if ($task->completed_at) {
                $completedMonth = date('Y-m', strtotime($task->completed_at));
                $analytics['monthly_completion'][$completedMonth] =
                    ($analytics['monthly_completion'][$completedMonth] ?? 0) + 1;
            }
        }

        // Tag frequency using array_merge() and array_count_values()
        $allTags = [];
        foreach ($tasks as $task) {
            if (is_array($task->tags)) {
                $allTags = array_merge($allTags, $task->tags);
            }
        }
        $analytics['tag_frequency'] = array_count_values($allTags);

        // Using arsort() to sort by frequency
        arsort($analytics['tag_frequency']);

        // Productivity metrics using various native functions
        $completedTasks = array_filter($tasks->toArray(), function ($task) {
            return $task['status'] === 'completed';
        });

        $totalHours = array_sum(array_filter(array_column($tasks->toArray(), 'estimated_hours')));
        $completedHours = array_sum(array_filter(array_column($completedTasks, 'estimated_hours')));

        $analytics['productivity'] = [
            'completion_rate' => count($tasks) > 0 ?
                round((count($completedTasks) / count($tasks)) * 100, 1) : 0,
            'total_estimated_hours' => $totalHours,
            'completed_hours' => $completedHours,
            'efficiency_rate' => $totalHours > 0 ?
                round(($completedHours / $totalHours) * 100, 1) : 0
        ];

        return view('tasks.analytics', compact('analytics'));
    }

    public function bulkUpdate(Request $request)
    {
        $taskIds = $request->input('task_ids', []);
        $action = $request->input('action');
        $value = $request->input('value');

        if (empty($taskIds) || !$action) {
            return redirect()->back()->with('error', 'No tasks selected or action specified.');
        }

        // Using array_map() to convert string IDs to integers
        $taskIds = array_map('intval', $taskIds);
        $tasks = Task::whereIn('id', $taskIds)->get();

        $updatedCount = 0;

        // Using native switch statement and string functions
        foreach ($tasks as $task) {
            switch ($action) {
                case 'status':
                    if (in_array($value, ['pending', 'in_progress', 'completed'])) {
                        $task->status = $value;
                        if ($value === 'completed') {
                            $task->completed_at = date('Y-m-d H:i:s');
                        } elseif ($value !== 'completed') {
                            $task->completed_at = null;
                        }
                        $task->save();
                        $updatedCount++;
                    }
                    break;

                case 'priority':
                    if (in_array($value, ['low', 'medium', 'high'])) {
                        $task->priority = $value;
                        $task->save();
                        $updatedCount++;
                    }
                    break;

                case 'add_tag':
                    if (!empty(trim($value))) {
                        $task->addTags($value);
                        $task->save();
                        $updatedCount++;
                    }
                    break;

                case 'delete':
                    // Clean up attachments using file functions
                    if ($task->attachment && Storage::disk('public')->exists($task->attachment)) {
                        Storage::disk('public')->delete($task->attachment);
                    }
                    $task->delete();
                    $updatedCount++;
                    break;
            }
        }

        // Using sprintf() for formatted message
        $message = sprintf(
            'Successfully updated %d task%s.',
            $updatedCount,
            $updatedCount === 1 ? '' : 's'
        );

        return redirect()->back()->with('success', $message);
    }

    public function generateReport()
    {
        $tasks = Task::all();

        // Using native PHP functions to generate comprehensive report
        $report = [];
        $report['generated_at'] = date('Y-m-d H:i:s');
        $report['total_tasks'] = count($tasks);

        // Task breakdown using array filtering
        $report['breakdown'] = [
            'completed' => count(array_filter($tasks->toArray(), fn($t) => $t['status'] === 'completed')),
            'in_progress' => count(array_filter($tasks->toArray(), fn($t) => $t['status'] === 'in_progress')),
            'pending' => count(array_filter($tasks->toArray(), fn($t) => $t['status'] === 'pending')),
            'overdue' => count(array_filter($tasks->toArray(), function ($t) {
                return $t['due_date'] && strtotime($t['due_date']) < strtotime('today') && $t['status'] !== 'completed';
            }))
        ];

        // Using array functions for detailed analysis
        $estimatedHours = array_filter(array_column($tasks->toArray(), 'estimated_hours'));
        $report['time_analysis'] = [
            'total_estimated' => array_sum($estimatedHours),
            'average_per_task' => !empty($estimatedHours) ? round(array_sum($estimatedHours) / count($estimatedHours), 1) : 0,
            'max_hours' => !empty($estimatedHours) ? max($estimatedHours) : 0,
            'min_hours' => !empty($estimatedHours) ? min($estimatedHours) : 0
        ];

        // Generate filename using date functions
        $filename = 'task_report_' . date('Y_m_d_His') . '.json';
        $filePath = storage_path('app/reports/' . $filename);

        // Ensure directory exists using native functions
        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }

        // Using file_put_contents() and json_encode()
        file_put_contents($filePath, json_encode($report, JSON_PRETTY_PRINT));

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
