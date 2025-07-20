<?php

namespace App\Helpers;

class NativePHPHelper
{
    /**
     * Demonstrate String Functions
     */
    public static function processTaskTitle($title)
    {
        // Using multiple native string functions
        $processed = [];

        // Basic string info
        $processed['original'] = $title;
        $processed['length'] = strlen($title);
        $processed['word_count'] = str_word_count($title);

        // Case transformations
        $processed['uppercase'] = strtoupper($title);
        $processed['lowercase'] = strtolower($title);
        $processed['title_case'] = ucwords(strtolower($title));
        $processed['first_letter_caps'] = ucfirst(strtolower($title));

        // String manipulations
        $processed['reversed'] = strrev($title);
        $processed['no_spaces'] = str_replace(' ', '', $title);
        $processed['with_underscores'] = str_replace(' ', '_', strtolower($title));

        // Substring operations
        $processed['first_10_chars'] = substr($title, 0, 10);
        $processed['last_5_chars'] = substr($title, -5);
        $processed['middle_portion'] = substr($title, 2, -2);

        // Position finding
        $processed['first_space_pos'] = strpos($title, ' ');
        $processed['last_space_pos'] = strrpos($title, ' ');

        // Trimming and cleaning
        $processed['trimmed'] = trim($title);
        $processed['left_trimmed'] = ltrim($title);
        $processed['right_trimmed'] = rtrim($title);

        return $processed;
    }

    /**
     * Demonstrate Array Functions
     */
    public static function analyzeTaskArray($tasks)
    {
        if (empty($tasks)) return [];

        $analysis = [];

        // Basic array info
        $analysis['count'] = count($tasks);
        $analysis['is_array'] = is_array($tasks);
        $analysis['has_numeric_keys'] = array_is_list($tasks);

        // Extract specific columns
        $titles = array_column($tasks, 'title');
        $priorities = array_column($tasks, 'priority');
        $statuses = array_column($tasks, 'status');

        $analysis['titles'] = $titles;
        $analysis['unique_priorities'] = array_unique($priorities);
        $analysis['status_counts'] = array_count_values($statuses);

        // Array operations
        $tasksCopy = $tasks; // Create a copy for reference functions
        $analysis['first_task'] = reset($tasksCopy);
        $tasksCopy = $tasks; // Reset copy for end()
        $analysis['last_task'] = end($tasksCopy);
        $analysis['keys'] = array_keys($tasks);
        $analysis['values_only'] = array_values($tasks);

        // Filtering operations
        $highPriorityTasks = array_filter($tasks, function ($task) {
            return isset($task['priority']) && $task['priority'] === 'high';
        });
        $analysis['high_priority_count'] = count($highPriorityTasks);

        // Mapping operations
        $taskSlugs = array_map(function ($task) {
            return isset($task['title']) ? self::createSlug($task['title']) : '';
        }, $tasks);
        $analysis['task_slugs'] = $taskSlugs;

        // Array merging and combining
        $taskIds = range(1, count($tasks));
        $combined = array_combine($taskIds, $titles);
        $analysis['id_title_map'] = $combined;

        // Searching and checking
        $analysis['has_completed'] = in_array('completed', $statuses);
        $analysis['first_completed_key'] = array_search('completed', $statuses);

        // Chunking and slicing
        $analysis['first_3_tasks'] = array_slice($tasks, 0, 3);
        $analysis['task_chunks'] = array_chunk($tasks, 2);

        // Sorting operations (on a copy)
        $sortedTitles = $titles;
        sort($sortedTitles);
        $analysis['sorted_titles'] = $sortedTitles;

        rsort($sortedTitles);
        $analysis['reverse_sorted_titles'] = $sortedTitles;

        return $analysis;
    }

    /**
     * Demonstrate Date/Time Functions
     */
    public static function analyzeDatesAndTimes($dateString = null)
    {
        $analysis = [];
        $targetDate = $dateString ?: date('Y-m-d H:i:s');

        // Current time functions
        $analysis['current_timestamp'] = time();
        $analysis['current_date'] = date('Y-m-d');
        $analysis['current_time'] = date('H:i:s');
        $analysis['current_datetime'] = date('Y-m-d H:i:s');

        // Date formatting variations
        $analysis['formatted_dates'] = [
            'iso_date' => date('c', strtotime($targetDate)),
            'us_format' => date('m/d/Y', strtotime($targetDate)),
            'european_format' => date('d/m/Y', strtotime($targetDate)),
            'long_format' => date('l, F j, Y', strtotime($targetDate)),
            'short_format' => date('M j, Y', strtotime($targetDate)),
            'with_time' => date('Y-m-d H:i:s', strtotime($targetDate)),
            'timestamp' => strtotime($targetDate)
        ];

        // Time calculations
        $now = time();
        $targetTimestamp = strtotime($targetDate);

        $analysis['time_calculations'] = [
            'seconds_since_epoch' => $targetTimestamp,
            'days_since_epoch' => floor($targetTimestamp / (60 * 60 * 24)),
            'seconds_from_now' => $targetTimestamp - $now,
            'days_from_now' => floor(($targetTimestamp - $now) / (60 * 60 * 24)),
            'is_future' => $targetTimestamp > $now,
            'is_past' => $targetTimestamp < $now,
            'is_today' => date('Y-m-d', $targetTimestamp) === date('Y-m-d', $now)
        ];

        // Week and month calculations
        $analysis['period_info'] = [
            'day_of_week' => date('w', $targetTimestamp),
            'day_name' => date('l', $targetTimestamp),
            'week_of_year' => date('W', $targetTimestamp),
            'month_number' => date('n', $targetTimestamp),
            'month_name' => date('F', $targetTimestamp),
            'quarter' => ceil(date('n', $targetTimestamp) / 3),
            'days_in_month' => date('t', $targetTimestamp),
            'is_leap_year' => date('L', $targetTimestamp) == 1
        ];

        return $analysis;
    }

