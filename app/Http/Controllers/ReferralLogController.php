<?php

namespace App\Http\Controllers;

use App\Models\ReferralLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReferralLogController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request parameters
        $validated = $request->validate([
            'influencer_id' => 'required|exists:influencers,id',
            'program_id' => 'nullable|exists:inclusive_package_details,id',
        ]);

        // Capture the referral identifier from the query string
        $referralId = $request->query('ref');  // This will capture the 'ref' parameter

        // Check if referral ID is passed
        if (!$referralId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Referral ID is missing.',
            ], 400);
        }

        try {
            // Create the referral log entry
            $referralLog = ReferralLog::create([
                'influencer_id' => $validated['influencer_id'],
                'program_id' => $validated['program_id'] ?? null,
                'user_ip' => $request->ip(),  // Capture the user's IP address
                'user_agent' => $request->header('User-Agent'), // Capture the User-Agent
                'referral_id' => $referralId, // Store the referral ID (ref)
            ]);

            // Optional: Log the creation of the referral log
            Log::info("Referral log created for influencer ID: {$referralLog->influencer_id} with referral ID: {$referralId}");

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Referral log created successfully',
                'data' => $referralLog
            ]);
        } catch (\Exception $e) {
            // Log the exception error
            Log::error("Error creating referral log: {$e->getMessage()}");

            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the referral log.'
            ], 500);
        }
    }
}
