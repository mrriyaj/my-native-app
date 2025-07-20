@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900">Native PHP Functions Showcase</h1>
            <p class="text-gray-600 mt-2">Explore the power of native PHP functions in task management</p>
        </div>

        <!-- Quick Stats Using Native PHP -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                <h3 class="text-lg font-semibold text-blue-800">Total Tasks</h3>
                <p class="text-3xl font-bold text-blue-600">{{ count($tasks) }}</p>
                <p class="text-sm text-blue-600">Using count()</p>
            </div>

            <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                <h3 class="text-lg font-semibold text-green-800">Completed</h3>
                <p class="text-3xl font-bold text-green-600">
                    {{ count(array_filter($tasks->toArray(), fn($t) => $t['status'] === 'completed')) }}
                </p>
                <p class="text-sm text-green-600">Using array_filter()</p>
            </div>

            <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                <h3 class="text-lg font-semibold text-yellow-800">Average Hours</h3>
                @php
                    $hours = array_filter(array_column($tasks->toArray(), 'estimated_hours'));
                    $avg = !empty($hours) ? round(array_sum($hours) / count($hours), 1) : 0;
                @endphp
                <p class="text-3xl font-bold text-yellow-600">{{ $avg }}</p>
                <p class="text-sm text-yellow-600">Using array_sum() & round()</p>
            </div>

            <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                <h3 class="text-lg font-semibold text-red-800">Overdue Tasks</h3>
                <p class="text-3xl font-bold text-red-600">
                    {{ count(
                        array_filter($tasks->toArray(), function ($t) {
                            return $t['due_date'] && strtotime($t['due_date']) < strtotime('today') && $t['status'] !== 'completed';
                        }),
                    ) }}
                </p>
                <p class="text-sm text-red-600">Using strtotime()</p>
            </div>
        </div>

        <!-- String Functions Demo -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">String Functions Demonstration</h2>
            @if ($tasks->isNotEmpty())
                @php
                    $firstTask = $tasks->first();
                    $stringDemo = App\Helpers\NativePHPHelper::processTaskTitle($firstTask->title);
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <h4 class="font-medium text-gray-700">Original Title:</h4>
                        <p class="text-sm bg-gray-100 p-2 rounded">{{ $stringDemo['original'] }}</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700">Length & Words:</h4>
                        <p class="text-sm bg-gray-100 p-2 rounded">
                            {{ $stringDemo['length'] }} chars, {{ $stringDemo['word_count'] }} words<br>
                            <small>strlen() & str_word_count()</small>
                        </p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700">Case Variations:</h4>
                        <div class="text-xs bg-gray-100 p-2 rounded space-y-1">
                            <div>UPPER: {{ $stringDemo['uppercase'] }}</div>
                            <div>lower: {{ $stringDemo['lowercase'] }}</div>
                            <div>Title: {{ $stringDemo['title_case'] }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Array Functions Demo -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Array Functions Demonstration</h2>
            @php
                $priorities = array_column($tasks->toArray(), 'priority');
                $priorityCount = array_count_values($priorities);
                $statuses = array_column($tasks->toArray(), 'status');
                $statusCount = array_count_values($statuses);
                $allTags = [];
                foreach ($tasks as $task) {
                    if (is_array($task->tags)) {
                        $allTags = array_merge($allTags, $task->tags);
                    }
                }
                $tagCount = array_count_values($allTags);
                arsort($tagCount);
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Priority Distribution</h4>
                    <div class="space-y-1">
                        @foreach ($priorityCount as $priority => $count)
                            <div class="flex justify-between text-sm bg-gray-50 p-2 rounded">
                                <span>{{ ucfirst($priority) }}</span>
                                <span class="font-medium">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-1">array_column() & array_count_values()</p>
                </div>

                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Status Distribution</h4>
                    <div class="space-y-1">
                        @foreach ($statusCount as $status => $count)
                            <div class="flex justify-between text-sm bg-gray-50 p-2 rounded">
                                <span>{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                <span class="font-medium">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-1">str_replace() & ucfirst()</p>
                </div>

                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Top Tags</h4>
                    <div class="space-y-1">
                        @foreach (array_slice($tagCount, 0, 5, true) as $tag => $count)
                            <div class="flex justify-between text-sm bg-gray-50 p-2 rounded">
                                <span>{{ $tag }}</span>
                                <span class="font-medium">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-1">array_merge(), arsort() & array_slice()</p>
                </div>
            </div>
        </div>

        <!-- Date/Time Functions Demo -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Date/Time Functions Demonstration</h2>
            @php
                $dateDemo = App\Helpers\NativePHPHelper::analyzeDatesAndTimes();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Current Time Information</h4>
                    <div class="space-y-2 text-sm">
                        <div class="bg-gray-50 p-2 rounded">
                            <strong>Timestamp:</strong> {{ $dateDemo['current_timestamp'] }}
                            <br><small>time()</small>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <strong>Date:</strong> {{ $dateDemo['current_date'] }}
                            <br><small>date('Y-m-d')</small>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <strong>Time:</strong> {{ $dateDemo['current_time'] }}
                            <br><small>date('H:i:s')</small>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Format Variations</h4>
                    <div class="space-y-2 text-sm">
                        @foreach ($dateDemo['formatted_dates'] as $format => $value)
                            @if (in_array($format, ['us_format', 'european_format', 'long_format']))
                                <div class="bg-gray-50 p-2 rounded">
                                    <strong>{{ ucfirst(str_replace('_', ' ', $format)) }}:</strong> {{ $value }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Math Functions Demo -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Math Functions Demonstration</h2>
            @php
                $hours = array_filter(array_column($tasks->toArray(), 'estimated_hours'));
                $mathDemo = App\Helpers\NativePHPHelper::performMathOperations($hours ?: [1, 2, 3, 4, 5]);
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Basic Operations</h4>
                    <div class="space-y-1 text-sm">
                        @foreach ($mathDemo['basic_operations'] as $operation => $value)
                            <div class="bg-gray-50 p-2 rounded flex justify-between">
                                <span>{{ ucfirst(str_replace('_', ' ', $operation)) }}:</span>
                                <span class="font-medium">{{ is_float($value) ? round($value, 2) : $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Advanced Operations</h4>
                    <div class="space-y-1 text-sm">
                        @foreach ($mathDemo['advanced_operations'] as $operation => $value)
                            @if (!is_array($value))
                                <div class="bg-gray-50 p-2 rounded flex justify-between">
                                    <span>{{ ucfirst(str_replace('_', ' ', $operation)) }}:</span>
                                    <span class="font-medium">{{ is_float($value) ? round($value, 2) : $value }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Random Operations</h4>
                    <div class="space-y-1 text-sm">
                        @foreach ($mathDemo['random_operations'] as $operation => $value)
                            @if (!is_array($value))
                                <div class="bg-gray-50 p-2 rounded flex justify-between">
                                    <span>{{ ucfirst(str_replace('_', ' ', $operation)) }}:</span>
                                    <span class="font-medium">{{ is_float($value) ? round($value, 2) : $value }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- File Functions Demo -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">File Functions Demonstration</h2>
            @php
                $fileDemo = App\Helpers\NativePHPHelper::analyzeFileOperations('demo_file.txt');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Path Information</h4>
                    <div class="space-y-1 text-sm">
                        @foreach ($fileDemo['path_info'] as $info => $value)
                            <div class="bg-gray-50 p-2 rounded">
                                <strong>{{ ucfirst($info) }}:</strong> {{ $value }}
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-1">pathinfo(), dirname(), basename()</p>
                </div>

                <div>
                    <h4 class="font-medium text-gray-700 mb-2">File Operations</h4>
                    <div class="space-y-1 text-sm">
                        @foreach ($fileDemo['file_operations'] as $operation => $value)
                            <div class="bg-gray-50 p-2 rounded flex justify-between">
                                <span>{{ ucfirst(str_replace('_', ' ', $operation)) }}:</span>
                                <span class="font-medium">
                                    @if (is_bool($value))
                                        {{ $value ? 'Yes' : 'No' }}
                                    @else
                                        {{ is_string($value) ? (strlen($value) > 30 ? substr($value, 0, 30) . '...' : $value) : $value }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-1">file_exists(), is_file(), is_readable()</p>
                </div>
            </div>
        </div>

        <!-- Function Categories Summary -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Native PHP Functions Used in This Application</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-sm">
                <div>
                    <h4 class="font-medium text-blue-800 mb-2">String Functions</h4>
                    <ul class="space-y-1 text-blue-700">
                        <li>• strlen(), substr()</li>
                        <li>• explode(), implode()</li>
                        <li>• trim(), strtolower()</li>
                        <li>• str_replace(), ucfirst()</li>
                        <li>• strpos(), strrpos()</li>
                        <li>• str_word_count()</li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-medium text-green-800 mb-2">Array Functions</h4>
                    <ul class="space-y-1 text-green-700">
                        <li>• array_map(), array_filter()</li>
                        <li>• array_column(), count()</li>
                        <li>• array_merge(), array_unique()</li>
                        <li>• in_array(), array_search()</li>
                        <li>• sort(), rsort(), usort()</li>
                        <li>• array_count_values()</li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-medium text-yellow-800 mb-2">Date/Time Functions</h4>
                    <ul class="space-y-1 text-yellow-700">
                        <li>• date(), time()</li>
                        <li>• strtotime(), mktime()</li>
                        <li>• DateTime, date_diff()</li>
                        <li>• filemtime(), fileatime()</li>
                        <li>• Format variations</li>
                        <li>• Time calculations</li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-medium text-red-800 mb-2">File & Math Functions</h4>
                    <ul class="space-y-1 text-red-700">
                        <li>• fopen(), fputcsv()</li>
                        <li>• file_exists(), pathinfo()</li>
                        <li>• max(), min(), round()</li>
                        <li>• array_sum(), floor()</li>
                        <li>• rand(), mt_rand()</li>
                        <li>• json_encode(), serialize()</li>
                    </ul>
                </div>
            </div>

            <div class="mt-6 p-4 bg-white rounded border border-blue-200">
                <p class="text-gray-700">
                    <strong>Total Native PHP Functions Demonstrated:</strong> 50+<br>
                    <small>This task management application showcases the power and versatility of native PHP functions
                        across string manipulation, array operations, date/time handling, file operations, mathematical
                        calculations,
                        and data processing - all without relying on external libraries!</small>
                </p>
            </div>
        </div>
    </div>
@endsection