    /**
     * Demonstrate File and Directory Functions
     */
    public static function analyzeFileOperations($filename = 'sample.txt')
    {
        $analysis = [];

        // File path operations
        $analysis['path_info'] = pathinfo($filename);
        $analysis['dirname'] = dirname($filename);
        $analysis['basename'] = basename($filename);
        $analysis['filename_only'] = pathinfo($filename, PATHINFO_FILENAME);
        $analysis['extension'] = pathinfo($filename, PATHINFO_EXTENSION);

        // File existence and properties (simulated)
        $samplePath = storage_path('app/' . $filename);
        $analysis['file_operations'] = [
            'full_path' => $samplePath,
            'file_exists' => file_exists($samplePath),
            'is_file' => is_file($samplePath),
            'is_dir' => is_dir(dirname($samplePath)),
            'is_readable' => is_readable(dirname($samplePath)),
            'is_writable' => is_writable(dirname($samplePath))
        ];

        // Create a sample file for demonstration
        $sampleContent = "This is a sample file created at " . date('Y-m-d H:i:s') . "\n";
        $sampleContent .= "Demonstrating native PHP file functions.\n";
        $sampleContent .= "File size will be calculated after creation.";

        // File operations
        if (is_writable(dirname($samplePath))) {
            file_put_contents($samplePath, $sampleContent);

            if (file_exists($samplePath)) {
                $analysis['file_stats'] = [
                    'size_bytes' => filesize($samplePath),
                    'last_modified' => filemtime($samplePath),
                    'last_accessed' => fileatime($samplePath),
                    'permissions' => substr(sprintf('%o', fileperms($samplePath)), -4),
                    'lines_count' => count(file($samplePath)),
                    'content_preview' => substr(file_get_contents($samplePath), 0, 100)
                ];

                // Clean up
                unlink($samplePath);
            }
        }

        return $analysis;
    }

    /**
     * Demonstrate Math Functions
     */
    public static function performMathOperations($numbers = [1, 2, 3, 4, 5, 10, 15, 20])
    {
        $analysis = [];

        // Basic math functions
        $analysis['basic_operations'] = [
            'sum' => array_sum($numbers),
            'product' => array_product($numbers),
            'count' => count($numbers),
            'average' => array_sum($numbers) / count($numbers),
            'minimum' => min($numbers),
            'maximum' => max($numbers)
        ];

        // Advanced math functions
        $analysis['advanced_operations'] = [
            'square_root_of_sum' => sqrt(array_sum($numbers)),
            'power_of_first' => pow($numbers[0], 2),
            'absolute_values' => array_map('abs', $numbers),
            'rounded_average' => round(array_sum($numbers) / count($numbers), 2),
            'ceiling_average' => ceil(array_sum($numbers) / count($numbers)),
            'floor_average' => floor(array_sum($numbers) / count($numbers))
        ];

        // Random operations
        $shuffledCopy = array_slice($numbers, 0);
        shuffle($shuffledCopy);
        $analysis['random_operations'] = [
            'random_number_0_to_100' => rand(0, 100),
            'random_float' => mt_rand() / mt_getrandmax(),
            'shuffled_array' => $shuffledCopy,
            'random_array_element' => $numbers[array_rand($numbers)]
        ];

        return $analysis;
    }

    /**
     * Helper function using string manipulation
     */
    private static function createSlug($string)
    {
        $slug = strtolower(trim($string));
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        return trim($slug, '-');
    }

    /**
     * Demonstrate Variable and Type Functions
     */
    public static function analyzeVariableTypes($value)
    {
        $analysis = [];

        // Type checking functions
        $analysis['type_checks'] = [
            'gettype' => gettype($value),
            'is_array' => is_array($value),
            'is_bool' => is_bool($value),
            'is_float' => is_float($value),
            'is_int' => is_int($value),
            'is_null' => is_null($value),
            'is_numeric' => is_numeric($value),
            'is_object' => is_object($value),
            'is_string' => is_string($value),
            'is_scalar' => is_scalar($value),
            'is_callable' => is_callable($value),
            'is_countable' => is_countable($value),
            'is_iterable' => is_iterable($value)
        ];

        // Variable status
        $analysis['variable_status'] = [
            'isset' => isset($value),
            'empty' => empty($value),
            'var_export' => var_export($value, true),
            'serialize' => serialize($value),
            'json_encode' => json_encode($value)
        ];

        return $analysis;
    }

    /**
     * Demonstrate Sorting Functions
     */
    public static function demonstrateSorting($array)
    {
        $sorting = [];

        // Make copies for different sorting methods
        $copy1 = $array;
        $copy2 = $array;
        $copy3 = $array;
        $copy4 = $array;
        $copy5 = $array;
        $copy6 = $array;

        // Different sorting methods
        sort($copy1);
        $sorting['sort_ascending'] = $copy1;

        rsort($copy2);
        $sorting['sort_descending'] = $copy2;

        asort($copy3);
        $sorting['asort_preserve_keys'] = $copy3;

        arsort($copy4);
        $sorting['arsort_preserve_keys'] = $copy4;

        ksort($copy5);
        $sorting['ksort_by_keys'] = $copy5;

        krsort($copy6);
        $sorting['krsort_by_keys_desc'] = $copy6;

        // Custom sorting
        $copy7 = $array;
        usort($copy7, function ($a, $b) {
            return strlen($a) <=> strlen($b);
        });
        $sorting['usort_by_length'] = $copy7;

        // Shuffle
        $copy8 = $array;
        shuffle($copy8);
        $sorting['shuffled'] = $copy8;

        return $sorting;
    }
}
