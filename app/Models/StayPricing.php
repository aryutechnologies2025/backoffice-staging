<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StayPricing extends Model
{
    use HasFactory;

    public function priceStay(): HasMany
    {
        return $this->hasMany(CustomerPriceCalculatorList::class, 'type_id')->where('type', 'stay')->where('is_deleted', '0');
    }

    
}
