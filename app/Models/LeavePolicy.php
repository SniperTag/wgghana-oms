<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class LeavePolicy extends Model
{
    // Fillable fields for mass assignment
    protected $fillable = [
        'name',
        'total_days',
        'role_id',
        'department_id',
    ];

    // Casts for type enforcement
    protected $casts = [
        'total_days' => 'integer',
    ];

    // Relationships

    // One policy can affect many leave balances
    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    // One policy can define many leave types (if structured that way)
    public function leaveTypes()
    {
        return $this->hasMany(LeaveType::class);
    }

    // The role this policy is assigned to
    public function role()
    {
        return $this->belongsTo(HasRoles::class);
    }

    // The department this policy is assigned to
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Any leaves created under this policy
    public function leaveRequests()
    {
        return $this->hasMany(Leave::class);
    }
}
