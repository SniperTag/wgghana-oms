<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NextOfKin extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'date_of_birth',
        'relationship',
        'phone',
        'email',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
