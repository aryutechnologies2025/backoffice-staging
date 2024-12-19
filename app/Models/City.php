<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use MongoDB\Laravel\Eloquent\Model;
// use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
// use Illuminate\Contracts\Auth\Authenticatable;
// class City extends Model implements Authenticatable
// {
//     use AuthenticatableTrait;
    
//     protected $connection = 'mongodb';
//     protected $collection = 'city_details';
// }



namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Authenticatable
{
    use HasFactory;
    protected $table = 'city_details';
}
