<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Authenticatable
{
    use HasFactory;
    protected $table = 'settings';

    protected $primaryKey = 'id';

    // Allow mass assignment for these attributes
    protected $fillable = [
        'id',
        'meta_title',
        'meta_keywords',
        'meta_desc',
        'site_logo',
        'footer_logo',
        'fav_icon',
        // Add other fields you want to be mass assignable
    ];
}
