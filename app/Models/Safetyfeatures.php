<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use MongoDB\Laravel\Eloquent\Model;
// use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
// use Illuminate\Contracts\Auth\Authenticatable;
// class Safetyfeatures extends Model implements Authenticatable
// {
//     use AuthenticatableTrait;
    
//     protected $connection = 'mongodb';
//     protected $collection = 'safety_feature';
// }

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Safetyfeatures extends Authenticatable
{
    use HasFactory;
    protected $table = 'safety_feature';
}
