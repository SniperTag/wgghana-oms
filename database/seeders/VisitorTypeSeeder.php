<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VisitorType;

class VisitorTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Guest',
            'Contractor',
            'Interviewee',
            'Vendor',
            'Delivery',
            'Client',
            'Maintenance',
            'Interns',
            'Government Official',
            'VIP',
            'Meeting Attendee',
            'Consultant',
            'Student/Graduate',
        ];


        foreach ($types as $type) {
            VisitorType::firstOrCreate(['name' => $type]);
        }
    }
}
