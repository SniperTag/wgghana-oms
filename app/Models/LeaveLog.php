<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveLog extends Model
{
    protected $fillable = [
        'leave_id',
        'performed_by',
        'action',
        'comments',
    ];

    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
