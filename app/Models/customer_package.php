<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class customer_package extends Model
{
    use HasFactory;
    protected $table = 'customer_packages';

    public function customerpackage(): HasOne
    {
        return $this->hasOne(CustomerPricingCalculator::class, 'customer_package_id');
    }
}
