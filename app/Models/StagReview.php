<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StagReview extends Model
{
    use HasFactory;
    protected $table = 'stag_reviews';
    protected $fillable = [
        'user_id',
        'stag_id',
        'review',
        'rating',
        'is_deleted',
        'created_by',
    ];
}
