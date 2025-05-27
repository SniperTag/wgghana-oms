<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
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
    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
    // Accessors
    public function getDocumentCountAttribute()
    {
        return $this->documents()->count();
    }
}
