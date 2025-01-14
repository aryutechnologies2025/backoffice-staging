<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Influencers;
class ReferralLog extends Model
{
    use HasFactory;

    // Define the table name if different from the default (optional)
    protected $table = 'referral_logs';

    // Define the fillable attributes
    protected $fillable = [
        'influencer_id',
        'program_id',
        'user_ip',
        'user_agent',
    ];

    // Define any relationships if necessary (example: belongs to Influencer)
    public function influencer()
    {
        return $this->belongsTo(Influencers::class);
    }

    // Define any additional model functions here
}
