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
    ['name' => 'hr', 'description' => 'Human Resources Department', 'head_id' => 1],
    ['name' => 'it', 'description' => 'Information Technology Department', 'head_id' => 2],
    ['name' => 'finance', 'description' => 'Finance Department', 'head_id' => 3],
    ['name' => 'business development', 'description' => 'Business Development Department', 'head_id' => 4],
    ['name' => 'client service', 'description' => 'Client Service Department', 'head_id' => 5],
    ['name' => 'management', 'description' => 'Board of Directors Department', 'head_id' => 6],
    ['name' => 'general', 'description' => 'General Staff Department', 'head_id' => null],
    ['name' => 'marketing', 'description' => 'Marketing Department', 'head_id' => 7],
    ['name' => 'sales', 'description' => 'Sales Department', 'head_id' => 8],
    ['name' => 'legal', 'description' => 'Legal Department', 'head_id' => 9],
    ['name' => 'research and development', 'description' => 'Research and Development Department', 'head_id' => 10],
    ['name' => 'quality assurance', 'description' => 'Quality Assurance Department', 'head_id' => 11],
    ['name' => 'customer support', 'description' => 'Customer Support Department', 'head_id' => 12],
    ['name' => 'logistics', 'description' => 'Logistics Department', 'head_id' => 13],
    ['name' => 'production', 'description' => 'Production Department', 'head_id' => 14],
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
