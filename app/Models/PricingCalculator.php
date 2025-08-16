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

    
}