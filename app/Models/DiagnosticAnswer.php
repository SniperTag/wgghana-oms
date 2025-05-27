<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosticAnswer extends Model
{
    protected $fillable = ['diagnostic_id', 'question_id', 'user_id', 'answer', 'score'];

    // Each answer is linked to a diagnostic session
    public function diagnostic()
    {
        return $this->belongsTo(Diagnostic::class);
    }

    // Each answer is linked to a question
    public function question()
    {
        return $this->belongsTo(DiagnosticQuestion::class);
    }

    // The user who provided this answer
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
