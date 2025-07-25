<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceCalculatorList extends Model
{
    use HasFactory;

    protected $table = 'price_calculator_lists';

    public function pricingCalculator(): BelongsTo
    {
        return $this->belongsTo(PricingCalculator::class, 'pricing_calculator_id');
    }
}