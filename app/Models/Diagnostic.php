<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnostic extends Model
{
    protected $fillable = ['user_id', 'title'];

    // One diagnostic belongs to a user (SME)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // One diagnostic has many answers
    public function answers()
    {
        return $this->hasMany(DiagnosticAnswer::class);
    }
}
