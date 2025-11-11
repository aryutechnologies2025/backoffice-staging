<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ActivityP extends Model
{
    use HasFactory;

    protected $table ='p_activities';

     public function priceActivity(): HasMany
    {
        return $this->hasMany(CustomerPriceCalculatorList::class, 'type_id')->where('type', 'activity')->where('is_deleted', '0');
    }

       public function destination(): HasOne
    {
        // Remove the space in 'destination_id ' and use correct foreign key
        return $this->hasOne(stay_district::class, 'id', 'destination_id');
    }

     public function city()
    {
        return $this->belongsTo(City::class, 'destination_id', 'id');
    }

}
