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
            ['name' => 'Administration',        'code' => 'ADM', 'description' => 'General Administration Department','head_id' => 1],
            ['name' => 'Human Resources',       'code' => 'HR',  'description' => 'Human Resources Department', 'head_id' => 2],
            ['name' => 'Information Technology','code' => 'IT',  'description' => 'Information Technology Department', 'head_id' => 3],
            ['name' => 'Finance',               'code' => 'FIN', 'description' => 'Finance Department', 'head_id' => 4],
            ['name' => 'Business Development',  'code' => 'BD',  'description' => 'Business Development Department', 'head_id' => 5],
            ['name' => 'Client Services',       'code' => 'CS',  'description' => 'Client Service Department', 'head_id' => 6],
            ['name' => 'Legal',                 'code' => 'LEG', 'description' => 'Legal Department', 'head_id' => 7],
            ['name' => 'Research & Development','code' => 'RND', 'description' => 'Research and Development Department', 'head_id' => 8],
            ['name' => 'Security',              'code' => 'SEC', 'description' => 'Security Department', 'head_id' => 9],
            ['name' => 'Innovation',            'code' => 'INN', 'description' => 'Innovation Department', 'head_id' => 10],
        ];

        foreach ($departments as $data) {
            Department::updateOrCreate(
                ['name' => $data['name']],
                [
                    'code'        => $data['code'],
                    'description' => $data['description'],
                    'head_id'     => $data['head_id'],
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                ]
            );
        }
    }
}
