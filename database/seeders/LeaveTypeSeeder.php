<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Annual', 'default_days' => 22],
            ['name' => 'Sick', 'default_days' => 12],
            ['name' => 'Maternity', 'default_days' => 90],
            ['name' => 'Paternity', 'default_days' => 5],
            ['name' => 'Bereavement', 'default_days' => 5],
            ['name' => 'Personal', 'default_days' => 3],
        ];

        foreach ($types as $type) {
            LeaveType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
