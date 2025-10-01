<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class customer_package extends Model
{
    use HasFactory;
    protected $table = 'customer_packages';

    public function customerpackage(): HasOne
    {
        return $this->hasOne(CustomerPricingCalculator::class, 'customer_package_id');
    }

    // Relation to CustomerTourPlanning
    public function customertourplanning(): HasMany
    {
        return $this->hasMany(CustomerTourPlanning::class, 'customer_id')->where('is_deleted','0');
    }
}
