<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'role_id',
        
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function attendanceRecord()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
    public function leaveRequests()
    {
        return $this->hasMany(Leave::class);
    }
    public function breakTimes()
    {
        return $this->hasMany(BreakTime::class);
    }
 
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }
}
