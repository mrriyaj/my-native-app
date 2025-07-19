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
}
