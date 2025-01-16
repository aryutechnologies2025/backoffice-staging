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
            $influencer = Influencer::where('reference_id', $ref_id)->first();
    
            if (!$influencer) {
                \Log::error("Influencer not found for reference_id: $ref_id");
                return redirect('/')->with('error', 'Invalid affiliate link.');
            }
    
            AffiliateLinkClick::create([
                'influencer_id' => $influencer->id,
                'clicked_at' => now(),
                'ip_address' => request()->ip(),
            ]);
    
            return redirect('/signup/' . $ref_id);
        } catch (\Exception $e) {
            \Log::error("Error in recordClick: " . $e->getMessage());
            return redirect('/')->with('error', 'Something went wrong.');
        }
    }
    
}
