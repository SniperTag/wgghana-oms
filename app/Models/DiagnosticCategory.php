<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosticCategory extends Model
{
    protected $fillable = ['name', 'description'];

    // One category has many questions
    public function questions()
    {
        return $this->hasMany(DiagnosticQuestion::class, 'category_id');
    }
}
