<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $with = ['createdBy'];
    protected $appends = ['document_count'];
    protected $attributes = [
        'name' => '',
        'description' => '',
    ];
}
