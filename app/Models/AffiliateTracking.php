<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateTracking extends Model
{
    use HasFactory;
    protected $table = 'affiliate_tracking';

    protected $fillable = [
        'user_id',
        'influencer_id',
        'program_id',
        'ip_address',
        'referrer',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function influencer()
    {
        return $this->belongsTo(Influencers::class, 'influencer_id');
    }

    public function program()
    {
        return $this->belongsTo(InclusivePackages::class, 'program_id');
    }
}