<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
  /**
   * Run the database seeder.
   */
  public function run(): void
  {
    // Sample tasks with various native PHP functions demonstrations
    $tasks = [
      [
        'title' => 'Implement User Authentication',
        'description' => 'Create login and registration functionality with password hashing using native PHP functions.',
        'priority' => 'high',
        'status' => 'in_progress',
        'due_date' => date('Y-m-d', strtotime('+3 days')),
        'tags' => ['authentication', 'security', 'php'],
        'estimated_hours' => 8,
        'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'title' => 'File Upload System',
        'description' => 'Build a file upload system that validates file types using pathinfo() and manages storage with native file functions.',
        'priority' => 'medium',
        'status' => 'pending',
        'due_date' => date('Y-m-d', strtotime('+1 week')),
        'tags' => ['files', 'upload', 'validation'],
        'estimated_hours' => 6,
        'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'title' => 'Data Export to CSV',
        'description' => 'Implement CSV export functionality using fputcsv() and other native PHP file handling functions.',
        'priority' => 'low',
        'status' => 'completed',
        'due_date' => date('Y-m-d', strtotime('-2 days')),
        'tags' => ['export', 'csv', 'data'],
        'estimated_hours' => 4,
        'completed_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
        'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
        'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
      ],
      [
        'title' => 'String Processing Functions',
        'description' => 'Explore various string manipulation functions like explode(), implode(), trim(), substr(), and str_replace().',
        'priority' => 'medium',
        'status' => 'in_progress',
        'due_date' => date('Y-m-d', strtotime('+5 days')),
        'tags' => ['strings', 'processing', 'functions'],
        'estimated_hours' => 3,
        'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'title' => 'Array Manipulation Showcase',
        'description' => 'Demonstrate array functions including array_map(), array_filter(), in_array(), and array_key_exists().',
        'priority' => 'high',
        'status' => 'pending',
        'due_date' => date('Y-m-d', strtotime('+2 days')),
        'tags' => ['arrays', 'manipulation', 'native-php'],
        'estimated_hours' => 5,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'title' => 'Date and Time Functions',
        'description' => 'Implement various date/time operations using date(), time(), strtotime(), and DateTime classes.',
        'priority' => 'medium',
        'status' => 'pending',
        'due_date' => date('Y-m-d', strtotime('+10 days')),
        'tags' => ['datetime', 'formatting', 'calculations'],
        'estimated_hours' => 4,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'title' => 'JSON Data Processing',
        'description' => 'Work with JSON data using json_encode(), json_decode(), and handle tag arrays efficiently.',
        'priority' => 'low',
        'status' => 'completed',
        'due_date' => date('Y-m-d', strtotime('-1 week')),
        'tags' => ['json', 'data', 'processing'],
        'estimated_hours' => 2,
        'completed_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
        'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
        'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
      ],
      [
        'title' => 'Error Handling and Validation',
        'description' => 'Implement comprehensive error handling using try-catch blocks and validation with native PHP functions.',
        'priority' => 'high',
        'status' => 'pending',
        'due_date' => date('Y-m-d', strtotime('+1 day')),
        'tags' => ['error-handling', 'validation', 'security'],
        'estimated_hours' => 6,
        'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
        'updated_at' => date('Y-m-d H:i:s'),
      ]
    ];

    // Use native PHP functions to process and create tasks
    foreach ($tasks as $taskData) {
      // Using native array functions
      $tags = is_array($taskData['tags']) ? $taskData['tags'] : explode(',', $taskData['tags']);
      $taskData['tags'] = array_map('trim', $tags);

      Task::create($taskData);
    }

    // Display statistics using native PHP functions
    $totalTasks = count($tasks);
    $priorityCount = array_count_values(array_column($tasks, 'priority'));
    $statusCount = array_count_values(array_column($tasks, 'status'));

    echo "✅ Created {$totalTasks} sample tasks\n";
    echo "📊 Priority distribution: " . implode(', ', array_map(function ($priority, $count) {
      return ucfirst($priority) . ": {$count}";
    }, array_keys($priorityCount), $priorityCount)) . "\n";
    echo "📈 Status distribution: " . implode(', ', array_map(function ($status, $count) {
      return ucfirst(str_replace('_', ' ', $status)) . ": {$count}";
    }, array_keys($statusCount), $statusCount)) . "\n";
  }
}
