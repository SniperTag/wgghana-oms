<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_type',
        'leave_type_id',
        'leave_policy_id',
        'start_date',
        'end_date',
        'status', // final status: pending/approved/rejected
        'reason',
        'comments', // HR/Admin comments
        'attachment',
        'ip_address',
        'user_agent',
        'requested_at',
        'approved_at',
        'rejected_at',
        'approved_by',
        'rejected_by',
        'notes',

        // Supervisor review
        'supervisor_id',
        'supervisor_status', // pending/approved/rejected
        'supervisor_approved_at',
        'supervisor_comment',

        // HR review
        'hr_id',
        'hr_status', // pending/approved/rejected
        'hr_approved_at',
       
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'supervisor_approved_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function leavePolicy()
{
    return $this->belongsTo(LeavePolicy::class);
}

public function approvedByUser()
{
    return $this->belongsTo(User::class, 'approved_by');
}

public function rejectedByUser()
{
    return $this->belongsTo(User::class, 'rejected_by');
}


public function hr()
{
    return $this->belongsTo(User::class, 'hr_id');
}
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }


    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
    public function approver()
{
    return $this->belongsTo(User::class, 'approved_by');
}

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
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
    public function logs()
    {
        return $this->hasMany(LeaveLog::class);
    }

    public function addLog(string $action, int $userId, ?string $comments = null)
    {
        return $this->logs()->create([
            'action' => $action,
            'user_id' => $userId,
            'comments' => $comments,
        ]);
    }

    public function scopeCurrentlyOnLeave($query)
    {
        return $query->where('status', 'approved')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }
}
