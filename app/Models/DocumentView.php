<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentView extends Model
{
    //
    protected $fillable = [
        'document_id',
        'user_id',
        'ip_address',
        'user_agent',
        'referrer',
        'viewed_at',
        'viewed_by',
        'viewed_ip',
    ];
    protected $casts = [
        'viewed_at' => 'datetime',
    ];
    protected $table = 'document_views';
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getDocumentViewCount($documentId)
    {
        return $this->where('document_id', $documentId)->count();
    }
    public function getUserDocumentViewCount($userId)
    {
        return $this->where('user_id', $userId)->count();
    }
    public function getDocumentViewsByUser($userId)
    {
        return $this->where('user_id', $userId)->get();
    }
    public function getDocumentViewsByDocument($documentId)
    {
        return $this->where('document_id', $documentId)->get();
    }
    public function getDocumentViewsByDate($date)
    {
        return $this->whereDate('viewed_at', $date)->get();
    }
    public function getDocumentViewsByDateRange($startDate, $endDate)
    {
        return $this->whereBetween('viewed_at', [$startDate, $endDate])->get();
    }
    public function getDocumentViewsByIp($ipAddress)
    {
        return $this->where('ip_address', $ipAddress)->get();
    }
    public function getDocumentViewsByUserAgent($userAgent)
    {
        return $this->where('user_agent', 'like', '%' . $userAgent . '%')->get();
    }
    public function getDocumentViewsByReferrer($referrer)
    {
        return $this->where('referrer', 'like', '%' . $referrer . '%')->get();
    }
    public function getDocumentViewsByViewedAt($viewedAt)
    {
        return $this->where('viewed_at', $viewedAt)->get();
    }
    public function getDocumentViewsByViewedBy($viewedBy)
    {
        return $this->where('viewed_by', 'like', '%' . $viewedBy . '%')->get();
    }
}
