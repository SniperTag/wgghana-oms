<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosticQuestion extends Model
{
    protected $fillable = ['category_id', 'question', 'type', 'weight', 'options'];

    // Question belongs to a category
    public function category()
    {
        return $this->belongsTo(DiagnosticCategory::class);
    }

    // A question can have many answers
    public function answers()
    {
        return $this->hasMany(DiagnosticAnswer::class);
    }
}
