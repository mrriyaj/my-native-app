<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'attachment',
        'due_date',
        'status',
        'tags',
        'estimated_hours',
        'completed_at'
    ];

    protected $casts = [
        'tags' => 'array',
        'due_date' => 'date',
        'completed_at' => 'datetime'
    ];

    // Native PHP functions exploration
    public function getFormattedDueDateAttribute()
    {
        // Using native date() function
        return $this->due_date ? date('M j, Y', strtotime($this->due_date)) : null;
    }

    public function getTimeRemainingAttribute()
    {
        if (!$this->due_date) return null;

        // Using native time() and date_diff()
        $now = new \DateTime();
        $due = new \DateTime($this->due_date);
        $diff = date_diff($now, $due);

        return $diff->format('%R%a days');
    }

    public function getPriorityColorAttribute()
    {
        // Using native array functions
        $colors = ['low' => 'green', 'medium' => 'orange', 'high' => 'red'];
        return array_key_exists($this->priority, $colors) ? $colors[$this->priority] : 'gray';
    }

    public function getTagsStringAttribute()
    {
        // Using native implode() function
        return is_array($this->tags) ? implode(', ', $this->tags) : '';
    }

    // Additional Native PHP Functions for Task Management

    public function getTitleLengthAttribute()
    {
        // Using native strlen() function
        return strlen($this->title);
    }

    public function getShortDescriptionAttribute()
    {
        if (!$this->description) return null;

        // Using native substr() and strpos() functions
        $maxLength = 100;
        if (strlen($this->description) <= $maxLength) {
            return $this->description;
        }

        // Find last space before maxLength using strrpos()
        $lastSpace = strrpos(substr($this->description, 0, $maxLength), ' ');
        $cutPoint = $lastSpace ? $lastSpace : $maxLength;

        return substr($this->description, 0, $cutPoint) . '...';
    }

    public function getWordCountAttribute()
    {
        if (!$this->description) return 0;

        // Using native str_word_count() function
        return str_word_count(strip_tags($this->description));
    }

    public function getProgressPercentageAttribute()
    {
        // Using native array functions and calculations
        $statusWeights = [
            'pending' => 0,
            'in_progress' => 50,
            'completed' => 100
        ];

        return array_key_exists($this->status, $statusWeights) ? $statusWeights[$this->status] : 0;
    }

    public function getDaysUntilDueAttribute()
    {
        if (!$this->due_date) return null;

        // Using native mktime() and floor() functions
        $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $dueTimestamp = strtotime($this->due_date);
        $daysDiff = floor(($dueTimestamp - $today) / (60 * 60 * 24));

        return $daysDiff;
    }

    public function getIsOverdueAttribute()
    {
        if (!$this->due_date || $this->status === 'completed') return false;

        // Using native date comparison
        return strtotime($this->due_date) < strtotime('today');
    }

    public function getFormattedCreatedAtAttribute()
    {
        // Using native date() and gmdate() functions
        return date('F j, Y \a\t g:i A', strtotime($this->created_at));
    }

    public function getHashtagsAttribute()
    {
        if (!is_array($this->tags)) return [];

        // Using native array_map() and array_filter() functions
        return array_filter(array_map(function ($tag) {
            // Using trim() and substr() functions
            $cleanTag = trim($tag);
            return $cleanTag ? '#' . str_replace(' ', '', ucwords($cleanTag)) : null;
        }, $this->tags));
    }

    public function getSearchableContentAttribute()
    {
        // Using native strtolower() and array_merge() functions
        $content = [
            strtolower($this->title),
            strtolower($this->description ?? ''),
            strtolower($this->priority),
            strtolower($this->status)
        ];

        if (is_array($this->tags)) {
            $content = array_merge($content, array_map('strtolower', $this->tags));
        }

        // Using array_filter() to remove empty values and implode()
        return implode(' ', array_filter($content));
    }

    public function getPriorityNumericAttribute()
    {
        // Using native array_search() function
        $priorities = ['low', 'medium', 'high'];
        $index = array_search($this->priority, $priorities);
        return $index !== false ? $index + 1 : 0;
    }

    public function getTaskStatisticsAttribute()
    {
        // Using multiple native PHP functions for statistics
        $stats = [];

        // Character count using strlen()
        $stats['title_chars'] = strlen($this->title);
        $stats['desc_chars'] = strlen($this->description ?? '');

        // Word counts using str_word_count()
        $stats['title_words'] = str_word_count($this->title);
        $stats['desc_words'] = str_word_count($this->description ?? '');

        // Tag count using count()
        $stats['tag_count'] = is_array($this->tags) ? count($this->tags) : 0;

        // Age in days using round() and time()
        $stats['age_days'] = round((time() - strtotime($this->created_at)) / (60 * 60 * 24));

        return $stats;
    }

    // Static method using native PHP functions for bulk operations
    public static function getTaskSummary($tasks)
    {
        if (empty($tasks)) return [];

        // Using native array functions for analysis
        $statuses = array_column($tasks->toArray(), 'status');
        $priorities = array_column($tasks->toArray(), 'priority');

        // Using array_count_values() for counting
        $statusCount = array_count_values($statuses);
        $priorityCount = array_count_values($priorities);

        // Using array_sum() and count() for averages
        $estimatedHours = array_filter(array_column($tasks->toArray(), 'estimated_hours'));
        $avgHours = !empty($estimatedHours) ? round(array_sum($estimatedHours) / count($estimatedHours), 1) : 0;

        // Using max() and min() functions
        $maxHours = !empty($estimatedHours) ? max($estimatedHours) : 0;
        $minHours = !empty($estimatedHours) ? min($estimatedHours) : 0;

        return [
            'total_tasks' => count($tasks),
            'status_breakdown' => $statusCount,
            'priority_breakdown' => $priorityCount,
            'avg_estimated_hours' => $avgHours,
            'max_estimated_hours' => $maxHours,
            'min_estimated_hours' => $minHours,
            'completion_rate' => isset($statusCount['completed']) ?
                round(($statusCount['completed'] / count($tasks)) * 100, 1) : 0
        ];
    }

    // Method using native string functions for title processing
    public function getSlugAttribute()
    {
        // Using multiple native string functions
        $slug = strtolower($this->title);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug); // Remove special chars
        $slug = trim($slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug); // Replace spaces with hyphens
        $slug = trim($slug, '-');

        return $slug;
    }

    // Method using native array and string functions for tag management
    public function addTags($newTags)
    {
        if (is_string($newTags)) {
            // Using explode() and array_map() with trim()
            $newTags = array_map('trim', explode(',', $newTags));
        }

        $currentTags = is_array($this->tags) ? $this->tags : [];

        // Using array_merge() and array_unique() and array_filter()
        $allTags = array_unique(array_merge($currentTags, $newTags));
        $this->tags = array_filter($allTags, function ($tag) {
            return !empty(trim($tag));
        });

        return $this;
    }

    // Method using native date functions for scheduling
    public function isScheduledForToday()
    {
        if (!$this->due_date) return false;

        // Using date() function for comparison
        return date('Y-m-d', strtotime($this->due_date)) === date('Y-m-d');
    }

    public function isScheduledForThisWeek()
    {
        if (!$this->due_date) return false;

        // Using strtotime() and date() functions
        $weekStart = strtotime('monday this week');
        $weekEnd = strtotime('sunday this week');
        $dueTimestamp = strtotime($this->due_date);

        return $dueTimestamp >= $weekStart && $dueTimestamp <= $weekEnd;
    }
}
