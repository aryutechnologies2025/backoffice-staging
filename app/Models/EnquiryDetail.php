<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryDetail extends Model
{
    use HasFactory;
    protected $table = 'enquiry_details';
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
        'male_female_count',
        'travel_date',
        'rooms_count',
    ];
}

?>