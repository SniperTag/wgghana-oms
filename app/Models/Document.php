<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Architecture\Enums\Visibility;
use App\Models\Status;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_type',
        'file_size',
        'access_level',
        'user_id',
        'project_id',
        'category_id',
        'status_id',
        'visibility_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'approved_by',
        'rejected_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $with = ['user', 'project', 'category', 'status', 'visibility'];
    protected $appends = ['file_url', 'formatted_file_size'];

    protected $attributes = [
        'access_level' => 'private',
    ];

    // Relationships
    public function user() { return $this->belongsTo(User::class); }
    public function project() { return $this->belongsTo(Project::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function status() { return $this->belongsTo(Status::class); }

    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy() { return $this->belongsTo(User::class, 'updated_by'); }
    public function deletedBy() { return $this->belongsTo(User::class, 'deleted_by'); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
    public function rejectedBy() { return $this->belongsTo(User::class, 'rejected_by'); }

    // Accessors
    public function getFileUrlAttribute()
    {
        return url($this->file_path);
    }

    public function getFormattedFileSizeAttribute()
    {
        return $this->file_size ? number_format($this->file_size / 1024, 2) . ' KB' : null;
    }

    public function getAccessLevelAttribute($value)
    {
        return $value ?? 'private';
    }

    public function setAccessLevelAttribute($value)
    {
        $this->attributes['access_level'] = $value ?? 'private';
    }
}
