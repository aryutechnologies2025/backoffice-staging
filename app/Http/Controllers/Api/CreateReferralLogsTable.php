<?php

namespace App\Http\Controllers;

use App\Models\ReferralLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReferralLogController extends Controller
{
    // Store a new referral log
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'influencer_id' => 'required|exists:influencers,id',
            'program_id' => 'nullable|exists:inclusive_package_details,id', // Make sure the program_id is valid
        ]);

        try {
            // Create the referral log entry
            $referralLog = ReferralLog::create([
                'influencer_id' => $validated['influencer_id'],
                'program_id' => $validated['program_id'] ?? null, // Default to null if program_id is not provided
                'user_ip' => $request->ip(),  // Capture the user's IP address
                'user_agent' => $request->header('User-Agent'), // Capture the User-Agent
            ]);

            // Optional: Log the creation of the referral log
            Log::info("Referral log created for influencer ID: {$referralLog->influencer_id}");

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

    // Optionally, a method to view the referral logs (e.g., for admin panel)
    public function index()
    {
        $referralLogs = ReferralLog::all();
        return response()->json([
            'status' => 'success',
            'data' => $referralLogs
        ]);
    }
}
