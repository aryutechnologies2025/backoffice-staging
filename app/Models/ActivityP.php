<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityP extends Model
{
    use HasFactory;

    protected $table ='p_activities';

     public function priceActivity(): HasMany
    {
        return $this->hasMany(CustomerPriceCalculatorList::class, 'type_id')->where('type', 'activity')->where('is_deleted', '0');
    }
}
