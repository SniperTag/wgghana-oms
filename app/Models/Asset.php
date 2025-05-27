<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Asset extends Model
{
    //asset_type
    const TYPE_HARDWARE = 'hardware';
    const TYPE_SOFTWARE = 'software';
    const TYPE_FURNITURE = 'furniture';
    const TYPE_VEHICLE = 'vehicle';
    //asset_subtype
    const SUBTYPE_LAPTOP = 'laptop';
    const SUBTYPE_DESKTOP = 'desktop';
    const SUBTYPE_PRINTER = 'printer';
    const SUBTYPE_MONITOR = 'monitor';
    const SUBTYPE_NETWORK_DEVICE = 'network_device';
    const SUBTYPE_SOFTWARE_LICENSE = 'software_license';
    //condition
    const CONDITION_NEW = 'new';
    const CONDITION_USED = 'used';
    const CONDITION_REFURBISHED = 'refurbished';
    //status
    const STATUS_AVAILABLE = 'available';
    const STATUS_IN_USE = 'in_use';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_RETIRED = 'retired';
    // Define the fillable attributes
    protected $fillable = [
        'name',
        'description',
        'serial_number',
        'model',
        'brand',
        'category',
        'asset_type',
        'asset_subtype',
        'asset_tag',
        'purchase_order_number',
        'vendor',
        'vendor_contact',
        'vendor_phone',
        'vendor_email',
        'condition',
        'status',
        'assigned_to_id',
        'purchase_date',
        'warranty_period',
        'warranty_expiry_date',
        'purchase_price',
        'current_value',
        'image',
        'notes'
    ];
    // Define the relationships
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }
    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }
    public function scopeInUse($query)
    {
        return $query->where('status', self::STATUS_IN_USE);
    }
    public function scopeMaintenance($query)
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }
    public function scopeRetired($query)
    {
        return $query->where('status', self::STATUS_RETIRED);
    }
    public function scopeHardware($query)
    {
        return $query->where('asset_type', self::TYPE_HARDWARE);
    }
    public function scopeSoftware($query)
    {
        return $query->where('asset_type', self::TYPE_SOFTWARE);
    }
    public function scopeFurniture($query)
    {
        return $query->where('asset_type', self::TYPE_FURNITURE);
    }
    public function scopeVehicle($query)
    {
        return $query->where('asset_type', self::TYPE_VEHICLE);
    }
    public function scopeLaptop($query)
    {
        return $query->where('asset_subtype', self::SUBTYPE_LAPTOP);
    }
    public function scopeDesktop($query)
    {
        return $query->where('asset_subtype', self::SUBTYPE_DESKTOP);
    }
    public function scopePrinter($query)
    {
        return $query->where('asset_subtype', self::SUBTYPE_PRINTER);
    }
    public function scopeMonitor($query)
    {
        return $query->where('asset_subtype', self::SUBTYPE_MONITOR);
    }
    public function scopeNetworkDevice($query)
    {
        return $query->where('asset_subtype', self::SUBTYPE_NETWORK_DEVICE);
    }
    public function scopeSoftwareLicense($query)
    {
        return $query->where('asset_subtype', self::SUBTYPE_SOFTWARE_LICENSE);
    }
    public function scopeNew($query)
    {
        return $query->where('condition', self::CONDITION_NEW);
    }
    public function scopeUsed($query)
    {
        return $query->where('condition', self::CONDITION_USED);
    }
    public function scopeRefurbished($query)
    {
        return $query->where('condition', self::CONDITION_REFURBISHED);
    }
    public function scopeAvailableStatus($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }
    public function scopeInUseStatus($query)
    {
        return $query->where('status', self::STATUS_IN_USE);
    }
    public function scopeMaintenanceStatus($query)
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }
    public function scopeRetiredStatus($query)
    {
        return $query->where('status', self::STATUS_RETIRED);
    }
    public function scopeHardwareType($query)
    {
        return $query->where('asset_type', self::TYPE_HARDWARE);
    }
    public function scopeSoftwareType($query)
    {
        return $query->where('asset_type', self::TYPE_SOFTWARE);
    }
}
