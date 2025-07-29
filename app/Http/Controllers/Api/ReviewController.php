<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\StagReview;
use Illuminate\Support\Facades\Log;

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
        // $review = Review::create($request->only(['user_id', 'package_id', 'comment', 'rating']))->orderBy('created_at', 'desc')->get();
    

        $client_review = new Review;
        $client_review->package_id = $request->input('package_id');
        $client_review->user_id = $request->input('user_id');
        $client_review->comment = $request->input('comment');
        $client_review->review_dt = $request->input('review_dt');
        $client_review->rating = $request->input('rating');
        $client_review->status = '1';
        $client_review->created_date = date('Y-m-d H:i:s');
        $client_review->created_by = $request->input('user_id');
        $client_review->is_deleted = '0';
        $client_review->updated_at = null;
        $client_review->save();

        // Return response
        return response()->json([
            'message' => 'Review added successfully!',
            'review' => $client_review,
        ], 201);
    }

    public function addStayReview(Request $request)
    {
        // Validate incoming request
        // $request->validate([
        //     'user_id' => 'required|exists:users,id',
        //     'stag_id' => 'required|exists:stays_destination_details,id',
        //     'comment' => 'nullable|string',
        //     'rating' => 'nullable|integer|min:1|max:5',
        // ]);
        // Log::info('Add Stay Review Request Data: ', $request->all());

        // // Ensure at least one of comment or rating is present
        // if (is_null($request->comment) && is_null($request->rating)) {
        //     return response()->json([
        //         'message' => 'You must provide at least a comment or a rating.',
        //     ], 422);
        // }
        
        $client_review = new StagReview();
        $client_review->stag_id = $request->input('stag_id');
        $client_review->user_id = $request->input('user_id');
        $client_review->review = $request->input('review');
        $client_review->rating = $request->input('rating');
        $client_review->created_by = $request->input('user_id');
        $client_review->is_deleted = '0';
        $client_review->save();

        // $client_review = new Review();
        // $client_review->package_id = $request->input('stag_id');
        // $client_review->user_id = $request->input('user_id');
        // $client_review->comment = $request->input('review');
        // $client_review->rating = $request->input('rating');
        // $client_review->created_by = $request->input('user_id');
        // $client_review->is_deleted = '0';
        // $client_review->save();

        // Return response
        return response()->json([
            'message' => 'Review added successfully!',
            'review' => $client_review,
        ], 201);
    }
    
    
}