<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeEnquiryDetail extends Model
{
    use HasFactory;
    protected $table = 'home_enquiry_details';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'comments',
        'location',
        'days',
        'travel_destination',
        'budget_per_head',
        'cab_need',
        'total_count',
        'male_count',
        'female_count',
        'travel_date',
        'rooms_count',
        'child_count',
        'child_age',
        'engagement_date',
        'birth_date'
       
    ];

    public function followUps()
{
    return $this->hasMany(FollowUp::class, 'home_id', 'id');
}
}
