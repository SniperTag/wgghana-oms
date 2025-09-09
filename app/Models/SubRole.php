<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class SubRole extends Model
{
use HasRoles;
    protected $fillable = [
        'name',
        'title',
        'guard_name',
        'role_id',
    ];

    /**
     * Get the role that owns the sub-role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the permissions associated with the sub-role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'sub_role_permissions', 'sub_role_id', 'permission_id');
    }

public function users()
    {
        return $this->hasMany(User::class, 'sub_role_id');
    }
 public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
