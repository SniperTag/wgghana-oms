<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //setings table
    protected $table = 'settings';
    protected $fillable = [
        'key',
        'value',
        'description',
        'type',
        'category',
        'group',
        'subgroup',
        'default',
        'validation',
        'options',
        'help_text',
        'is_required',
        'is_visible',
        'is_editable',
        'is_deletable',
        'is_system'
    ];
    protected $casts = [
        'is_required' => 'boolean',
        'is_visible' => 'boolean',
        'is_editable' => 'boolean',
        'is_deletable' => 'boolean',
        'is_system' => 'boolean',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $appends = [
        'formatted_value',
    ];
    protected $attributes = [
        'value' => null,
        'description' => null,
        'type' => 'string',
        'category' => null,
        'group' => null,
        'subgroup' => null,
        'default' => null,
        'validation' => null,
        'options' => null,
        'help_text' => null,
        'is_required' => false,
        'is_visible' => true,
        'is_editable' => true,
        'is_deletable' => true,
        'is_system' => false,
    ];
    public function getFormattedValueAttribute()
    {
        if ($this->type === 'json') {
            return json_decode($this->value, true);
        }
        return $this->value;
    }
    public function setValueAttribute($value)
    {
        if ($this->type === 'json') {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }
    public function getValueAttribute($value)
    {
        if ($this->type === 'json') {
            return json_decode($value, true);
        }
        return $value;
    }
    public function getKeyAttribute($value)
    {
        return strtolower($value);
    }
    public function setKeyAttribute($value)
    {
        $this->attributes['key'] = strtolower($value);
    }
}
