<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'staff_id',
        'month',
        'gross_salary',
        'net_salary',
        'overtime',
        'payment_method',
        'notes',
        'payslip_path',
        'currency',
        'payment_date',
        'payment_reference',
        'bank_account',
        'bank_name',
        'bank_branch',
        'bank_swift_code',
        'bank_iban',
        'bank_account_holder',
        'bank_account_type',
        'tax_id',
        'ssnit_id',
        'payment_gateway',
        'transaction_id',
        'payment_status',
    ];

   // Staff user relation
public function staff()
{
    return $this->belongsTo(User::class, 'staff_id');
}

// Tax relation
public function tax()
{
    return $this->belongsTo(TaxBracket::class);
}

// SSNIT relation
public function ssnit()
{
    return $this->belongsTo(SsnitRate::class);
}

}
