<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'department_id','staff_id','clockin_pin',
    'pin_changed',
        'phone', 'is_active', 'is_invited', 'invite_token', 'invite_token_expiry', 'invite_token_used'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

public function leaveBalances()
{
    return $this->hasMany(LeaveBalance::class);
}

    public function visitors()
    {
        return $this->hasMany(Visitors::class, 'host_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
    public function leaves()
{
    return $this->hasMany(Leave::class);
}

    public function events()
    {
        return $this->hasMany(Event::class);
    }

// User.php

public function supervisor()
{
    return $this->belongsTo(User::class, 'supervisor_id');
}

public function subordinates()
{
    return $this->hasMany(User::class, 'supervisor_id');
}


    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function departmentHeadOf()
    {
        return $this->hasMany(Department::class, 'head_id');
    }

    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->map(fn($word) => strtoupper(Str::substr($word, 0, 1)))
            ->implode('');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    protected static function booted()
{
    static::creating(function ($user) {
        do {
            $random = random_int(1000, 9999);
            $year = now()->year;
            $staffId = "WG-$random-$year";
        } while (User::where('staff_id', $staffId)->exists());

        $user->staff_id = $staffId;
    });
}
public function breakTimes()
{
    return $this->hasMany(BreakTime::class);
}
public function projects() {
    return $this->belongsToMany(Project::class);
}

public function tasks() {
    return $this->hasMany(Task::class);
}

}
