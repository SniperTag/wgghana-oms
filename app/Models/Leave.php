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
        'reason',
        'status',

        // Supervisor
        'supervisor_required',
        'supervisor_id',
        'supervisor_status',
        'supervisor_approved_at',
        'supervisor_comment',
        'supervisor_rejection_reasons',

        // HR
        'hr_id',
        'hr_status',
        'hr_approved_at',
        'hr_rejection_reasons',

        // System tracking
        'attachment',
        'ip_address',
        'user_agent',
        'requested_at',
        'approved_at',
        'rejected_at',
        'approved_by',
        'rejected_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'supervisor_approved_at' => 'datetime',
        'hr_approved_at' => 'datetime',
    ];

    /******************
     * RELATIONSHIPS
     ******************/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function hr()
    {
        return $this->belongsTo(User::class, 'hr_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
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

    public function logs()
    {
        return $this->hasMany(LeaveLog::class);
    }

    /******************
     * SCOPES
     ******************/

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCurrentlyOnLeave($query)
    {
        return $query->where('status', 'approved')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('start_date', now()->toDateString());
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

    /******************
     * HELPERS
     ******************/

    public function addLog(string $action, int $userId, ?string $comments = null)
    {
        return $this->logs()->create([
            'action' => $action,
            'user_id' => $userId,
            'comments' => $comments,
        ]);
    }
}
