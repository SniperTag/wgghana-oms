<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffStatus extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'created_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'staff_status_id');
    }

    public function getUserCountAttribute()
    {
        return $this->users()->count();
    }
}
