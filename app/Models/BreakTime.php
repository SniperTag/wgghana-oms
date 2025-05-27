<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreakTime extends Model
{
    protected $fillable = [
        'user_id',
        'attendance_id',
        'break_start',
        'break_end',
        'break_duration',
        'break_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->belongsTo(AttendanceRecord::class);
    }

    public function getDurationInMinutesAttribute()
    {
        if ($this->break_start && $this->break_end) {
            return $this->break_end->diffInMinutes($this->break_start);
        }
        return 0;
    }
    
}
