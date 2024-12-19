<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use MongoDB\Laravel\Eloquent\Model;
// use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
// use Illuminate\Contracts\Auth\Authenticatable;
// class Blog extends Model implements Authenticatable
// {
//     use AuthenticatableTrait;
    
//     protected $connection = 'mongodb';
//     protected $collection = 'blogs';
//     protected $fillable = [
//         'title',
//         'description',
//         'img_one',
//         'img_two',
//         'img_three',
//         'img_four'
//     ];
// }

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Authenticatable
{
    use HasFactory;
    protected $table = 'blogs';
}
