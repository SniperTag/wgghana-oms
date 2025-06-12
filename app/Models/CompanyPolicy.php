<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPolicy extends Model
{
    protected $fillable = ['title', 'content', 'department_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
