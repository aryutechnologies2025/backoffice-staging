<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stays_whishlist extends Model
{
    use HasFactory;
   protected $fillable = ['user_id','stay_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function stay_dts()
    {
        return $this->belongsTo(stays_destination_details::class, 'stay_id');
    }
}
