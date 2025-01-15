<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Influencers;
class AffiliateController extends Controller
{
    public function recordClick($ref_id)
{
    // Find the influencer by reference ID
    $influencer = Influencer::where('reference_id', $ref_id)->first();

    if ($influencer) {
        // Record the click
        AffiliateLinkClick::create([
            'influencer_id' => $influencer->id,
            'clicked_at' => now(),
            'ip_address' => request()->ip(),
        ]);

        // Redirect to the actual signup page
        return redirect('/signup/' . $ref_id);
    }

    // If the influencer is not found, redirect to a generic page
    return redirect('/');
}
}
