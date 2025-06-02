<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stay_enquiry_details extends Model
{
    use HasFactory;
      protected $fillable = [
        'name',
        'email',
        'phone',
        'comments',
        'location',
        'stay_title',
        'birth_date',
        'engagement_date',
        'no_of_days',
        'total_count',
        'male_count',
        'female_count',
        'child_count',
        'checkin_date',
        'checkout_count',
        'cab',
        'price'
        
    ];
}
