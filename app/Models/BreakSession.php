<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class BreakSession extends Model
{
    protected $fillable = [
        'user_id',
        'attendance_id',
        'started_at',
        'ended_at',
        'break_duration',
        'break_type',      
    ];

    // Cast timestamps to Carbon instances
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Relationship: BreakSession belongs to a User (staff)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: BreakSession optionally belongs to an AttendanceRecord
     */
    public function attendance()
    {
        return $this->belongsTo(AttendanceRecord::class);
    }

    /**
     * Accessor to get the break duration in minutes dynamically
     */
    public function getDurationInMinutesAttribute()
    {
        if ($this->started_at && $this->ended_at) {
            return $this->ended_at->diffInMinutes($this->started_at);
        }
        return 0;
    }
}
