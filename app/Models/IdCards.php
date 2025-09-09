<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdCards extends Model
{
protected $fillable = [
        'user_id',
        'card_number',
        'id_type',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Additional methods can be added here as needed
}
