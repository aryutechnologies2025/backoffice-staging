<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'package_id', 'comment', 'rating' , 'is_deleted',
    ];

   // Relationship with the User model
   public function user()
   {
       return $this->belongsTo(User::class, 'user_id');
   }

   // Relationship with the InclusivePackageDetail model
   public function package()
   {
       return $this->belongsTo(InclusivePackages::class, 'package_id');
   }
}
