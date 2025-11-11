<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stay_district extends Model
{
    use HasFactory;
    // protected $table = 'stay_districts';

    protected $fillable = [
        'districts_data',
        'destination' // Add this to allow mass assignment
        // Add other fillable fields if needed
    ];

    protected $casts = [
        'districts_data' => 'array'  // Ensure proper JSON casting
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'destination', 'id');
    }
}
