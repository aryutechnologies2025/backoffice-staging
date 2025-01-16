<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Influencers;
class AffiliateController extends Controller
{
    public function recordClick($ref_id)
{
    try {
        // Validate the input reference ID
        if (!preg_match('/^[a-zA-Z0-9-_]+$/', $ref_id)) {
            \Log::error("Invalid reference_id format: $ref_id");
            return redirect('/')->with('error', 'Invalid affiliate link.');
        }

        // Fetch influencer by reference_id
        $influencer = Influencer::where('reference_id', $ref_id)->first();

        if (!$influencer) {
            \Log::error("Influencer not found for reference_id: $ref_id");
            return redirect('/')->with('error', 'Invalid affiliate link.');
        }

        // Check for duplicate click (same day for the same influencer)
        $existingClick = AffiliateLinkClick::where('influencer_id', $influencer->id)
            ->whereDate('clicked_at', now()->toDateString())
            ->first();

        if ($existingClick) {
            \Log::info("Duplicate click detected for influencer_id: {$influencer->id}, ref_id: $ref_id");
            return redirect('/signup/' . $ref_id); // Redirect as the click was already recorded
        }

        // Record the click
        AffiliateLinkClick::create([
            'influencer_id' => $influencer->id,
            'clicked_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'referrer' => request()->header('Referer', 'direct'), // Optional: capture referrer
        ]);

        \Log::info("Click recorded successfully for influencer_id: {$influencer->id}, ref_id: $ref_id");

        // Redirect to the signup page with the reference ID
        return redirect('/signup/' . $ref_id);
    } catch (\Exception $e) {
        // Log the error and redirect with a generic error message
        \Log::error("Error in recordClick: " . $e->getMessage());
        return redirect('/')->with('error', 'Something went wrong.');
    }
}

    
}
