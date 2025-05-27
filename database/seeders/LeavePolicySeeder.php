<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeavePolicy;
use Spatie\Permission\Models\Role;
use App\Models\Department;

class LeavePolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the leave policies to be seeded
        $policies = [
            [
                'name' => 'admin Policy',
                'role' => 'admin',
                'department' => 'management', // Make sure this department name exists in your DB
                'total_days' => 28
            ],
            [
                'name' => 'manager Policy',
                'role' => 'manager',
                'department' => 'client Service',
                'total_days' => 24
            ],
            [
                'name' => 'hr Policy',
                'role' => 'hr',
                'department' => 'Human Resources',
                'total_days' => 26
            ],
            [
                'name' => 'staff Policy',
                'role' => 'staff',
                'department' => 'General',
                'total_days' => 22
            ],
        ];

        // Loop through each policy and seed it
        foreach ($policies as $data) {
            $role = Role::where('name', $data['role'])->first();
            $dept = Department::where('name', $data['department'])->first();

            // Only create the policy if both role and department exist
            if ($role && $dept) {
                LeavePolicy::create([
                    'name' => $data['name'],
                    'total_days' => $data['total_days'],
                    'role_id' => $role->id,
                    'department_id' => $dept->id,
                ]);

                // Optional: Output to console/log for verification
                echo "Seeded leave policy: {$data['name']} (Role: {$data['role']}, Department: {$data['department']})\n";
            } else {
                echo "Skipping: Missing role or department for {$data['name']}\n";
            }
        }
    }
}
