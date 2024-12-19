<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program_wishlist extends Authenticatable
{
    use HasFactory;
    protected $table = 'program_wishlist';
    protected $fillable = [
        'user_id',
        'program_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function program_dts()
    {
        return $this->belongsTo(InclusivePackages::class, 'program_id');
    }
}
