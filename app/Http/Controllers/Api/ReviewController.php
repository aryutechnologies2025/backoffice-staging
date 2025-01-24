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
            'package_id' => 'required|exists:inclusive_package_details,id',
            'comment' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);
    
        // Ensure at least one of comment or rating is present
        if (is_null($request->comment) && is_null($request->rating)) {
            return response()->json([
                'message' => 'You must provide at least a comment or a rating.',
            ], 422);
        }
    
        // Create the review record
        $review = Review::create($request->only(['user_id', 'package_id', 'comment', 'rating']))->orderBy('created_at', 'desc')->get();
    
        // Return response
        return response()->json([
            'message' => 'Review added successfully!',
            'review' => $review,
        ], 201);
    }
    
    
}