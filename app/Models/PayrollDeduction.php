<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollDeduction extends Model
{
    //
    protected $fillable = [
        'user_id',
        'deduction_type',
        'amount',
        'deduction_date',
        'description',
    ];
    protected $casts = [
        'deduction_date' => 'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getDeductionTypeAttribute($value)
    {
        return ucwords(str_replace('_', ' ', $value));
    }
    public function setDeductionTypeAttribute($value)
    {
        $this->attributes['deduction_type'] = strtolower(str_replace(' ', '_', $value));
    }
    public function getFormattedDeductionDateAttribute()
    {
        return $this->deduction_date->format('d-m-Y');
    }
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }
    public function getFormattedDescriptionAttribute()
    {
        return ucwords($this->description);
    }
    public function getFormattedDeductionTypeAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->deduction_type));
    }
}
