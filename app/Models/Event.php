<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
    //Event table
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'all_day',
        'recurring',
        'recurrence_rule',
        'recurrence_end_date',
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
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'recurrence_end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
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
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }
    public function scopePast($query)
    {
        return $query->where('start_date', '<', now());
    }
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }
    public function scopeFilterByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
