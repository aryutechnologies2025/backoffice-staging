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

    public function stagReviews()
    {
        return $this->hasMany(StagReview::class, 'stag_id')
        ->with (['user' => function ($query) {
            $query->select('id','first_name','last_name', 'email','profile_image'); 
        }]);
    }   
}


