<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackAffiliate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $ref = $request->query('ref');
        if ($ref) {
            // Decode influencer ID from ref
            $influencerId = $this->getInfluencerIdFromRef($ref);
    
            // Log the tracking
            \App\Models\AffiliateTracking::create([
                'user_id' => auth()->id(), // Logged-in user ID or null for guests
                'influencer_id' => $influencerId,
                'program_id' => $request->route('program_id'), // Assuming program_id is in the route
                'ip_address' => $request->ip(),
                'referrer' => $request->headers->get('referer'),
            ]);
        }
    
        return $next($request);
    }
    
    private function getInfluencerIdFromRef($ref)
    {
        // Implement logic to extract influencer ID from ref
        return \App\Models\Influencer::where('code', $ref)->value('id');
    }
    
}
