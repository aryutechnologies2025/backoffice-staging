<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function modules()
    {
        return $this->hasMany(ModulePermission::class, 'permission_id', 'id');
    }


    public function modulesP()
    {
        return $this->hasMany(ModulePermission::class, 'permission_id');
    }
}
