<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use MongoDB\Laravel\Eloquent\Model;
// use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
// use Illuminate\Contracts\Auth\Authenticatable;
// class Podcast extends Model implements Authenticatable
// {
//     use AuthenticatableTrait;
    
//     protected $connection = 'mongodb';
//     protected $collection = 'podcast_details';
// }

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Podcast extends Authenticatable
{
    use HasFactory;
    protected $table = 'podcast_details';
}
