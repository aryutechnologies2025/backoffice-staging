<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import the Str class

class InclusivePackages extends Authenticatable
{
    use HasFactory;
    protected $table = 'inclusive_package_details';

    protected $fillable = [
        'title',
        'google_map',
        'slug',
        'program_pdf'
    ];


    public function enquiries()
    {
        return $this->hasMany(EnquiryDetail::class, 'package_id');
    }
    
    public function destination()
    {
        return $this->belongsTo(City::class, 'city_details');
    }

    // public function destinationCategory()
    // {
    //     return $this->belongsTo(Destination_category::class, 'destination_cat');
    // }

    public function theme()
    {
        return $this->belongsTo(Themes::class, 'theme_id');
        
    }

    // public function theme_cat()
    // {
    //     return $this->belongsTo(Themes_category::class, 'theme_cat_id');
    // }

    // public function geo_feature_dts()
    // {
    //     return $this->belongsTo(Themes_category::class, 'geo_feature');
    // }

    // public function clientReviews()
    // {
    //     return $this->hasMany(Clientreview::class, 'program_id', 'id');
    // }
    public function clientReviews()
    {
        return $this->hasMany(Clientreview::class, 'program_id', 'id')
                    ->where('status', '1')
                    ->where('is_deleted', '0');
    }

    public function pdf()
    {
        return $this->hasMany(program_pdf::class, 'program_id', 'id')
                    ->where('status', '1')
                    ->where('is_deleted', '0');
    }

    public function wishlists()
    {
        return $this->hasMany(Program_wishlist::class, 'program_id', 'id','user_id');
                    
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'package_id')->where('status', '1')
        ->where('is_deleted', '0')->with('user');
    }
    public function themes()
{
    return $this->belongsTo(themes::class, 'theme_id', 'id');
}

public function amenities()
{
    return $this->hasMany(Amenities::class ,  'id');
}

// Define relationship for food & beverage
public function foodAndBeverages()
{
    return $this->hasMany(FoodBeverage::class,  'id');
}

// Define relationship for activities
public function activities()
{
    return $this->hasMany(Activities::class ,  'id');
}

// Define relationship for safety features
public function safetyFeatures()
{
    return $this->hasMany(Safetyfeatures::class,  'id');
}
    // public function program_wishlists()
    // {
    //     return $this->hasMany(Program_wishlist::class, 'program_id');
    // }



    public function getAffiliateLink($referralCode)
    {
        // Ensure that the package title is available and referral code is passed
        if (empty($this->title) || empty($referralCode)) {
            return 'Error generating affiliate link. Missing data.';
        }
    
        try {
            $url = "https://innerpece.com";
            // Generate the affiliate URL
            $affiliateLink = $url . '/' . $this->id . '/' . Str::slug($this->title) . '?ref=' . $referralCode;
            return $affiliateLink;
        } catch (\Exception $e) {
            // In case of any exception, return a generic error message
            return 'Error generating affiliate link. Please try again.';
        }
    }
    
    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         // Automatically generate slug if not provided
    //         if (empty($model->slug)) {
    //             $model->slug = Str::slug($model->title);  // Generate slug from the title
    //         }
    //     });

    //     static::updating(function ($model) {
    //         // Ensure slug is updated when the title changes
    //         if (empty($model->slug)) {
    //             $model->slug = Str::slug($model->title);
    //         }
    //     });
    // }

}

// if ($request->hasFile('program_pdf')) {
//     $file = $request->file('program_pdf');
//     $extension = $file->getClientOriginalExtension();
//     $filename = time() . '.' . $extension;

//     $file->move(public_path('uploads/program_pdfs'), $filename);
//     $inclusive_packages->program_pdf = $filename;
// }