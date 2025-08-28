<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cab extends Model
{
    use HasFactory;

    public function priceCabs(): HasMany
    {
        return $this->hasMany(CustomerPriceCalculatorList::class, 'type_id')->where('type', 'cabs')->where('is_deleted', '0');
    }
}
