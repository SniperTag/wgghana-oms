<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentShare extends Model
{
    //
    protected $fillable = [
        'document_id',
        'user_id',
        'shared_with',
        'shared_by',
        'shared_ip',
        'shared_user_agent',
        'shared_referrer',
        'shared_at',
        'access_level',
        'status',
        'note',
        'shared_by_ip'
    ];
    protected $casts = [
        'shared_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $appends = [
        'shared_with_user',
        'shared_by_user',
    ];
    public function getSharedWithUserAttribute()
    {
        return $this->shared_with ? User::where('email', $this->shared_with)->first() : null;
    }
    public function getSharedByUserAttribute()
    {
        return $this->shared_by ? User::where('email', $this->shared_by)->first() : null;
    }
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function sharedWith()
    {
        return $this->belongsTo(User::class, 'shared_with', 'email');
    }
    public function sharedBy()
    {
        return $this->belongsTo(User::class, 'shared_by', 'email');
    }
    public function scopeWithSharedWith($query, $email)
    {
        return $query->where('shared_with', $email);
    }
    public function scopeWithSharedBy($query, $email)
    {
        return $query->where('shared_by', $email);
    }
    public function scopeWithDocumentId($query, $documentId)
    {
        return $query->where('document_id', $documentId);
    }
    public function scopeWithUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
