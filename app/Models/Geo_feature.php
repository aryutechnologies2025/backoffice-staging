<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geo_feature extends Authenticatable
{
    use HasFactory;
    protected $table = 'geo_feature_details';
}
