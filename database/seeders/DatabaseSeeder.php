<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SubRole;
use App\Models\LeaveType;
use App\Models\Department;
use App\Models\LeaveBalance;
use Illuminate\Database\Seeder;
use App\Models\EmergencyContact;
use App\Models\EmploymentDetail;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run all supporting seeders in the right order
        $this->call([
            DepartmentSeeder::class,
            RolePermissionSeeder::class,
            LeaveTypeSeeder::class,
            LeavePolicySeeder::class,
            VisitorTypeSeeder::class,
            AdminSeeder::class,
            HolidaySeeder::class,
            LeaveBalanceSeeder::class,
            TaxBracketSeeder::class,
            CompanyPolicySeeder::class,

        ]);

        // Now that roles, departments, subroles, and leave types exist,
        // safely create the admin user
    }


}
