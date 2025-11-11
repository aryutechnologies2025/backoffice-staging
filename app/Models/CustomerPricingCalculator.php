<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerPricingCalculator extends Model
{
    use HasFactory;

    protected $table = 'customer_pricing_calculators';


    public function priceLists(): HasMany
    {
        return $this->hasMany(CustomerPriceCalculatorList::class, 'customer_pricing_id');
    }

    public function pricingCalculator(): BelongsTo
    {
        return $this->belongsTo(customer_package::class, 'customer_pricing_id');
    }


    // Specific relationship for stays only
    public function stayLists(): HasMany
    {
        return $this->hasMany(CustomerPriceCalculatorList::class, 'customer_pricing_id')
            ->select('price')
            ->where('type', 'stay');
    }

    // Specific relationship for activities only
    public function activityLists(): HasMany
    {
        return $this->hasMany(CustomerPriceCalculatorList::class, 'customer_pricing_id')
            ->select('price')
            ->where('type', 'activity');
    }

    // Specific relationship for cabs only
    public function cabLists(): HasMany
    {
        return $this->hasMany(CustomerPriceCalculatorList::class, 'customer_pricing_id')
            ->select('price')
            ->where('type', 'cabs');
    }


    // In your CustomerPricing model
    public function getStayTotalAttribute()
    {
        return $this->stayLists()->sum('price');
    }

    public function getActivityTotalAttribute()
    {
        return $this->activityLists()->sum('price');
    }

    public function getCabTotalAttribute()
    {
        return $this->cabLists()->sum('price');
    }

}
