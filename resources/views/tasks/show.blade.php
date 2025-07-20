@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
                        <div class="flex items-center space-x-4 mt-2">
                            <!-- Priority Badge -->
                            <span
                                class="px-3 py-1 text-sm font-medium rounded-full
                            @if ($task->priority == 'high') bg-red-100 text-red-800
                            @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($task->priority) }} Priority
                            </span>

                            <!-- Status Badge -->
                            <span
                                class="px-3 py-1 text-sm font-medium rounded-full
                            @if ($task->status == 'completed') bg-green-100 text-green-800
                            @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex space-x-2">
                        <a href="{{ route('tasks.edit', $task) }}"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                            Edit Task
                        </a>
                        <a href="{{ route('tasks.index') }}"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md">
                            Back to Tasks
                        </a>
                    </div>
                </div>
            </div>

            <!-- Task Details -->
            <div class="p-6">
                <!-- Description -->
                @if ($task->description)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Description</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $task->description }}</p>
                    </div>
                @endif

                <!-- Task Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Due Date -->
                    @if ($task->due_date)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Due Date</p>
                                    <p class="text-sm text-gray-600">{{ $task->formatted_due_date }}</p>
                                    @if ($task->time_remaining)
                                        <p
                                            class="text-xs {{ strpos($task->time_remaining, '-') === 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $task->time_remaining }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Estimated Hours -->
                    @if ($task->estimated_hours)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Estimated Time</p>
                                    <p class="text-sm text-gray-600">{{ $task->estimated_hours }}
                                        hour{{ $task->estimated_hours > 1 ? 's' : '' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Created Date -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4.01M8 12h4m5.63-4.95l-2.65-2.69 2.63 2.69z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Created</p>
                                <p class="text-sm text-gray-600">{{ $task->created_at->format('M j, Y \a\t g:i A') }}</p>
                                <p class="text-xs text-gray-500">{{ $task->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Last Updated -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                <p class="text-sm text-gray-600">{{ $task->updated_at->format('M j, Y \a\t g:i A') }}</p>
                                <p class="text-xs text-gray-500">{{ $task->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tags -->
                @if ($task->tags && count($task->tags) > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($task->tags as $tag)
                                <span
                                    class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Attachment -->
                @if ($task->attachment)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Attachment</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                    </path>
                                </svg>
                                <div>
                                    <a href="{{ Storage::url($task->attachment) }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800 font-medium">
                                        View Attachment
                                    </a>
                                    <p class="text-xs text-gray-500 mt-1">Click to open in new tab</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Completion Info -->
                @if ($task->completed_at)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-6 h-6 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h3 class="text-sm font-medium text-green-800">Task Completed</h3>
                                <p class="text-sm text-green-700 mt-1">
                                    This task was completed on {{ $task->completed_at->format('M j, Y \a\t g:i A') }}
                                    ({{ $task->completed_at->diffForHumans() }})
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Actions Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Task ID: #{{ $task->id }}
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('tasks.edit', $task) }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Edit Task
                        </a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline"
                            onsubmit="return confirm('Are you sure you want to delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                Delete Task
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
