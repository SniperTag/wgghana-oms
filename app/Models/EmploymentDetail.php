<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmploymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'department_id', 'job_title', 'employment_type',
        'date_of_joining', 'user_type', 'supervisor_id', 'work_location','employment_status',
        'start_date', 'end_date','pay_grade', 'salary', 'benefits', 'contract_duration'
    ];

    // Belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Belongs to department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Supervisor
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
