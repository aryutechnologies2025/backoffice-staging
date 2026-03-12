<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable
{
    use HasFactory;
    protected $table = 'admin';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'profile_pic',
        'status',
        'role_id',
        'is_deleted',
        'created_by',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
