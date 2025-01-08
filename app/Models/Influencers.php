<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Influencers extends Model
{
    use HasFactory;
    protected $table = 'influencers';
    protected $fillable = [
        'reference_id',
        'full_name',
        'email',
        'phone',
        'whatsapp',
        'gender',
        'age',
        'city',
        'state',
        'country',
        'instagram_name',
        'instagram_profile_link',
        'instagram_followers_count',
        'instagram_category',
        'linkedin_name',
        'linkedin_profile_link',
        'linkedin_followers_count',
        'linkedin_category',
        'youtube_name',
        'youtube_profile_link',
        'youtube_followers_count',
        'youtube_category',
        'facebook_name',
        'facebook_profile_link',
        'facebook_followers_count',
        'facebook_category',
        'twitter_name',
        'twitter_profile_link',
        'twitter_followers_count',
        'twitter_category',
        'created_by',
        'status',
        'is_deleted'

    ];
}
