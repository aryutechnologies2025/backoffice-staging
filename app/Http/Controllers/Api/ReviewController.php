<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function addReview(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:inclusive_package_details,id',  // Make sure package_id exists in inclusive_package_details
            'comment' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        // Ensure at least one of comment or rating is present
        if (!$request->has('comment') && !$request->has('rating')) {
            return response()->json([
                'message' => 'Either comment or rating is required.',
            ], 422);
        }

        // Create the review record
        $review = Review::create($request->all());

        // Return response
        return response()->json([
            'message' => 'Review added successfully!',
            'review' => $review,
        ], 201);
    }
}