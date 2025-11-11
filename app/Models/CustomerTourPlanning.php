<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTourPlanning extends Model
{
    use HasFactory;

    protected $table = 'customer_tour_planning';

    protected $fillable = [
        'customer_id',
        'package_id',
        'day_title',
        'day_subtitle',
        'activity_description',
        'day_order'
    ];
}
