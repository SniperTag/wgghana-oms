<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Visitor extends Model
{
    use Notifiable; 
  protected $fillable = [
    'full_name',
    'email',
    'gender',
    'date_of_birth',
    'nationality',
    'address',
    'city',
    'phone',
    'company',
    'id_type',
    'id_number',
    'visitor_type_id',
    'visitor_uid', // Unique identifier for the visitor
    'group_uid',
    'created_by',
    'deleted_by',
    'status',
    'photo',
    'signature',
    'is_leader',
    
];
public function host()
{
    return $this->belongsTo(User::class, 'host_id');
}

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function visitLogs()
    {
        return $this->hasMany(VisitLog::class);
    }
    public function visitorType()
    {
        return $this->belongsTo(VisitorType::class, 'visitor_type_id');
    }
    public function getFullNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }
public function group_members()
{
    return $this->hasMany(Visitor::class, 'group_uid', 'group_uid')
                ->where('is_leader', false);
}

    public function getEmailAttribute($value)
    {
        return strtolower($value);
    }
    public function getPhoneAttribute($value)
    {
        return preg_replace('/\D/', '', $value); // Remove non-numeric characters
    }
}
