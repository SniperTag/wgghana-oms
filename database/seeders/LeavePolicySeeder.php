<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeavePolicy;
use App\Models\LeaveType;
use App\Models\Department;
use Spatie\Permission\Models\Role;

class LeavePolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all leave types from DB
        $leaveTypes = LeaveType::all();
        $currentYear = now()->year;

        // Define the base leave policies by role/department
        $policies = [
            [
                'name' => 'Admin Policy',
                'role' => 'admin',
                'department' => 'management',
                'annual_days' => 28
            ],
            [
                'name' => 'Manager Policy',
                'role' => 'manager',
                'department' => 'client service',
                'annual_days' => 24
            ],
            [
                'name' => 'HR Policy',
                'role' => 'hr',
                'department' => 'human resources',
                'annual_days' => 26
            ],
            [
                'name' => 'Staff Policy',
                'role' => 'staff',
                'department' => 'general',
                'annual_days' => 22
            ],
        ];

        foreach ($policies as $data) {
            $role = Role::where('name', strtolower($data['role']))->first();
            $dept = Department::whereRaw('LOWER(name) = ?', [strtolower($data['department'])])->first();

            if ($role && $dept) {
                foreach ($leaveTypes as $leaveType) {
                    // Determine default days based on leave type
                    $totalDays = match (strtolower($leaveType->name)) {
                        'annual leave' => $data['annual_days'],
                        'sick leave' => 10,
                        'maternity leave' => 90,
                        'paternity leave' => 5,
                        default => 15,
                    };

                    LeavePolicy::updateOrCreate(
                        [
                            'role_id' => $role->id,
                            'department_id' => $dept->id,
                            'leave_type_id' => $leaveType->id,
                            'year' => $currentYear,
                        ],
                        [
                            'name' => "{$data['name']} - {$leaveType->name}",
                            'total_days' => $totalDays,
                        ]
                    );

                    echo "Seeded: {$data['name']} | {$leaveType->name} | {$totalDays} days\n";
                }
            } else {
                echo "❌ Skipped: Role or department not found for '{$data['name']}'\n";
            }
        }
    }
}
