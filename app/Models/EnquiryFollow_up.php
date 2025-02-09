<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryFollow_up extends Model
{
    use HasFactory;
    protected $table = 'enquiry_follow_ups';
    protected $fillable = [
        'enquiry_id',
        'customer_name',
        'customer_location',
        'event_name',
        'no_of_persons',
        'transportation_mode',
        'travel_date_time',
        'booking_date',
        'travel_start_date',
        'travel_end_date',
        'return_mode',
        'return_travel_date_time',
        'bus_service',
        'bus_status',
        'bus_travel_date_time',
        'cab_pickup',
        'cab_travel_date_time',
        'program_details',
        'special_occasion',
        'stay_list',
        'property_name',
        'cab_service',
        'trip_status',
        'is_delected',
    ];
    public function enquiry()
    {
        return $this->belongsTo(EnquiryDetail::class);
    }

}
