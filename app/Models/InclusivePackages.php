<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InclusivePackages extends Authenticatable
{
    use HasFactory;
    protected $table = 'inclusive_package_details';

    protected $fillable = [
        'title',
        'google_map',
    ];
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

    public function wishlists()
    {
        return $this->hasMany(Program_wishlist::class, 'program_id', 'id','user_id');
                    
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'package_id');
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
}
