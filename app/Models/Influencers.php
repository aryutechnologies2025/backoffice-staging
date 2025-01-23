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
        'is_deleted',
        'referral_code', // Ensure this column exists in the database
    ];

    /**
     * Get affiliate links for this influencer based on inclusive packages.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAffiliateLinks()
    {
        $affiliateLinks = InclusivePackages::where('is_deleted', '0')
            ->where('status', "1")
            ->get()
            ->map(function ($package) {
                // If referral_code is missing, provide a placeholder response
                if (empty($this->referral_code)) {
                    return [
                        'title' => $package->title,
                        'link' => 'Referral code is missing.',
                    ];
                }

                // Generate the affiliate link using the reference_id
                $affiliateLink = $package->getAffiliateLink($this->reference_id);

                return [
                    'title' => $package->title,
                    'link' => $affiliateLink,
                ];
            });

        return $affiliateLinks;
    }

    /**
     * Define a relationship to the AffiliateLinks model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function affiliateLinks()
    {
        return $this->hasMany(AffiliateLinks::class, 'influencer_id');
    }
}
