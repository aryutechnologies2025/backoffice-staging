<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\stay_enquiry_details;
use Illuminate\Http\Request;

class StayEnquiryController extends Controller
{
     public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'required|string|email|max:200',
            'phone' => 'required|string|max:100',
            'comments' => 'nullable|string',
            'location' => 'required|string',
            'stay_title' => 'required|string',
            'birth_date' => 'required|string',
            'engagement_date' => 'required|string',
            'no_of_days' => 'required|string',
            'total_count' => 'required|integer',
            'male_count' => 'required|integer',
            'female_count' => 'required|integer',
            'child_count' => 'required|integer',
            'checkin_date' => 'required|string',
            'checkout_count' => 'required|integer',
            'cab' => 'nullable|string',
            'price' => 'required|integer',
        ]);

        // Create a new record
        $record = stay_enquiry_details::create($validatedData);

        // Return response
        return response()->json([
            'message' => 'Record created successfully',
            'data' => $record
        ], 201);
    }
}
