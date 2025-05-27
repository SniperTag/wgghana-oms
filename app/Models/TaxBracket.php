<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxBracket extends Model
{
    protected $fillable = [
        'name',
        'min_amount',
        'max_amount',
        'rate',
        'fixed_amount',
        'description',
        // include other boolean flags if you want mass assignable fields
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'rate' => 'decimal:2',
        'fixed_amount' => 'decimal:2',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'formatted_min_income',
        'formatted_max_income',
        'formatted_tax_rate',
        'formatted_tax_bracket',
        'formatted_tax_bracket_with_description',
        'formatted_tax_bracket_with_name',
    ];

    public function getFormattedMinIncomeAttribute()
    {
        return number_format($this->min_amount, 2);
    }

    public function getFormattedMaxIncomeAttribute()
    {
        return number_format($this->max_amount, 2);
    }

    public function getFormattedTaxRateAttribute()
    {
        return number_format($this->rate, 2) . '%';
    }

    public function getFormattedDescriptionAttribute()
    {
        return ucwords($this->description);
    }

    public function getFormattedNameAttribute()
    {
        return ucwords($this->name);
    }

    public function getFormattedTaxBracketAttribute()
    {
        return $this->formatted_min_income . ' - ' . $this->formatted_max_income . ' (' . $this->formatted_tax_rate . ')';
    }

    public function getFormattedTaxBracketWithDescriptionAttribute()
    {
        return $this->formatted_tax_bracket . ' - ' . $this->formatted_description;
    }

    public function getFormattedTaxBracketWithNameAttribute()
    {
        return $this->formatted_tax_bracket . ' - ' . $this->formatted_name;
    }
}
