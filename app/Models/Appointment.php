<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    // Allow mass assignment on these fields
    protected $fillable = [
        'visitor_id',
        'visitor_name',
        'visitor_phone',
        'visitor_email',
        'host_id',
        'department_id',
        'title',
        'description',
        'date',
        'time',
        'meeting_type',
        'status',
        'decline_reason',
        'was_rescheduled',
        'rescheduled_at',
        'rescheduled_by',
        'reschedule_reason',
        'location',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i', // Cast time only
    ];

    // Primary visitor (if any)
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    // If multiple visitors are linked
    public function visitors()
    {
        return $this->belongsToMany(Visitor::class, 'appointment_visitor');
    }

    // Host user (staff member being met)
    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    // Department of the host
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Receptionist or admin who created the appointment
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Optional accessor if you're combining date + time
    public function getScheduledAtAttribute()
    {
        if ($this->date && $this->time) {
            return $this->date->format('Y-m-d') . ' ' . $this->time->format('H:i');
        }
        return null;
    }

    // One-to-one link to visit log
    public function visitLog()
    {
        return $this->hasOne(VisitLog::class);
    }
}
