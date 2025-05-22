<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stays_destination_details extends Model
{
    use HasFactory;

    public function amenities()
    {
    return $this->hasMany(Amenities::class ,'id',  'amenity_details');

    }
}


