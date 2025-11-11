<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingCalculator extends Model
{
    use HasFactory;

    protected $table = 'pricing_calculators';

    public function priceLists(): HasMany
    {
        return $this->hasMany(PriceCalculatorList::class, 'pricing_calculator_id');
    }

    public function destinations(): HasMany
    {
        return $this->hasMany(stay_district::class, 'id', 'destination_id');
    }

    // Add an accessor to get destination names
    public function getDestinationNamesAttribute()
    {
        if (empty($this->destination_id)) {
            return 'N/A';
        }

        // Convert comma-separated string to array
        $destinationIds = explode(',', $this->destination_id);
        
        // Get destination names
        $destinations = stay_district::whereIn('id', $destinationIds)
            ->pluck('destination')
            ->toArray();

        return implode(', ', $destinations);
    }

    
}