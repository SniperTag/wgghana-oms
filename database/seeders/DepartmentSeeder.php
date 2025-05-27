<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'HR',
                'description' => 'Human Resources Department',
                'head_id' => 1,
            ],
            [
                'name' => 'IT Department',
                'description' => 'Information Technology Department',
                'head_id' => 2,
            ],
            [
                'name' => 'Finance Officer',
                'description' => 'Finance Department',
                'head_id' => 3,
            ],
            [
                'name' => 'Business Development',
                'description' => 'Business Development Department',
                'head_id' => 4,
            ],
            [
                'name' => 'Client Service',
                'description' => 'Client Service Department',
                'head_id' => 5,
            ],
            [
                'name' => 'Management',
                'description' => 'Board of Directors Department',
                'head_id' => 6,
            ],
        ];

        foreach ($departments as $data) {
            Department::updateOrCreate(
                ['name' => $data['name']],
                [
                    'description' => $data['description'],
                    'head_id' => $data['head_id'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}
