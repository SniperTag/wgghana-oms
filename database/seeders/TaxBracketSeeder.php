<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaxBracket;

class TaxBracketSeeder extends Seeder
{
    public function run(): void
    {
        TaxBracket::truncate(); // Clear existing entries if needed

        $brackets = [
            ['name' => 'First GHS 0 – 402',    'min' => 0.00,    'max' => 402.00,  'rate' => 0,   'fixed' => 0],
            ['name' => 'Next GHS 403 – 1100',  'min' => 402.01,  'max' => 1100.00, 'rate' => 5,   'fixed' => 0],
            ['name' => 'Next GHS 1101 – 3200', 'min' => 1100.01, 'max' => 3200.00, 'rate' => 10,  'fixed' => 0],
            ['name' => 'Next GHS 3201 – 5300', 'min' => 3200.01, 'max' => 5300.00, 'rate' => 17.5,'fixed' => 0],
            ['name' => 'Next GHS 5301 – 35000','min' => 5300.01, 'max' => 35000.00,'rate' => 25,  'fixed' => 0],
            ['name' => 'Above GHS 35000',      'min' => 35000.01,'max' => 9999999,'rate' => 30,  'fixed' => 0],
        ];

        foreach ($brackets as $bracket) {
            TaxBracket::create([
                'name' => $bracket['name'],
                'min_amount' => $bracket['min'],
                'max_amount' => $bracket['max'],
                'rate' => $bracket['rate'],
                'fixed_amount' => $bracket['fixed'],
                'is_active' => true,
                'is_taxable' => true,
                'is_progressive' => true,
                'is_monthly' => true,
            ]);
        }
    }
}
