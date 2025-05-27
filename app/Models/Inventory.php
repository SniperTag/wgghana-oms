<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'item_name',
        'sku',
        'inventory_type',
        'quantity',
        'reorder_level',
        'unit_price',
        'location',
        'vendor',
        'vendor_contact',
        'vendor_email',
        'vendor_phone',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
    ];

    // Scopes
    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'reorder_level');
    }

    // Relationships (optional)
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // Accessors
    public function getTotalValueAttribute()
    {
        return $this->quantity * $this->unit_price;
    }

    public function getIsLowStockAttribute()
    {
        return $this->quantity <= $this->reorder_level;
    }
}
