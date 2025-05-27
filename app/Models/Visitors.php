<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitors extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'company',
        'purpose_of_visit',
        'visit_date',
        'check_in_time',
        'check_out_time',
        'host_id',
        'status'
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime'
    ];
    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
    public function scopeCheckedIn($query)
    {
        return $query->where('status', 'checked_in');
    }
    public function scopeCheckedOut($query)
    {
        return $query->where('status', 'checked_out');
    }
    public function scopeToday($query)
    {
        return $query->whereDate('visit_date', now()->format('Y-m-d'));
    }
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('visit_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('visit_date', now()->month);
    }
    public function scopeThisYear($query)
    {
        return $query->whereYear('visit_date', now()->year);
    }
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('full_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone_number', 'like', "%{$search}%")
                ->orWhere('company', 'like', "%{$search}%")
                ->orWhere('purpose_of_visit', 'like', "%{$search}%");
        });
    }
    public function scopeFilterByDate($query, $startDate, $endDate)
    {
        return $query->whereBetween('visit_date', [$startDate, $endDate]);
    }
    public function scopeFilterByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public function scopeFilterByHost($query, $hostId)
    {
        return $query->where('host_id', $hostId);
    }
    public function scopeFilterByCompany($query, $company)
    {
        return $query->where('company', 'like', "%{$company}%");
    }
    public function scopeFilterByPhone($query, $phone)
    {
        return $query->where('phone_number', 'like', "%{$phone}%");
    }
    public function scopeFilterByEmail($query, $email)
    {
        return $query->where('email', 'like', "%{$email}%");
    }
}
