<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
       'package_id',
        'user_id',
        'alternate_name',
        'upload_image_name',
        'comment',
        'review_dt',
        'rating',
        'client_pic',
        'status',
        'created_date',
        'created_by',
        'is_deleted',
        'updated_at',
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
