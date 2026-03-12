<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulePermission extends Model
{
    use HasFactory;

    protected $table = 'module_permission';

    protected $fillable = [
        'permission_id',
        'module',
        'is_view',
        'is_edit',
        'is_delete'
    ];
//  'permission_id',
//         'module',
//         'is_view',
//         'is_create',
//         'is_edit',
//         'is_delete',
//         'is_list'


    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
