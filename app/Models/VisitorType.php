<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorType extends Model
{
    protected $fillable = [
        'name',
        'requires_user_id',
        'requires_checklist',
        'description',
        'created_by',
    ];

    // Relationship to visitors using this type
    public function visitors()
    {
        return $this->hasMany(Visitor::class, 'visitor_type_id');
    }

    // Relationship to creator (admin/receptionist)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessor to format the name
    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }
    
}
