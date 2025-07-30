<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
class StepOut extends Model
{
    protected $fillable = [
        'user_id',
        'step_out_at',
        'returned_at',
        'reason',
        'status', // e.g., 'stepped out', 'available'
        
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
