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
    ['name' => 'Human Resources', 'description' => 'Human Resources Department', 'head_id' => 1],
    ['name' => 'Information Technology', 'description' => 'Information Technology Department', 'head_id' => 2],
    ['name' => 'Finance', 'description' => 'Finance Department', 'head_id' => 3],
    ['name' => 'Business Development', 'description' => 'Business Development Department', 'head_id' => 4],
    ['name' => 'Client Service', 'description' => 'Client Service Department', 'head_id' => 5],
    ['name' => 'Management', 'description' => 'Board of Directors Department', 'head_id' => 6],
    ['name' => 'Administration', 'description' => 'General Administration Department','head_id' => 15],
    ['name' => 'Sales & Marketing', 'description' => 'Sales and Marketing Department', 'head_id' => 7],
    ['name' => 'Legal', 'description' => 'Legal Department', 'head_id' => 9],
    ['name' => 'Research & Development', 'description' => 'Research and Development Department', 'head_id' => 10],
    ['name' => 'Quality Assurance', 'description' => 'Quality Assurance Department', 'head_id' => 11],
    ['name' => 'Customer Support', 'description' => 'Customer Support Department', 'head_id' => 12],
    ['name' => 'Logistics', 'description' => 'Logistics Department', 'head_id' => 13],
    ['name' => 'Production', 'description' => 'Production Department', 'head_id' => 14],
    ['name' => 'Security', 'description' => 'Security Department', 'head_id' => 16],
    ['name' => 'Compliance', 'description' => 'Compliance Department', 'head_id' => 17],
    ['name' => 'Training & Development', 'description' => 'Training and Development Department', 'head_id' => 18],
    ['name' => 'Public Relations', 'description' => 'Public Relations Department', 'head_id' => 19],
    ['name' => 'Procurement', 'description' => 'Procurement Department', 'head_id' => 20],
    ['name' => 'Health & Safety', 'description' => 'Health and Safety Department', 'head_id' => 21],
    ['name' => 'Environmental Sustainability', 'description' => 'Environmental Sustainability Department', 'head_id' => 22],
    ['name' => 'Data Analysis', 'description' => 'Data Analysis Department', 'head_id' => 23],
    ['name' => 'Innovation', 'description' => 'Innovation Department', 'head_id' => 24],
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
