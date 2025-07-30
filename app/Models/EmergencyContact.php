<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'relationship', 'phone', 'email'
    ];

    protected $casts = [
        'phone' => 'string', // Ensure phone is treated as a string
        'email' => 'string', // Ensure email is treated as a string
    ];
    // Belongs to user
    public function user()
    {        return $this->belongsTo(User::class);
}

}
