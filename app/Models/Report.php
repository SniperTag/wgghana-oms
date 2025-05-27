<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'report_type',
        'content',
        'report_date',
        'status',
        'reviewed_by',
        'review_comments',
    ];

    /**
     * The user who submitted the report.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The assessments linked to this report.
     */
   
}
