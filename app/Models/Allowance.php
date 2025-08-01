<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    protected $fillable = ['payroll_id', 'type', 'amount'];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
