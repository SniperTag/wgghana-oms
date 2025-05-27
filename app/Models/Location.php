<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //Location types
    const TYPE_OFFICE = 'office';
    const TYPE_WAREHOUSE = 'warehouse';
    const TYPE_STORE = 'store';
    const TYPE_OTHER = 'other';
    // Define the fillable attributes
    protected $fillable = [
        'location_name',
        'description',
        'location_type',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'phone_number',
        'email_address',
    ];
    // Define the relationships
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
    // Accessors
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->state}, {$this->country}, {$this->postal_code}";
    }
    public function getContactInfoAttribute()
    {
        return "Phone: {$this->phone_number}, Email: {$this->email_address}";
    }

}
