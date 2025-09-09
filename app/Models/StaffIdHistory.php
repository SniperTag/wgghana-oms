<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffIdHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'old_staff_id',
        'new_staff_id',
        'reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
