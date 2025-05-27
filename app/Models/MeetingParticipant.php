<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingParticipant extends Model
{
    //Metting participants table
    protected $fillable = [
        'meeting_id',
        'user_id',
        'status',
        'ip_address',
        'user_agent',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }
    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }
    public function scopeTentative($query)
    {
        return $query->where('status', 'tentative');
    }
    public function scopeNoResponse($query)
    {
        return $query->where('status', 'no_response');
    }
    public function scopeNotResponded($query)
    {
        return $query->where('status', 'not_responded');
    }
    public function scopeAttending($query)
    {
        return $query->where('status', 'attending');
    }
    public function scopeNotAttending($query)
    {
        return $query->where('status', 'not_attending');
    }
    public function scopeMaybe($query)
    {
        return $query->where('status', 'maybe');
    }
    public function scopeNotInvited($query)
    {
        return $query->where('status', 'not_invited');
    }
    public function scopeInvited($query)
    {
        return $query->where('status', 'invited');
    }
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function scopeAcceptedOrTentative($query)
    {
        return $query->whereIn('status', ['accepted', 'tentative']);
    }
    public function scopeDeclinedOrTentative($query)
    {
        return $query->whereIn('status', ['declined', 'tentative']);
    }
    public function scopeAcceptedOrDeclined($query)
    {
        return $query->whereIn('status', ['accepted', 'declined']);
    }
    public function scopeAcceptedOrDeclinedOrTentative($query)
    {
        return $query->whereIn('status', ['accepted', 'declined', 'tentative']);
    }
    public function scopeAcceptedOrDeclinedOrTentativeOrNoResponse($query)
    {
        return $query->whereIn('status', ['accepted', 'declined', 'tentative', 'no_response']);
    }
}
