<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    //
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'total_days',
        'used_days',
        'remaining_days'=> 22,
        'year',
    ];
    /**
     * Get the user who owns this leave balance.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the leave type this balance is for (e.g., vacation, sick).
     */
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    /**
     * Optional: scope for filtering by year
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }
// LeaveBalance.php
public function getRemainingDaysAttribute()
{
    return $this->total_days - $this->used_days;
}

}
