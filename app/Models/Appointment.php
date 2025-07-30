<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    //Apoimntments can have multiple visitors
    protected $fillable = [
        'visitor_id',
        'user_id',
        'department_id',
        'scheduled_at',
        'purpose',
        'check_in_time',
        'check_out_time',
        'status',
        'qr_code',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class, 'visitor_id');
    }

    public function visitors()
{
    return $this->belongsToMany(Visitor::class, 'appointment_visitor');
}

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function getScheduledAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }
}
