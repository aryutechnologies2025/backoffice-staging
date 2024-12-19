<?php


namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Themes_category extends Authenticatable
{
    use HasFactory;
    protected $table = 'themes_category';

    public function theme()
    {
        return $this->belongsTo(Themes::class, 'theme_id');
    }
}
