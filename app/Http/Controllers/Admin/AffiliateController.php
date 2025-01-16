<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Influencers;
class AffiliateController extends Controller
{
    public function recordClick($ref_id)
    {
        \Log::info("Affiliate route hit with ref_id: $ref_id");
    
        try {
            $influencer = Influencer::where('reference_id', $ref_id)->first();
    
            if (!$influencer) {
                \Log::error("Influencer not found for reference_id: $ref_id");
                return response()->json(['error' => 'Invalid affiliate link.'], 404);
            }
    
            AffiliateLinkClick::create([
                'influencer_id' => $influencer->id,
                'clicked_at' => now(),
                'ip_address' => request()->ip(),
            ]);
    
            return response()->json(['message' => 'Click recorded successfully.'], 200);
        } catch (\Exception $e) {
            \Log::error("Error in recordClick: " . $e->getMessage());
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
    

    
}
