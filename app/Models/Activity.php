<?php

namespace App\Models;
use Spatie\Activitylog\Models\Activity as SpatieActivity;
use Illuminate\Support\Str;

class Activity extends SpatieActivity
{
    protected $table = 'activity_log';
    public $incrementing = false; // since UUID is primary key, not auto-increment int
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}