<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InclusivePackages;
use App\Models\Review;
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
        'male_count',
        'female_count',
        'travel_date',
        'rooms_count',
        'reference_id',
        'package_id',
        'program_title',
        'child_count',
        'child_age',
        'user_id'
       
    ];
    public function package()
{
    return $this->belongsTo(InclusivePackages::class, 'package_id');
}
public function user()
{
    return $this->belongsTo(User::class);
}

    
public function themes()
{
    return $this->package ? $this->package->theme() : null;
}
// In EnquiryDetail.php
public function review()
{
    return $this->hasOne(Review::class, 'enquiry_id'); // Adjust 'enquiry_id' to the correct foreign key
}

}

?>