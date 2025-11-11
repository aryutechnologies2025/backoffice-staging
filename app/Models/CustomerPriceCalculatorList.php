<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class CustomerPriceCalculatorList extends Model
{
    use HasFactory;

    protected $table = 'customer_price_calculator_lists';
    protected $fillable = [
        'customer_pricing_id',
        'type',
        'type_id',
        'title',
        'price_title',
        'price',
        'is_deleted'
    ];


    public function pricingCalculator(): BelongsTo
    {
        return $this->belongsTo(CustomerPricingCalculator::class, 'customer_pricing_id');
    }

    public function StayPricing(): BelongsTo
    {
        return $this->belongsTo(StayPricing::class, 'type_id');
    }

    
}
