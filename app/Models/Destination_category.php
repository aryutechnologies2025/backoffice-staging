<?php


namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination_category extends Authenticatable
{
    use HasFactory;
    protected $table = 'destination_category';

    public function destination()
    {
        return $this->belongsTo(City::class, 'destination_id');
    }
}
