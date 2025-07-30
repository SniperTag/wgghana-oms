<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferLog extends Model
{
    //Transfers can have multiple transfer logs`
   protected $fillable = [
    'visitor_id',
    'from_host',
    'to_host',
    'reason',
    'transferred_at',
    'transferred_by',
    'created_by',
];

protected $casts = [
    'transferred_at' => 'datetime',
];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class, 'visitor_id');
    }
    public function transferredBy()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function getTransferTimeAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }
    public function getFromLocationAttribute($value)
    {
        return ucwords(strtolower($value));

    }

    public function getFromHostAttribute($value)
{
    return ucwords(strtolower($value));
}

public function getToHostAttribute($value)
{
    return ucwords(strtolower($value));
}
}
