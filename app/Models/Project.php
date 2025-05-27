<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    //projects table
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'department_id',
        'manager_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'priority',
        'attachment',
        'status_comment',
        'color',
        'tags',
        'ip_address',
        'user_agent',
    ];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function createdBy()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function updatedBy()
{
    return $this->belongsTo(User::class, 'updated_by');
}

public function deletedBy()
{
    return $this->belongsTo(User::class, 'deleted_by');
}

public function tasks()
{
    return $this->hasMany(Task::class);
}

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    public function scopeOnHold($query)
    {
        return $query->where('status', 'on_hold');
    }
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
    public function scopeToday($query)
    {
        return $query->whereDate('start_date', now()->format('Y-m-d'));
    }
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('start_date', now()->month);
    }
    public function scopeThisYear($query)
    {
        return $query->whereYear('start_date', now()->year);
    }
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }
    public function scopeDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }
    public function scopeManager($query, $managerId)
    {
        return $query->where('manager_id', $managerId);
    }
}
