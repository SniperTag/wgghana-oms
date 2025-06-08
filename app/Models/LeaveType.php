<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    //
    protected $fillable = [
        'name',
        'is_excluded',
        'default_days',
    ];
      // One leave type has many leave requests
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    // One leave type has many balances (per user per year)
    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }
}
