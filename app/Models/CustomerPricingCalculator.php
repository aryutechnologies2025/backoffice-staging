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
}
