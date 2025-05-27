<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'project_id',
        'assigned_by',
        'assigned_to',
        'attachment',
        'comments',
        'ip_address',
        'user_agent',
        'status_comment',
        'tags',
        'color',
        'recurrence',
        'recurrence_end_date',
        'recurrence_rule',
        'recurrence_exceptions',
        'recurrence_id',
        'recurrence_instance',
        'recurrence_count',
        'recurrence_interval',
        'recurrence_by_day',
        'recurrence_by_month',
        'recurrence_by_year',
        'recurrence_by_month_day',
        'recurrence_by_week_no',
        'recurrence_by_set_pos',
        'recurrence_by_hour'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'recurrence_end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOnHold($query)
    {
        return $query->where('status', 'on_hold');
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    public function scopeMediumPriority($query)
    {
        return $query->where('priority', 'medium');
    }

    public function scopeLowPriority($query)
    {
        return $query->where('priority', 'low');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }

    public function scopeRecurrence($query, $recurrence)
    {
        return $query->where('recurrence', $recurrence);
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', now()->format('Y-m-d'));
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('due_date', now()->month);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('due_date', now()->year);
    }
}
