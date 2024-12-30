<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ClientReview;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use App\Models\Amenities;
use App\Models\FoodBeverage;
use App\Models\Activities;
use App\Models\InclusivePackages;
use App\Models\Safetyfeatures;
use App\Models\EnquiryDetail;
use App\Models\Program_wishlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ProgramApiController extends Controller
{

    public function get_program_details(Request $request)
    {
        try {
            // Validate the request to ensure an ID is provided
            $request->validate([
                'program_id' => 'required',
            ]);

            // Retrieve the ID from the request
            $id = $request->input('program_id');
            $user_id = $request->input('user_id');

            // Fetch the program details using the provided ID
            // $package = InclusivePackages::find($id);

            $package = InclusivePackages::with('destination', 'theme', 'clientReviews')->find($id);

            if (!$package) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Program not found.',
                    'data' => null
                ], 404);
            }

            // $amenityIds = json_decode($package->amenity_details, true);
            // $foodBeverageIds = json_decode($package->food_beverages, true);
            // $activityIds = json_decode($package->activities, true);
            // $safetyFeatureIds = json_decode($package->safety_features, true);
            $amenityIds = json_decode($package->amenity_details, true) ?? [];
            $foodBeverageIds = json_decode($package->food_beverages, true) ?? [];
            $activityIds = json_decode($package->activities, true) ?? [];
            $safetyFeatureIds = json_decode($package->safety_features, true) ?? [];
            $eventsPackageImages = json_decode($package->events_package_images, true) ?? [];
            $tourPlanning = json_decode($package->tour_planning, true) ?? [];
            $campRule = json_decode($package->camp_rule, true) ?? [];

            // Fetch details using the IDs
            // $amenities = Amenities::whereIn('id', $amenityIds)->pluck('amenity_name', 'id','amenity_pic');
            $amenities = Amenities::whereIn('id', $amenityIds)
                ->get(['id', 'amenity_name', 'amenity_pic'])
                ->keyBy('id')
                ->map(function ($item) {
                    return [
                        'amenity_name' => $item->amenity_name,
                        'amenity_pic' => $item->amenity_pic,
                    ];
                });
            $foodBeverages = FoodBeverage::whereIn('id', $foodBeverageIds)
                ->get(['id', 'food_beverage', 'food_beverage_pic'])
                ->keyBy('id')
                ->map(function ($item) {
                    return [
                        'food_beverage' => $item->food_beverage,
                        'food_beverage_pic'  => $item->food_beverage_pic,
                    ];
                });
            $activities = Activities::whereIn('id', $activityIds)
                ->get(['id', 'activities', 'activities_pic'])
                ->keyBy('id')
                ->map(function ($item) {
                    return [
                        'activities' => $item->activities,
                        'activities_pic' => $item->activities_pic,
                    ];
                });
            $safetyFeatures = Safetyfeatures::whereIn('id', $safetyFeatureIds)
                ->get(['id', 'safety_features', 'safety_features_pic'])
                ->keyBy('id')
                ->map(function ($item) {
                    return [
                        'safety_features' => $item->safety_features,
                        'safety_features_pic' => $item->safety_features_pic,
                    ];
                });

            // Format the start date
            $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');
            $formattedendDate = \Carbon\Carbon::parse($package->return_date)->format('M d, Y');
            $category = json_decode($package->category, true) ?? [];

            $clientReviews = $package->clientReviews->map(function ($review) {
                $reviewDate =  $reviewDate = Carbon::parse($review->review_dt);
                return [
                    'client_name' => $review->client_name,
                    'client_pic' => $review->client_pic,
                    'client_review' => $review->client_review,
                    'review_dt' => $reviewDate->format('d M Y'),
                    'rating' => $review->rating,
                    // 'created_at' => $review->client_review->format('M d, Y'),
                ];
            });

            $totalReviews = $package->clientReviews->count();
            $averageRating = $package->clientReviews->avg('rating');
            $importantInfoPlainText = strip_tags(html_entity_decode($package->important_info, ENT_QUOTES, 'UTF-8'));
            $importantInfoPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $importantInfoPlainText);

            $program_inclusionPlainText = strip_tags(html_entity_decode($package->program_inclusion, ENT_QUOTES, 'UTF-8'));
            $program_inclusionPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $program_inclusionPlainText);

            $break_fastPlainText = strip_tags(html_entity_decode($package->break_fast, ENT_QUOTES, 'UTF-8'));
            $break_fastPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $break_fastPlainText);


            $responseData = [
                'id' => $package->id,
                'title' => $package->title,
                'program_desc' => $package->program_description,
                'flag' => $category,
                'destination' => $package->destination->city_name,
                'theme' => $package->theme->themes_name,
                'state' => $package->state,
                'city' => $package->city,
                'address' => $package->address,
                'country' => $package->country,
                'tour_planning' => $tourPlanning,
                'cover_img' => $package->cover_img,
                'gallery_img' => $eventsPackageImages,
                'start_date' => $formattedStartDate,
                'end_date' => $formattedendDate,
                'total_days' => $package->total_days,
                'member_capacity' => $package->member_capacity,
                'member_type' => $package->member_type,
                'actual_price' => $package->price,
                'discount_price' => $package->actual_price,
                'payment_policy' => $campRule,
                'important_info' => $importantInfoPlainText,
                'program_inclusion' => $program_inclusionPlainText,
                'break_fast' => $break_fastPlainText,
                'lunch' => $package->lunch,
                'dinner' => $package->dinner,
                'amenity_details' => $amenities,
                'foodBeverages' => $foodBeverages,
                'activities' => $activities,
                'safety_features' => $safetyFeatures,
                'client_reviews' => $clientReviews,
                'total_reviews' => $totalReviews,
                'google_map' => $package->google_map,
                'average_rating' => number_format($averageRating, 1),
                'created_date' => $package->created_date,
            ];
            if ($user_id) {
                $wishlist = Program_wishlist::where('user_id', $user_id)
                    ->where('program_id', $id)
                    ->exists();

                $responseData['wishlists'] = $wishlist;
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Program details retrieved successfully.',
                'data' => $responseData
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation error response
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error fetching Program details: ' . $e->getMessage());

            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching program details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function filter_program_by_price_sort(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'sort_order' => 'required|string|in:low,high', // Allow 'low' or 'high' for sorting
                'theme' => 'nullable|string', // Optional theme
                'user_id' => 'nullable|integer', // Optional user_id
            ]);
    
            // Retrieve the sort order, theme, and user_id (if provided)
            $sortOrder = $request->input('sort_order');
            $theme = $request->input('theme');
            $userId = $request->input('user_id');
    
            // Determine the sort direction based on the 'sort_order' value
            $sortDirection = ($sortOrder === 'low') ? 'asc' : 'desc';
    
            $query = InclusivePackages::query()
            ->where('status', '1') // Filter programs where status = 1
            ->where('is_deleted', '0') // Filter programs where is_deleted = 0
            ->orderByRaw("CAST(REPLACE(REPLACE(REPLACE(actual_price, '₹', ''), '$', ''), ',', '') AS SIGNED) $sortDirection") // Sort based on actual_price after removing ₹, $ symbols, and commas
            ->with('destination', 'theme', 'clientReviews');
        
    
            // Filter by theme if provided
            if (!empty($theme)) {
                $query->whereHas('theme', function ($query) use ($theme) {
                    $query->where('themes_name', $theme);
                });
            }
    
            // Execute the query
            $programs = $query->get();
    
            // Check if any programs were found
            if ($programs->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No programs found.',
                    'data' => null
                ], 404);
            }
    
            // Process the programs for the response
            $responseData = $programs->map(function ($package) use ($userId) {
                // Extract and process the necessary fields
                $clientReviews = $package->clientReviews->map(function ($review) {
                    $reviewDate = \Carbon\Carbon::parse($review->review_dt);
                    return [
                        'client_name' => $review->client_name,
                        'client_pic' => $review->client_pic,
                        'client_review' => $review->client_review,
                        'review_dt' => $reviewDate->format('d M Y'),
                        'rating' => $review->rating,
                    ];
                });
    
                $totalReviews = $package->clientReviews->count();
                $averageRating = $package->clientReviews->avg('rating');
    
                // Clean up the text fields
                $importantInfoPlainText = strip_tags(html_entity_decode($package->important_info, ENT_QUOTES, 'UTF-8'));
                $importantInfoPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $importantInfoPlainText);
    
                $programInclusionPlainText = strip_tags(html_entity_decode($package->program_inclusion, ENT_QUOTES, 'UTF-8'));
                $programInclusionPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $programInclusionPlainText);
    
                $breakFastPlainText = strip_tags(html_entity_decode($package->break_fast, ENT_QUOTES, 'UTF-8'));
                $breakFastPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $breakFastPlainText);
    
                $data = [
                    'id' => $package->id,
                    'title' => $package->title,
                    'program_desc' => $package->program_description,
                    'destination' => $package->destination->city_name,
                    'theme' => $package->theme->themes_name,
                    'actual_price' => $package->price,
                    'discount_price' => $package->actual_price,
                    'client_reviews' => $clientReviews,
                    'total_reviews' => $totalReviews,
                    'average_rating' => number_format($averageRating, 1),
                    'important_info' => $importantInfoPlainText,
                    'program_inclusion' => $programInclusionPlainText,
                    'break_fast' => $breakFastPlainText,
                ];
    
                // If userId is provided, check if the program is in their wishlist
                if ($userId) {
                    $wishlist = Program_wishlist::where('user_id', $userId)
                        ->where('program_id', $package->id)
                        ->exists();
    
                    $data['wishlists'] = $wishlist;
                }
    
                return $data;
            });
    
            return response()->json([
                'status' => 'success',
                'message' => 'Programs filtered and sorted by actual price retrieved successfully.',
                'data' => $responseData
            ], 200);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation error response
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error filtering programs by actual price: ' . $e->getMessage());
    
            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while filtering programs by actual price.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    

    public function filter_program_by_price(Request $request)
    {
        try {
            // Validate the request to ensure min_price and max_price are provided
            $request->validate([
                'min_price' => 'required|numeric|min:0',
                'max_price' => 'required|numeric|min:0',
                'user_id' => 'nullable|integer',
            ]);

            // Retrieve min_price, max_price, and user_id from the request
            $minPrice = $request->input('min_price');
            $maxPrice = $request->input('max_price');
            $userId = $request->input('user_id');

            // Fetch programs where the actual_price is between min_price and max_price
            $programs = InclusivePackages::whereBetween('price', [$minPrice, $maxPrice])
                ->with('destination', 'theme', 'clientReviews')
                ->get();

            // Check if any programs were found
            if ($programs->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No programs found within the specified price range.',
                    'data' => null
                ], 404);
            }

            // Process each program and prepare the response
            $responseData = $programs->map(function ($package) use ($userId) {
                $amenityIds = json_decode($package->amenity_details, true) ?? [];
                $foodBeverageIds = json_decode($package->food_beverages, true) ?? [];
                $activityIds = json_decode($package->activities, true) ?? [];
                $safetyFeatureIds = json_decode($package->safety_features, true) ?? [];
                $eventsPackageImages = json_decode($package->events_package_images, true) ?? [];
                $tourPlanning = json_decode($package->tour_planning, true) ?? [];
                $campRule = json_decode($package->camp_rule, true) ?? [];

                // Get related details
                $amenities = Amenities::whereIn('id', $amenityIds)
                    ->get(['id', 'amenity_name', 'amenity_pic'])
                    ->keyBy('id')
                    ->map(function ($item) {
                        return [
                            'amenity_name' => $item->amenity_name,
                            'amenity_pic' => $item->amenity_pic,
                        ];
                    });

                $foodBeverages = FoodBeverage::whereIn('id', $foodBeverageIds)
                    ->get(['id', 'food_beverage', 'food_beverage_pic'])
                    ->keyBy('id')
                    ->map(function ($item) {
                        return [
                            'food_beverage' => $item->food_beverage,
                            'food_beverage_pic' => $item->food_beverage_pic,
                        ];
                    });

                $activities = Activities::whereIn('id', $activityIds)
                    ->get(['id', 'activities', 'activities_pic'])
                    ->keyBy('id')
                    ->map(function ($item) {
                        return [
                            'activities' => $item->activities,
                            'activities_pic' => $item->activities_pic,
                        ];
                    });

                $safetyFeatures = Safetyfeatures::whereIn('id', $safetyFeatureIds)
                    ->get(['id', 'safety_features', 'safety_features_pic'])
                    ->keyBy('id')
                    ->map(function ($item) {
                        return [
                            'safety_features' => $item->safety_features,
                            'safety_features_pic' => $item->safety_features_pic,
                        ];
                    });

                // Format the dates
                $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');
                $formattedEndDate = \Carbon\Carbon::parse($package->return_date)->format('M d, Y');
                $category = json_decode($package->category, true) ?? [];

                // Process client reviews
                $clientReviews = $package->clientReviews->map(function ($review) {
                    $reviewDate = \Carbon\Carbon::parse($review->review_dt);
                    return [
                        'client_name' => $review->client_name,
                        'client_pic' => $review->client_pic,
                        'client_review' => $review->client_review,
                        'review_dt' => $reviewDate->format('d M Y'),
                        'rating' => $review->rating,
                    ];
                });

                $totalReviews = $package->clientReviews->count();
                $averageRating = $package->clientReviews->avg('rating');

                // Convert HTML to plain text
                $importantInfoPlainText = strip_tags(html_entity_decode($package->important_info, ENT_QUOTES, 'UTF-8'));
                $importantInfoPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $importantInfoPlainText);

                $programInclusionPlainText = strip_tags(html_entity_decode($package->program_inclusion, ENT_QUOTES, 'UTF-8'));
                $programInclusionPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $programInclusionPlainText);

                $breakFastPlainText = strip_tags(html_entity_decode($package->break_fast, ENT_QUOTES, 'UTF-8'));
                $breakFastPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $breakFastPlainText);

                // Prepare the response data
                $data = [
                    'id' => $package->id,
                    'title' => $package->title,
                    'program_desc' => $package->program_description,
                    'flag' => $category,
                    'destination' => $package->destination->city_name,
                    'theme' => $package->theme->themes_name,
                    'state' => $package->state,
                    'city' => $package->city,
                    'address' => $package->address,
                    'country' => $package->country,
                    'tour_planning' => $tourPlanning,
                    'cover_img' => $package->cover_img,
                    'gallery_img' => $eventsPackageImages,
                    'start_date' => $formattedStartDate,
                    'end_date' => $formattedEndDate,
                    'total_days' => $package->total_days,
                    'member_capacity' => $package->member_capacity,
                    'member_type' => $package->member_type,
                    'actual_price' => $package->price,
                    'discount_price' => $package->actual_price,
                    'payment_policy' => $campRule,
                    'important_info' => $importantInfoPlainText,
                    'program_inclusion' => $programInclusionPlainText,
                    'break_fast' => $breakFastPlainText,
                    'lunch' => $package->lunch,
                    'dinner' => $package->dinner,
                    'amenity_details' => $amenities,
                    'foodBeverages' => $foodBeverages,
                    'activities' => $activities,
                    'safety_features' => $safetyFeatures,
                    'client_reviews' => $clientReviews,
                    'total_reviews' => $totalReviews,
                    'average_rating' => number_format($averageRating, 1),
                    'created_date' => $package->created_date,
                ];

                // Check if the user ID is provided and add wishlist information
                if ($userId) {
                    $wishlist = Program_wishlist::where('user_id', $userId)
                        ->where('program_id', $package->id)
                        ->exists();

                    $data['wishlists'] = $wishlist;
                }

                return $data;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Programs filtered by price range retrieved successfully.',
                'data' => $responseData
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation error response
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error filtering programs by price range: ' . $e->getMessage());

            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while filtering programs by price range.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function filter_program_by_date_and_price(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'actual_price' => 'required|numeric|min:0',
                'user_id' => 'nullable|integer',
            ]);

            $startDate = $request->input('start_date');
            $actualPrice = $request->input('actual_price');
            $userId = $request->input('user_id');

            $programs = InclusivePackages::whereDate('start_date', $startDate)
                ->where('price', $actualPrice)
                ->with('destination', 'theme', 'clientReviews')
                ->get();

            if ($programs->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No programs found with the specified date and price.',
                    'data' => null
                ], 404);
            }

            $responseData = $programs->map(function ($package) use ($userId) {
                $amenityIds = json_decode($package->amenity_details, true) ?? [];
                $foodBeverageIds = json_decode($package->food_beverages, true) ?? [];
                $activityIds = json_decode($package->activities, true) ?? [];
                $safetyFeatureIds = json_decode($package->safety_features, true) ?? [];
                $eventsPackageImages = json_decode($package->events_package_images, true) ?? [];
                $tourPlanning = json_decode($package->tour_planning, true) ?? [];
                $campRule = json_decode($package->camp_rule, true) ?? [];

                $amenities = Amenities::whereIn('id', $amenityIds)
                    ->get(['id', 'amenity_name', 'amenity_pic'])
                    ->keyBy('id')
                    ->map(function ($item) {
                        return [
                            'amenity_name' => $item->amenity_name,
                            'amenity_pic' => $item->amenity_pic,
                        ];
                    });

                $foodBeverages = FoodBeverage::whereIn('id', $foodBeverageIds)
                    ->get(['id', 'food_beverage', 'food_beverage_pic'])
                    ->keyBy('id')
                    ->map(function ($item) {
                        return [
                            'food_beverage' => $item->food_beverage,
                            'food_beverage_pic' => $item->food_beverage_pic,
                        ];
                    });

                $activities = Activities::whereIn('id', $activityIds)
                    ->get(['id', 'activities', 'activities_pic'])
                    ->keyBy('id')
                    ->map(function ($item) {
                        return [
                            'activities' => $item->activities,
                            'activities_pic' => $item->activities_pic,
                        ];
                    });

                $safetyFeatures = Safetyfeatures::whereIn('id', $safetyFeatureIds)
                    ->get(['id', 'safety_features', 'safety_features_pic'])
                    ->keyBy('id')
                    ->map(function ($item) {
                        return [
                            'safety_features' => $item->safety_features,
                            'safety_features_pic' => $item->safety_features_pic,
                        ];
                    });

                $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');
                $formattedEndDate = \Carbon\Carbon::parse($package->return_date)->format('M d, Y');
                $category = json_decode($package->category, true) ?? [];

                $clientReviews = $package->clientReviews->map(function ($review) {
                    $reviewDate = \Carbon\Carbon::parse($review->review_dt);
                    return [
                        'client_name' => $review->client_name,
                        'client_pic' => $review->client_pic,
                        'client_review' => $review->client_review,
                        'review_dt' => $reviewDate->format('d M Y'),
                        'rating' => $review->rating,
                    ];
                });

                $totalReviews = $package->clientReviews->count();
                $averageRating = $package->clientReviews->avg('rating');

                $importantInfoPlainText = strip_tags(html_entity_decode($package->important_info, ENT_QUOTES, 'UTF-8'));
                $importantInfoPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $importantInfoPlainText);

                $programInclusionPlainText = strip_tags(html_entity_decode($package->program_inclusion, ENT_QUOTES, 'UTF-8'));
                $programInclusionPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $programInclusionPlainText);

                $breakFastPlainText = strip_tags(html_entity_decode($package->break_fast, ENT_QUOTES, 'UTF-8'));
                $breakFastPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $breakFastPlainText);

                $data = [
                    'id' => $package->id,
                    'title' => $package->title,
                    'program_desc' => $package->program_description,
                    'flag' => $category,
                    'destination' => $package->destination->city_name,
                    'theme' => $package->theme->themes_name,
                    'state' => $package->state,
                    'city' => $package->city,
                    'address' => $package->address,
                    'country' => $package->country,
                    'tour_planning' => $tourPlanning,
                    'cover_img' => $package->cover_img,
                    'gallery_img' => $eventsPackageImages,
                    'start_date' => $formattedStartDate,
                    'end_date' => $formattedEndDate,
                    'total_days' => $package->total_days,
                    'member_capacity' => $package->member_capacity,
                    'member_type' => $package->member_type,
                    'actual_price' => $package->price,
                    'discount_price' => $package->actual_price,
                    'payment_policy' => $campRule,
                    'important_info' => $importantInfoPlainText,
                    'program_inclusion' => $programInclusionPlainText,
                    'break_fast' => $breakFastPlainText,
                    'lunch' => $package->lunch,
                    'dinner' => $package->dinner,
                    'amenity_details' => $amenities,
                    'foodBeverages' => $foodBeverages,
                    'activities' => $activities,
                    'safety_features' => $safetyFeatures,
                    'client_reviews' => $clientReviews,
                    'total_reviews' => $totalReviews,
                    'average_rating' => number_format($averageRating, 1),
                    'created_date' => $package->created_date,
                ];

                if ($userId) {
                    $wishlist = Program_wishlist::where('user_id', $userId)
                        ->where('program_id', $package->id)
                        ->exists();

                    $data['wishlists'] = $wishlist;
                }

                return $data;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Programs filtered by date and price retrieved successfully.',
                'data' => $responseData
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error filtering programs by date and price: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while filtering programs by date and price.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // public function sort_program(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'sort_by' => 'nullable|in:recent,price_low_to_high,price_high_to_low',
    //         ]);

    //         $sortBy = $request->input('sort_by');

    //         $query = InclusivePackages::query();

    //         switch ($sortBy) {
    //             case 'recent':
    //                 $query->orderBy('id', 'desc');
    //                 break;

    //             case 'price_low_to_high':
    //                 $query->orderBy('actual_price', 'asc');
    //                 break;

    //             case 'price_high_to_low':
    //                 $query->orderBy('actual_price', 'desc');
    //                 break;

    //             default:
    //                 $query->orderBy('id', 'desc');
    //                 break;
    //         }

    //         // Fetch the results
    //         $programs = $query->get();

    //         // Format the data if needed (e.g., formatting dates, etc.)
    //         $programsData = $programs->map(function ($program) {
    //             return [
    //                 'id' => $program->id,
    //                 'title' => $program->title,
    //                 'category' => $program->category,
    //                 'location' => $program->location,
    //                 'total_days' => $program->total_days,
    //                 'member_capacity' => $program->member_capacity,
    //                 'price' => $program->price,
    //                 'actual_price' => $program->actual_price,
    //                 'cover_img' => $program->cover_img,
    //                 'start_date' => \Carbon\Carbon::parse($program->start_date)->format('M d, Y'),
    //                 'theme_id' => $program->theme_id,
    //                 'theme' => $program->theme,
    //                 'destination_id' => $program->destination_id,
    //                 'destination' => $program->destination,
    //                 'average_rating' => $program->average_rating,
    //                 'totalReviews' => $program->totalReviews,
    //             ];
    //         });

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Programs retrieved successfully.',
    //             'data' => $programsData
    //         ], 200);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         // Return validation error response
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Validation error.',
    //             'errors' => $e->errors()
    //         ], 422);
    //     } catch (\Exception $e) {
    //         // Log the exception
    //         \Log::error('Error fetching sorted programs: ' . $e->getMessage());

    //         // Return error response
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An error occurred while fetching programs.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }



    public function enquiry_form_insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255', // Validate email format
            'phone' => [
                'required',
                'regex:/^\+?[0-9]{10,15}$/',
            ],
            'comments' => 'required|string',
            'location' => 'required|string',
            'days' => 'required',
            'travel_destination' => 'required|string',
            'budget_per_head' => 'required|string',
            'cab_need' => 'required|string',
            'total_count' => 'required',
            'male_count' => 'required',
            'female_count' => 'required',
            'travel_date' => 'required',
            'rooms_count' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $enquiry = EnquiryDetail::create($request->all());

        return response()->json([
            'message' => 'Enquiry submitted successfully',
            'data' => $enquiry
        ], 201);
    }
    // public function store_wishlist(Request $request)
    // {

    //     // Validate the request
    //     $validator = Validator::make($request->all(), [
    //         'user_id' => 'required|exists:users,id',
    //         'program_id' => 'required|exists:inclusive_package_details,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Validation error.',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     // Check if the entry already exists
    //     $existingWishlist = Program_wishlist::where('user_id', $request->input('user_id'))
    //                                 ->where('program_id', $request->input('program_id'))
    //                                 ->first();

    //     if ($existingWishlist) {
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Program already in wishlist.',
    //             'data' => $existingWishlist
    //         ], 200);
    //     }

    //     // Create a new wishlist entry
    //     $wishlist = Program_wishlist::create([
    //         'user_id' => $request->input('user_id'),
    //         'program_id' => $request->input('program_id')
    //     ]);

    //     // Return a success response
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Program added to wishlist successfully.',
    //         'data' => $wishlist
    //     ], 201);
    // }

    public function manage_wishlist(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'program_id' => 'required|exists:inclusive_package_details,id',
            'action' => 'required|in:add,remove' // Action parameter to determine add or remove
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the authenticated user
        $user = $request->user(); // This assumes you are using Laravel Sanctum or Passport

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Please login to continue.',
                'data' => null
            ], 401);
        }

        $userId = $user->id; // Get the authenticated user's ID
        $programId = $request->input('program_id');
        $action = $request->input('action');

        if ($action === 'add') {
            // Check if the entry already exists
            $existingWishlist = Program_wishlist::where('user_id', $userId)
                ->where('program_id', $programId)
                ->first();

            if ($existingWishlist) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Program already in wishlist.',
                    'data' => $existingWishlist
                ], 200);
            }

            // Create a new wishlist entry
            $wishlist = Program_wishlist::create([
                'user_id' => $userId,
                'program_id' => $programId
            ]);

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Program added to wishlist successfully.',
                'data' => $wishlist
            ], 201);
        } elseif ($action === 'remove') {
            // Check if the entry exists
            $wishlist = Program_wishlist::where('user_id', $userId)
                ->where('program_id', $programId)
                ->first();

            if (!$wishlist) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Program not found in wishlist.',
                    'data' => null
                ], 404);
            }

            // Delete the wishlist entry
            $wishlist->delete();

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Program removed from wishlist successfully.',
                'data' => null
            ], 200);
        }
    }


    // Retrieve all enquiries
    // public function index()
    // {
    //     $enquiries = EnquiryDetail::all();

    //     return response()->json([
    //         'message' => 'Enquiries retrieved successfully',
    //         'data' => $enquiries
    //     ], 200);
    // }
    public function getAmenities()
    {
        $amenities = Amenities::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Amenities retrieved successfully.',
            'data' => $amenities
        ], 200);
    }

    public function getAmenitiesFoodBeverageActivitiesSafetyFeaturesById(Request $request)
    {
        
        // Validate the input
        $request->validate([
            'id' => 'required|integer',
        ]);
    
        $id = $request->input('id');
    
        // Fetch the package with relationships
        $package = InclusivePackages::with('destination', 'theme', 'clientReviews')->find($id);
    
        if (!$package) {
            return response()->json([
                'status' => 'error',
                'message' => 'Package not found.',
                'data' => null,
            ], 404);
        }
    
        // Decode the stored JSON fields
        $amenityIds = json_decode($package->amenity_details, true) ?? [];
        $foodBeverageIds = json_decode($package->food_beverages, true) ?? [];
        $activityIds = json_decode($package->activities, true) ?? [];
        $safetyFeatureIds = json_decode($package->safety_features, true) ?? [];
    
        // Fetch related records and format the response
        $amenities = Amenities::whereIn('id', $amenityIds)
            ->get(['id', 'amenity_name', 'amenity_pic'])
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'amenity_name' => $item->amenity_name,
                    'amenity_pic' => $item->amenity_pic,
                ];
            });
    
        $foodBeverages = FoodBeverage::whereIn('id', $foodBeverageIds)
            ->get(['id', 'food_beverage', 'food_beverage_pic'])
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'food_beverage' => $item->food_beverage,
                    'food_beverage_pic' => $item->food_beverage_pic,
                ];
            });
    
        $activities = Activities::whereIn('id', $activityIds)
            ->get(['id', 'activities', 'activities_pic'])
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'activities' => $item->activities,
                    'activities_pic' => $item->activities_pic,
                ];
            });
    
        $safetyFeatures = Safetyfeatures::whereIn('id', $safetyFeatureIds)
            ->get(['id', 'safety_features', 'safety_features_pic'])
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'safety_features' => $item->safety_features,
                    'safety_features_pic' => $item->safety_features_pic,
                ];
            });
    
        // Return the response
        return response()->json([
            'status' => 'success',
            'message' => 'Amenities, Food & Beverages, Activities, and Safety Features retrieved successfully.',
            'data' => [
                'amenities' => $amenities,
                'foodBeverages' => $foodBeverages,
                'activities' => $activities,
                'safetyFeatures' => $safetyFeatures,
            ],
        ], 200);
    }

}
