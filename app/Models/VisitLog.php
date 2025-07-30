<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitLog extends Model
{
    //Visitors can have multiple visit logs
    protected $fillable = [
        'visitor_id',
        'host_id',
        'purpose',
        'visit_reason_detail',
        'visitor_type_id',
        'appointment_id',
        'check_in_time',
        'check_out_time',
        'badge_number',
        'approval_status',
        'rejection_reason',
        'registered_ip',
        'device_name',
        'remarks',
        'checked_in_by',
        'checked_out_by',
        'location',
        'status',
        'created_by',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class, 'visitor_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function checkedInBy()
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }
    public function checkedOutBy()
    {
        return $this->belongsTo(User::class, 'checked_out_by');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function getCheckInTimeAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }
    public function getCheckOutTimeAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }
    public function getBadgeNumberAttribute($value)
    {
        return strtoupper($value);
    }
}
