<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function addReview(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'package_id' => 'required|exists:inclusive_packages,id',
        'comment' => 'required|string',
        'rating' => 'required|integer|min:1|max:5',
    ]);

    $review = Review::create($request->all());

    return response()->json([
        'message' => 'Review added successfully!',
        'review' => $review,
    ], 201);
}

}
