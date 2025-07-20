@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Tasks</h2>
            <p class="text-gray-600">Manage your tasks efficiently</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('tasks.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Search tasks..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                        </option>
                    </select>
                </div>

                <!-- Priority Filter -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select name="priority" id="priority"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <!-- Submit -->
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Tasks Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tasks as $task)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                    <!-- Task Header -->
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $task->title }}</h3>
                            <!-- Priority Badge -->
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full
                            @if ($task->priority == 'high') bg-red-100 text-red-800
                            @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>

                        <!-- Status Badge -->
                        <span
                            class="px-2 py-1 text-xs font-medium rounded-full
                        @if ($task->status == 'completed') bg-green-100 text-green-800
                        @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>

                    <!-- Task Body -->
                    <div class="p-4">
                        @if ($task->description)
                            <p class="text-gray-600 text-sm mb-3 line-clamp-3">{{ $task->description }}</p>
                        @endif

                        <!-- Tags -->
                        @if ($task->tags && count($task->tags) > 0)
                            <div class="mb-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($task->tags as $tag)
                                        <span
                                            class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Due Date -->
                        @if ($task->due_date)
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Due: {{ $task->formatted_due_date }}
                                @if ($task->time_remaining)
                                    <span
                                        class="ml-2 text-xs {{ strpos($task->time_remaining, '-') === 0 ? 'text-red-600' : 'text-green-600' }}">
                                        ({{ $task->time_remaining }})
                                    </span>
                                @endif
                            </div>
                        @endif

                        <!-- Estimated Hours -->
                        @if ($task->estimated_hours)
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $task->estimated_hours }} hour{{ $task->estimated_hours > 1 ? 's' : '' }}
                            </div>
                        @endif

                        <!-- Attachment -->
                        @if ($task->attachment)
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                    </path>
                                </svg>
                                <a href="{{ Storage::url($task->attachment) }}" target="_blank"
                                    class="text-blue-600 hover:underline">
                                    View Attachment
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Task Footer -->
                    <div class="px-4 py-3 bg-gray-50 flex justify-between items-center">
                        <span class="text-xs text-gray-500">
                            Created {{ $task->created_at->diffForHumans() }}
                        </span>
                        <div class="flex space-x-2">
                            <a href="{{ route('tasks.edit', $task) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Edit
                            </a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline"
                                onsubmit="return confirm('Are you sure you want to delete this task?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new task.</p>
                        <div class="mt-6">
                            <a href="{{ route('tasks.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                New Task
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($tasks->hasPages())
            <div class="mt-8">
                {{ $tasks->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection
