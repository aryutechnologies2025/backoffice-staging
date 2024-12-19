<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use MongoDB\Laravel\Eloquent\Model;
// use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
// use Illuminate\Contracts\Auth\Authenticatable;
// class FAQ extends Model implements Authenticatable
// {
//     use AuthenticatableTrait;
    
//     protected $connection = 'mongodb';
//     protected $collection = 'faq_details';
// }

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Authenticatable
{
    use HasFactory;
    protected $table = 'faq_details';
}