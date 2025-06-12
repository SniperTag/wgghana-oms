<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyPolicy;
use App\Models\Department;

class CompanyPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $policies = [
            [
                'title' => 'HR Policy',
                'department' => 'hr',
                'content' => <<<HTML
<h3>HR Policy Overview</h3>
<p>The HR Department is responsible for recruitment, employee welfare, and enforcing company policy related to work ethics, discipline, and attendance.</p>
<ul>
    <li>Recruitment process must be fair and transparent</li>
    <li>All staff must adhere to the company's code of conduct</li>
    <li>Leave management and disciplinary actions are handled by HR</li>
</ul>
HTML
            ],
            [
                'title' => 'General Staff Policy',
                'department' => 'general',
                'content' => <<<HTML
<h3>General Staff Guidelines</h3>
<p>All general staff are expected to follow the standard company rules and procedures:</p>
<ul>
    <li>Work hours: 8:00 AM – 5:00 PM</li>
    <li>Lunch Break: 12:30 PM – 1:30 PM</li>
    <li>Attendance must be recorded using the biometric system or app</li>
    <li>Respectful communication is mandatory in all departments</li>
</ul>
HTML
            ],
            [
                'title' => 'IT Security Policy',
                'department' => 'it',
                'content' => <<<HTML
<h3>IT Department Security Policy</h3>
<p>The IT Department ensures the protection of company data, infrastructure, and systems. This includes:</p>
<ul>
    <li>Password policies and change enforcement</li>
    <li>Firewall and VPN access management</li>
    <li>Device monitoring and encryption protocols</li>
</ul>
HTML
            ],
            [
                'title' => 'Finance Code of Conduct',
                'department' => 'finance',
                'content' => <<<HTML
<h3>Finance Department Code</h3>
<p>Responsible for financial planning, reporting, and audit compliance:</p>
<ul>
    <li>All transactions must be logged with receipts</li>
    <li>Monthly reports to be submitted by the 5th of each month</li>
    <li>Unauthorized financial decisions are grounds for disciplinary action</li>
</ul>
HTML
            ],
        ];

        foreach ($policies as $policy) {
            $dept = Department::whereRaw('LOWER(name) = ?', [strtolower($policy['department'])])->first();

            if ($dept) {
                CompanyPolicy::updateOrCreate(
                    [
                        'title' => $policy['title'],
                        'department_id' => $dept->id,
                    ],
                    [
                        'content' => $policy['content'],
                    ]
                );
                echo "✅ Seeded: {$policy['title']} ({$policy['department']})\n";
            } else {
                echo "❌ Department not found: {$policy['department']} for policy '{$policy['title']}'\n";
            }
        }
    }
}
