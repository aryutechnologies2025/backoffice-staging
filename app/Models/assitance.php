<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assitance extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'phone', 'comments'];
    protected $table = 'assitances';
}
