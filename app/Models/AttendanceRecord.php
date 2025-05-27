<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'attendance_date', // ✅ Correct spelling
        'check_in_time',
        'check_out_time',
        'status',
        'note',
        'ip_address',
        'device_info',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function scopeToday($query)
    {
        return $query->whereDate('attendance_date', now()->toDateString());
    }
    public function breakTimes()
{
    return $this->hasMany(BreakTime::class);
}

}
