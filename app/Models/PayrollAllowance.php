<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollAllowance extends Model
{
    protected $fillable = [
        'payroll_id',
        'allowance_id',
        'amount',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }
}
