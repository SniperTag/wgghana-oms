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
            ['name' => 'Maternity', 'default_days' => 90],
            ['name' => 'Bereavement', 'default_days' => 5],
            ['name' => 'Personal', 'default_days' => 3],
        ];

        // Seed core leave types
        foreach ($types as $type) {
            LeaveType::updateOrCreate(
                ['name' => $type['name']],
                [
                    'default_days' => $type['default_days'],
                    'is_excluded' => false,
                ]
            );
            echo "Seeded: {$type['name']} Leave\n";
        }

        // Seed excluded special leave type (if needed)
        LeaveType::updateOrCreate(
            ['name' => 'Sick Leave'],
            ['default_days' => 0, 'is_excluded' => true]
        );
        echo "Seeded: Sick Leave (excluded)\n";
    }
}
