<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
class StepOut extends Model
{
    protected $fillable = [
        'user_id',
        'stepped_out_at',
        'returned_at',
        'reason',
        'status_code',

    ];

    protected $casts = [
    'stepped_out_at' => 'datetime',
    'returned_at' => 'datetime',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'stepped out');
    }
}
