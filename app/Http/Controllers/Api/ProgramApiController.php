<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\CustomerPackage;
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
use App\Models\Address;
use App\Models\Influencers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\AffiliateLinkClick;
use App\Models\HomeEnquiryDetail;
use App\Mail\enquiryEmail;
use App\Mail\adminEmail;
use App\Models\customer_package;
use App\Models\program_pdf;
use App\Models\stay_enquiry_details;
use App\Models\stays_whishlist;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProgramApiController extends Controller
{
    public function get_program_details(Request $request)
    {
        try {
            // Step 1: Validate the request (Ensure program_id is present)
            $request->validate([
                'program_id' => 'required',
            ]);

            // Step 2: Retrieve the program_id and reference_id from the request
            $programId = $request->input('program_id'); // e.g., 36
            $referenceId = $request->input('reference_id'); // e.g., INPC001
            $user_id = $request->input('user_id'); // Optional user_id

            // Step 3: Find the program by ID
            $program = InclusivePackages::find($programId);

            if (!$program) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Program not found',
                ], 404);
            }

            // Step 4: Optional - Track the visit with reference_id
            if ($referenceId) {
                // Log the visit into a database for tracking (Example)
                DB::table('program_visits')->insert([
                    'program_id' => $programId,
                    'reference_id' => $referenceId,
                    'visited_at' => now(),
                ]);
            }

            // Check if the program details are already cached
            $cacheKey = "program_details_{$programId}";
            $cachedData = Cache::get($cacheKey);

            if ($cachedData) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Program details retrieved successfully from cache.',
                    'data' => $cachedData
                ], 200);
            }

            // Fetch the program details using the provided ID
            $package = InclusivePackages::with('destination', 'theme', 'clientReviews','reviews')->find($programId);

            if (!$package) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Program not found.',
                    'data' => null
                ], 404);
            }

            $amenityIds = json_decode($package->amenity_details, true) ?? [];
            $foodBeverageIds = json_decode($package->food_beverages, true) ?? [];
            $activityIds = json_decode($package->activities, true) ?? [];
            $safetyFeatureIds = json_decode($package->safety_features, true) ?? [];
            $eventsPackageImages = json_decode($package->events_package_images, true) ?? [];
            $tourPlanning = json_decode($package->tour_planning, true) ?? [];
            $campRule = json_decode($package->camp_rule, true) ?? [];

            $price_title = json_decode($package->price_tilte, true) ?? [];
            $price_amount = json_decode($package->price_amount, true) ?? [];


            $amenities = Amenities::whereIn('id', $amenityIds)
                ->get(['id', 'amenity_name', 'amenity_pic'])
                ->keyBy('id')
                ->map(function ($item) {
                    return [
                        'amenity_name' => $item->amenity_name,
                        'amenity_pic' => $item->amenity_pic,
                    ];
                });

            // $pricing = Amenities::whereIn('id', $amenityIds)
            // ->get(['id', 'amenity_name', 'amenity_pic'])
            // ->keyBy('id')
            // ->map(function ($item) {
            //     return [
            //         'price_title' => $item->amenity_name,
            //         'price_amount' => $item->amenity_pic,
            //     ];
            // });


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

            $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');
            $formattedendDate = \Carbon\Carbon::parse($package->return_date)->format('M d, Y');
            $category = json_decode($package->category, true) ?? [];
            $formattedLocation = ucfirst($package->address) . ', ' . ucfirst($package->state);
            $clientReviews = $package->clientReviews->map(function ($review) {
                $reviewDate = Carbon::parse($review->review_dt);
                return [
                    'client_name' => $review->client_name,
                    'client_pic' => $review->client_pic,
                    'client_review' => $review->client_review,
                    'review_dt' => $reviewDate->format('d M Y'),
                    'rating' => $review->rating,
                ];
            });
            $reviews = $package->reviews->map(function ($review) {
                $user = $review->user;
                return [
                    'first_name' => $user->first_name ?? null,
                    'last_name' => $user->last_name ?? null,
                    'profile_image' => $user->profile_image ?? null,
                    'comment' => $review->comment,
                    'rating' => $review->rating,
                    'date' => $review->created_at->format('M d, Y'),
                ];
            });

            // dd($reviews);
            // $reviews = $package->reviews;
            $reviewCount = $package->reviews->count();
            $totalReviews = $package->clientReviews->count();
            $averageRating = $package->reviews->avg('rating');
            $importantInfoPlainText = strip_tags(html_entity_decode($package->important_info, ENT_QUOTES, 'UTF-8'));
            $importantInfoPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $importantInfoPlainText);

            $program_inclusionPlainText = strip_tags($package->program_inclusion);
            $program_inclusionPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $program_inclusionPlainText);

            $break_fastPlainText = strip_tags(html_entity_decode($package->break_fast, ENT_QUOTES, 'UTF-8'));
            $break_fastPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $break_fastPlainText);

            $importantInfoPlainText = $package->important_info;
            $program_inclusionPlainText = $package->program_inclusion;
            $program_exclusionPlainText = $package->program_exclusion;

            // $program_exclusionPlainText = strip_tags(html_entity_decode($package->program_exclusion, ENT_QUOTES, 'UTF-8'));
            // $program_exclusionPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $program_exclusionPlainText);


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
                'program_exclusion' => $program_exclusionPlainText,
                'break_fast' => $break_fastPlainText,
                'location' => $formattedLocation,
                'lunch' => $package->lunch,
                'dinner' => $package->dinner,
                'amenity_details' => $amenities,
                'foodBeverages' => $foodBeverages,
                'activities' => $activities,
                'safety_features' => $safetyFeatures,
                'client_reviews' => $clientReviews,
                'total_reviews' => $totalReviews,
                'reviews' => $reviews,
                'review_count' => $reviewCount,
                'google_map' => $package->google_map,
                'average_rating' => number_format($averageRating, 1),
                'created_date' => $package->created_date,
                'current_location' => $package->location,
                'price_title'=> $price_title,
                'price_amount' =>$price_amount
              
            ];
            
            if ($user_id) {
                $wishlist = Program_wishlist::where('user_id', $user_id)
                    ->where('program_id', $programId)
                    ->exists();

                $responseData['wishlists'] = $wishlist;
            }

            // Cache the response data for 60 minutes
            Cache::put($cacheKey, $responseData, 60);

            return response()->json([
                'status' => 'success',
                'message' => 'Program details retrieved successfully.',
                'data' => $responseData
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching program details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function generateAffiliateLink($programId, $affiliateId)
    {
        // Fetch program details
        $program = InclusivePackages::find($programId);

        if (!$program) {
            return response()->json([
                'status' => 'error',
                'message' => 'Program not found'
            ], 404);
        }

        // Generate slug from the program title
        $programSlug = Str::slug($program->title, '-');

        // Generate the affiliate link
        $affiliateLink = "https://innerpece.com/{$programId}/{$programSlug}?ref={$affiliateId}";

        return response()->json([
            'status' => 'success',
            'message' => 'Affiliate link generated successfully',
            'data' => [
                'affiliate_link' => $affiliateLink
            ]
        ]);
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
                // Extract the first image URL
                $formattedLocation = ucfirst($package->address) . ', ' . ucfirst($package->state);
                // Helper function to get amenities, food & beverage, activities, and safety features
                $getDetailsById = function ($package) {
                    $id = $package->id;

                    // Call your original method logic here (or modify it to return the required data)
                    $response = (new ProgramApiController)->getAmenitiesFoodBeverageActivitiesSafetyFeaturesById(new Request(['id' => $id]));
                    return json_decode($response->getContent(), true)['data'];
                };

                // Fetch amenities, food & beverage, activities, safety features
                $details = $getDetailsById($package);
                $data = [
                    'id' => $package->id,
                    'title' => $package->title,
                    'program_desc' => $package->program_description,
                    'destination' => $package->destination->city_name,
                    'theme' => $package->theme->themes_name,
                    'actual_price' => $package->actual_price,
                    'price' => $package->price,
                    'client_reviews' => $clientReviews,
                    'total_reviews' => $totalReviews,
                    'average_rating' => number_format($averageRating, 1),
                    'important_info' => $importantInfoPlainText,
                    'program_inclusion' => $programInclusionPlainText,
                    'break_fast' => $breakFastPlainText,
                    'cover_img' => $package->cover_img,
                    'created_date' => $package->created_date,
                    'state' => $package->state,
                    'city' => $package->city,
                    'address' => $package->address,
                    'country' => $package->country,
                    'total_rooms' => $package->total_rooms,
                    'bed_room' => $package->bed_room,
                    'bath_room' => $package->bath_room,
                    'amerities' => $package->amerities,
                    'food_beverages' => $package->food_beverages,
                    'location' => $formattedLocation,
                    // Adding the fetched details
                    'amenities' => $details['amenities'] ?? [],
                    'foodBeverages' => $details['foodBeverages'] ?? [],
                    'activities' => $details['activities'] ?? [],
                    'safetyFeatures' => $details['safetyFeatures'] ?? [],
                    'current_location' => $package->location

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


            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while filtering programs by actual price.',
                'error' => $e->getMessage()
            ], 500);
        }
    }





    //get by destination with price 

    public function destination_program_by_price_sort(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'sort_order' => 'required|string|in:low,high', // Allow 'low' or 'high' for sorting
                'theme' => 'nullable|string', // Optional theme
                'user_id' => 'nullable|integer', // Optional user_id
                'city' => 'nullable|string', // Optional city for destination filter
                'state' => 'nullable|string', // Optional state for destination filter
                'country' => 'nullable|string', // Optional country for destination filter
            ]);

            // Retrieve the sort order, theme, user_id, and destination filter params (if provided)
            $sortOrder = $request->input('sort_order');
            $theme = $request->input('theme');
            $userId = $request->input('user_id');
            $city = $request->input('city');
            $state = $request->input('state');
            $country = $request->input('country');

            // Determine the sort direction based on the 'sort_order' value
            $sortDirection = ($sortOrder === 'low') ? 'asc' : 'desc';

            // Start building the query
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

            // Filter by destination if provided (city, state, or country)
            if ($city) {
                $query->whereHas('destination', function ($query) use ($city) {
                    $query->where('city_name', 'LIKE', "%$city%");
                });
            }

            if ($state) {
                $query->whereHas('destination', function ($query) use ($state) {
                    $query->where('state_name', 'LIKE', "%$state%");
                });
            }

            if ($country) {
                $query->whereHas('destination', function ($query) use ($country) {
                    $query->where('country_name', 'LIKE', "%$country%");
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

                // Extract the first image URL
                $formattedLocation = ucfirst($package->address) . ', ' . ucfirst($package->state);

                // Helper function to get amenities, food & beverage, activities, and safety features
                $getDetailsById = function ($package) {
                    $id = $package->id;

                    // Call your original method logic here (or modify it to return the required data)
                    $response = (new ProgramApiController)->getAmenitiesFoodBeverageActivitiesSafetyFeaturesById(new Request(['id' => $id]));
                    return json_decode($response->getContent(), true)['data'];
                };

                // Fetch amenities, food & beverage, activities, safety features
                $details = $getDetailsById($package);
                $data = [
                    'id' => $package->id,
                    'title' => $package->title,
                    'program_desc' => $package->program_description,
                    'destination' => $package->destination->city_name,
                    'theme' => $package->theme->themes_name,
                    'actual_price' => $package->actual_price,
                    'price' => $package->price,
                    'client_reviews' => $clientReviews,
                    'total_reviews' => $totalReviews,
                    'average_rating' => number_format($averageRating, 1),
                    'important_info' => $importantInfoPlainText,
                    'program_inclusion' => $programInclusionPlainText,
                    'break_fast' => $breakFastPlainText,
                    'cover_img' => $package->cover_img,
                    'created_date' => $package->created_date,
                    'state' => $package->state,
                    'city' => $package->city,
                    'address' => $package->address,
                    'country' => $package->country,
                    'total_rooms' => $package->total_rooms,
                    'bed_room' => $package->bed_room,
                    'bath_room' => $package->bath_room,
                    'amerities' => $package->amerities,
                    'food_beverages' => $package->food_beverages,
                    'location' => $formattedLocation,
                    // Adding the fetched details
                    'amenities' => $details['amenities'] ?? [],
                    'foodBeverages' => $details['foodBeverages'] ?? [],
                    'activities' => $details['activities'] ?? [],
                    'safetyFeatures' => $details['safetyFeatures'] ?? [],

                    'current_location' => $package->location
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
            'email' => 'required|email|max:255',
            'phone' => ['required', 'regex:/^\+?[0-9]{10,15}$/'],
            'comments' => 'required|string',
            'location' => 'required|string',
            
            'days' => 'required|integer',
            'travel_destination' => 'string|nullable',
            // 'budget_per_head' => 'required|string',
            'cab_need' => 'required|string',
            'total_count' => 'required|integer',
            'male_count' => 'required|integer',
            'female_count' => 'required|integer',
            'travel_date' => 'required|date',
            'rooms_count' => 'required|integer',
            'child_count' => 'required|integer|min:0',
            'child_age' => 'required_if:child_count,<,1|array|min:' . ($request->input('child_count') > 0 ? $request->input('child_count') : 0),

            // 'child_age.*' => 'integer|min:0', // Validate each age
            // 'child_age' => 'required|min:' . $request->child_count, // Validate as array
            'child_age.*' => 'min:0', // Validate each age
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
       
        

        $enquiryData = $request->all();
        $enquiryData['child_age'] = json_encode($request->input('child_age')); // Convert child_age to JSON

        $enquiry = EnquiryDetail::create($enquiryData);
 // Find matching program PDF
//  $programPdf = program_pdf::where('is_deleted', '0')->where('program_name', $enquiry->program_title)->first();
 $programPdf = InclusivePackages::where('is_deleted', '0')->where('title', $enquiry->program_title)->first();

     
        // Send email notifications
        try {
            // Send email to the client
            Mail::to($enquiry->email)->send(new enquiryEmail([
                'name' => $enquiry->name,
                'email' => $enquiry->email,
                'phone' => $enquiry->phone,
                'travel_destination' => $enquiry->travel_destination,
                'comments' => $enquiry->comments,
                'program_pdf' => $programPdf->program_pdf ?? null
            ]));

            // Send email to admin
            Mail::to('contact@innerpece.com')->send(new adminEmail([
                'name' => $enquiry->name,
                'email' => $enquiry->email,
                'phone' => $enquiry->phone,
                'comments' => $enquiry->comments,
                'location' => $enquiry->location,
                'days' => $enquiry->days,
                'travel_destination' => $enquiry->travel_destination,
                'cab_need' => $enquiry->cab_need,
                'total_count' => $enquiry->total_count,
                'child_count' => $enquiry->child_count,
            ]));
        } catch (\Exception $e) {
            // Log any email sending errors
            Log::error('Mail failed: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Enquiry submitted successfully. Emails sent if applicable.',
            'data' => $enquiry
        ], 201);
    }


    //getting the enquiry details by user email to match the enquiry details email
    public function getEnquiryDetailsByEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $email = $request->input('email');

        $enquiryDetails = EnquiryDetail::where('email', $email)->get();

        $stayDetails = stay_enquiry_details::where('email', $email)->get();

        $data =  $enquiryDetails->merge($stayDetails)->sortByDesc('created_at')->values();
    
        if ($enquiryDetails->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No enquiry details found for the provided email.',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Enquiry details retrieved successfully.',
            'data' => $data

        ], 200);
    }
   

    // public function getClientNotification(Request $request, $id)
    // {
    //     $projectDetails = EnquiryDetail::find($id);
    //     if ($projectDetails) {
    //         $clientEmail = $projectDetails->email;

    //         if (filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
    //             try {
    //                 // Send email to the client
    //                 Mail::to($clientEmail)->send(new enquiryEmail([
    //                     'name' => $projectDetails->name,
    //                     'email' => $projectDetails->email,
    //                     'phone' => $projectDetails->phone,
    //                     'comments' => $projectDetails->comments,
    //                 ]));

    //                 // Send email to admin
    //                 Mail::to('barathkrishnamoorthy17@gmail.com')->send(new adminEmail([
    //                     'name' => $projectDetails->name,
    //                     'email' => $projectDetails->email,
    //                     'phone' => $projectDetails->phone,
    //                     'comments' => $projectDetails->comments,
    //                 ]));

    //                 return response()->json(['success' => true, 'message' => 'Emails sent successfully.']);
    //             } catch (\Exception $e) {
    //                 // Log the error details
    //                 Log::error('Mail failed: ' . $e->getMessage());

    //                 return response()->json(['success' => false, 'message' => 'Failed to send email.']);
    //             }
    //         } else {
    //             Log::error('Invalid or empty client email: ' . $clientEmail);
    //             return response()->json(['success' => false, 'message' => 'Client email is empty or invalid.']);
    //         }
    //     } else {
    //         Log::error('Project not found with ID: ' . $id);
    //         return response()->json(['success' => false, 'message' => 'Project not found.']);
    //     }
    // }



    // public function enquiry_form_insert(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|max:255', // Validate email format
    //         'phone' => [
    //             'required',
    //             'regex:/^\+?[0-9]{10,15}$/',
    //         ],
    //         'comments' => 'required|string',
    //         'location' => 'required|string',
    //         'days' => 'required',
    //         'travel_destination' => 'required|string',
    //         'budget_per_head' => 'required|string',
    //         'cab_need' => 'required|string',
    //         'total_count' => 'required',
    //         'male_count' => 'required',
    //         'female_count' => 'required',
    //         'travel_date' => 'required',
    //         'rooms_count' => 'required|integer',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $enquiry = EnquiryDetail::create($request->all());

    //     return response()->json([
    //         'message' => 'Enquiry submitted successfully',
    //         'data' => $enquiry
    //     ], 201);
    // }

    public function home_enquiry_form_insert(Request $request)
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
            'child_count' => 'required|integer|min:0',
            'child_age' => 'required_if:child_count,<,1|array|min:' . ($request->input('child_count') > 0 ? $request->input('child_count') : 0),

            // 'child_age.*' => 'integer|min:0', // Validate each age
            // 'child_age' => 'required|min:' . $request->child_count, // Validate as array
            'child_age.*' => 'min:0', // Validate each age
           
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $enquiryData = $request->all();
        $enquiryData['child_age'] = json_encode($request->input('child_age')); // Convert child_age to JSON

        $enquiry = HomeEnquiryDetail::create($enquiryData);
        try {
            // Send email to the client
            Mail::to($enquiry->email)->send(new enquiryEmail([
                'name' => $enquiry->name,
                'email' => $enquiry->email,
                'phone' => $enquiry->phone,
                'comments' => $enquiry->comments,
                'travel_destination' => $enquiry->travel_destination,

            ]));

            // Send email to admin
            Mail::to('contact@innerpece.com')->send(new adminEmail([
                'name' => $enquiry->name,
                'email' => $enquiry->email,
                'phone' => $enquiry->phone,
                'comments' => $enquiry->comments,
                'location' => $enquiry->location,
                'days' => $enquiry->days,
                'travel_destination' => $enquiry->travel_destination,
                'cab_need' => $enquiry->cab_need,
                'total_count' => $enquiry->total_count,
                'child_count' => $enquiry->child_count,
            ]));
        } catch (\Exception $e) {
            // Log any email sending errors
            Log::error('Mail failed: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Enquiry submitted successfully',
            'data' => $enquiry
        ], 201);
    }



    public function getHomeNotification(Request $request, $id)
    {
        $projectDetails = HomeEnquiryDetail::find($id);
        if ($projectDetails) {
            $clientEmail = $projectDetails->email;

            if (filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
                try {
                    // Send email to the client
                    Mail::to($clientEmail)->send(new enquiryEmail([
                        'name' => $projectDetails->name,
                        'email' => $projectDetails->email,
                        'phone' => $projectDetails->phone,
                        'comments' => $projectDetails->comments,
                    ]));

                    // Send email to admin
                    Mail::to('contact@innerpece.com')->send(new adminEmail([
                        'name' => $projectDetails->name,
                        'email' => $projectDetails->email,
                        'phone' => $projectDetails->phone,
                        'comments' => $projectDetails->comments,
                        'travel_destination' => $projectDetails->travel_destination,
                    ]));

                    return response()->json(['success' => true, 'message' => 'Emails sent successfully.']);
                } catch (\Exception $e) {
                    // Log the error details
                    Log::error('Mail failed: ' . $e->getMessage());

                    return response()->json(['success' => false, 'message' => 'Failed to send email.']);
                }
            } else {
                Log::error('Invalid or empty client email: ' . $clientEmail);
                return response()->json(['success' => false, 'message' => 'Client email is empty or invalid.']);
            }
        } else {
            Log::error('Project not found with ID: ' . $id);
            return response()->json(['success' => false, 'message' => 'Project not found.']);
        }
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

    //getting the wishlist list by id
    public function getWishlist(Request $request)
    {
        // Retrieve user_id from the request query or fallback to the authenticated user
        $userId = $request->query('user_id') ?? ($request->user() ? $request->user()->id : null);
    
        if (!$userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized or missing user ID. Please provide a valid user ID or login to continue.',
                'data' => null
            ], 401);
        }
    
        // Fetch the wishlist entries for the provided user ID
        $program_wishlist = Program_wishlist::where('user_id', $userId)
            ->with('program_dts') // Assuming the `program_dts` relationship is correctly defined
            ->get();

         $stay_wishlist = stays_whishlist::where('user_id', $userId)
            ->with('stay_dts') // Assuming the `program_dts` relationship is correctly defined
            ->get();

        $wishlist =  $program_wishlist->merge($stay_wishlist)->sortByDesc('created_at')->values();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Wishlist retrieved successfully.',
            'data' => $wishlist
        ], 200);
    }
    
    // public function getWishlist(Request $request)
    // {
    //     $userId = $request->input('user_id'); // Get the user ID from the request

    //     // Fetch the wishlist entries
    //     $wishlist = Program_wishlist::where('user_id', $userId)
    //         ->with('program_dts')
    //         ->get();

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Wishlist retrieved successfully.',
    //         'data' => $wishlist
    //     ], 200);
    // }
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
        $addressDetailsIds = json_decode($package->address, true) ?? [];
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

        $addressDetails = Address::whereIn('id', $addressDetailsIds)
            ->get(['id', 'title', 'city', 'state', 'country'])
            ->map(function ($item) {
                return [


                    'city' => $item->city,
                    'state' => $item->state,
                    'country' => $item->country,
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
                'addressDetails' => $addressDetails,
            ],
        ], 200);
    }

     public function specific_program_details(Request $request)
    {
        try {
    
            $request->validate([
                'program_id' => 'required',
            ]);

            $programId = $request->input('program_id'); 
           

      
            $program = customer_package::find($programId);
             $Inclusivepackage = InclusivePackages::with('destination', 'theme', 'clientReviews','reviews')->find($program->package_id);

            if (!$program) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Program not found',
                ], 404);
            }

           

            // Check if the program details are already cached
            $cacheKey = "program_details_{$programId}";
            $cachedData = Cache::get($cacheKey);

            if ($cachedData) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Program details retrieved successfully from cache.',
                    'data' => $cachedData
                ], 200);
            }

            // Fetch the program details using the provided ID
            $package = customer_package::find($programId);

            if (!$package) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Program not found.',
                    'data' => null
                ], 404);
            }

            $amenityIds = json_decode($package->amenities, true) ?? [];
            $foodBeverageIds = json_decode($package->food_beverages, true) ?? [];
            $activityIds = json_decode($package->activities, true) ?? [];
            $safetyFeatureIds = json_decode($package->safety_features, true) ?? [];
            // $eventsPackageImages = json_decode($package->events_package_images, true) ?? [];
            $tourPlanning = json_decode($package->tour_planning, true) ?? [];
            $campRule = json_decode($package->camp_rule, true) ?? [];

            $price_title = json_decode($package->price_title, true) ?? [];
            $price_amount = json_decode($package->price_amount, true) ?? [];


            $amenities = Amenities::whereIn('id', $amenityIds)
                ->get(['id', 'amenity_name', 'amenity_pic'])
                ->keyBy('id')
                ->map(function ($item) {
                    return [
                        'amenity_name' => $item->amenity_name,
                        'amenity_pic' => $item->amenity_pic,
                    ];
                });

            // $pricing = Amenities::whereIn('id', $amenityIds)
            // ->get(['id', 'amenity_name', 'amenity_pic'])
            // ->keyBy('id')
            // ->map(function ($item) {
            //     return [
            //         'price_title' => $item->amenity_name,
            //         'price_amount' => $item->amenity_pic,
            //     ];
            // });


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

           
          
           
            $importantInfoPlainText = strip_tags(html_entity_decode($package->important_info, ENT_QUOTES, 'UTF-8'));
            $importantInfoPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $importantInfoPlainText);

            $program_inclusionPlainText = strip_tags($package->program_inclusion);
            $program_inclusionPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $program_inclusionPlainText);


            $importantInfoPlainText = $package->important_info;
            $program_inclusionPlainText = json_decode($package->package_inclusion, true) ?? [];
            $program_exclusionPlainText = json_decode($package->package_exclusion, true) ?? [];

            // $program_exclusionPlainText = strip_tags(html_entity_decode($package->program_exclusion, ENT_QUOTES, 'UTF-8'));
            // $program_exclusionPlainText = str_replace(["<br>", "<br/>", "<br />"], "\n", $program_exclusionPlainText);
            $eventsPackageImages = json_decode($Inclusivepackage->events_package_images, true) ?? [];

              $reviews = $Inclusivepackage->reviews->map(function ($review) {
                $user = $review->user;
                return [
                    'first_name' => $user->first_name ?? null,
                    'last_name' => $user->last_name ?? null,
                    'profile_image' => $user->profile_image ?? null,
                    'comment' => $review->comment,
                    'rating' => $review->rating,
                    'date' => $review->created_at->format('M d, Y'),
                ];
            });

        
            $reviewCount = $Inclusivepackage->reviews->count();

            $responseData = [
                'id' => $package->id,
                'name' => $package->name,
                'title' => $package->package_type,
                'program_desc' => $Inclusivepackage->program_description,
               
                // 'destination' => $package->destination->city_name,
                // 'theme' => $package->theme->themes_name,
                // 'state' => $package->state,
                // 'city' => $package->city,
                // 'address' => $package->address,
                // 'country' => $package->country,
                'tour_planning' => $tourPlanning,
                'cover_img' => $Inclusivepackage->cover_img,
                'gallery_img' => $eventsPackageImages,
                
                // 'total_days' => $package->total_days,
                // 'member_capacity' => $package->member_capacity,
                // 'member_type' => $package->member_type,
                // 'actual_price' => $package->price,
                // 'discount_price' => $package->actual_price,
                'payment_policy' => $campRule,
                'important_info' => $importantInfoPlainText,
                'program_inclusion' => $program_inclusionPlainText,
                'program_exclusion' => $program_exclusionPlainText,
               
                // 'lunch' => $package->lunch,
                // 'dinner' => $package->dinner,
                'amenity_details' => $amenities,
          
                'foodBeverages' => $foodBeverages,
                'activities' => $activities,
                'safety_features' => $safetyFeatures,
               
                
                // 'google_map' => $package->google_map,
                
                // 'created_date' => $package->created_date,
                'current_location' => json_decode($package->location),
                //  'client_reviews' => $clientReviews,
                // 'total_reviews' => $totalReviews,
                'reviews' => $reviews,
                'review_count' => $reviewCount,
                'price_title'=> $price_title,
                'price_amount' =>$price_amount
              
            ];
            
            // if ($user_id) {
            //     $wishlist = Program_wishlist::where('user_id', $user_id)
            //         ->where('program_id', $programId)
            //         ->exists();

            //     $responseData['wishlists'] = $wishlist;
            // }

            // Cache the response data for 60 minutes
            Cache::put($cacheKey, $responseData, 60);

            return response()->json([
                'status' => 'success',
                'message' => 'Program details retrieved successfully.',
                'data' => $responseData
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching program details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }









// public function get_program(Request $request)
// {
  
//     try {
//         $requestData = $request->all(); 

//         $program_type =  $request->input('program_type');
//         $theme =  $request->input('theme');
//         $destination = $request->input('destination');
//         $program_destination =  $request->input('program_destination');
//         $view_type =  $request->input('view_type');

//         // Build the query
//         $query = InclusivePackages::where('status', "1")
//             ->where('is_deleted', "0");
        
//         // Conditionally apply filters based on input
//         if ($program_type) {
//             $query->whereJsonContains('category', $program_type);
//         }

//         if ($theme) {
//             $query->where('theme_id', $theme);
//             $view_type = 'all';
//         }

//         if($destination) {
//             $query->where('city_details', $destination);
//             $view_type = 'all';
//         }

//         if ($program_destination) {
//             $query->where('city_details', $program_destination);
//             $view_type = 'all';
//         }

//         // Apply the limit conditionally
//         if ($view_type !== 'all') {
//             $query->take(4); // Limit to 4 packages if view_type is not 'all'
//         }

//         // Execute the query
//         $packages = $query->with(['theme', 'destination', 'clientReviews'])->paginate(10);
        
//         // Check if any packages were found
//         if ($packages->isEmpty()) {
//             return response()->json([
//                 'status' => 'success',
//                 'message' => 'No ' . str_replace('_', ' ', $program_type) . ' found.',
//                 'data' => []
//             ], 200);
//         }

//         // Helper function to get amenities, food & beverage, activities, and safety features
//         $getDetailsById = function ($package) {
//             $id = $package->id;
            
//             // Call your original method logic here (or modify it to return the required data)
//             $response = (new ProgramApiController)->getAmenitiesFoodBeverageActivitiesSafetyFeaturesById(new Request(['id' => $id]));
//             return json_decode($response->getContent(), true)['data'];
//         };

//         // Process each package to format the output
//         $formattedPackages = $packages->map(function ($package) use ($getDetailsById) {
//             // Decode JSON fields
//             $eventsPackageImages = json_decode($package->cover_img, true);
//             $tourPlanning = json_decode($package->tour_planning, true);
//             $campRule = json_decode($package->camp_rule, true);
//             $amenityDetails = json_decode($package->amenity_details, true);
//             $activities = json_decode($package->activities, true);
//             $safetyFeatures = json_decode($package->safety_features, true);
//             // Process reviews and attach user data
//         $reviews = $package->reviews->map(function ($review) {
//             $user = $review->user; // Get the related user (reviewer's name and image)
//             return [
//                 'first_name' => $review->user->first_name ?? null,  // Get user name, if available
//                 'profile_image' => $review->user->profile_image ?? null,        // User's image
//                 'comment' => $review->comment,
//                 'rating' => $review->rating,
//                 'date' => $review->created_at->format('M d, Y'),
//             ];
//         });
//             // Fetch amenities, food & beverage, activities, safety features
//             $details = $getDetailsById($package);
            
//             // Format the start date
//             $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');

//             // Extract the first image URL
//             $formattedLocation = ucfirst($package->city) . ', ' . ucfirst($package->state);
//             $totalReviews = $package->clientReviews->count();
//             $averageRating = $package->reviews->avg('rating');
//             $category = json_decode($package->category, true) ?? [];
//             $formattedcategory = is_array($category) ? implode(', ', $category) : $category;

            
//             // Return the formatted package data, including additional details
//             return [
//                 'id' => $package->id,
//                 'title' => ucfirst($package->title),
//                 'category' => ucfirst($formattedcategory),
//                 'location' => $formattedLocation,
//                 'total_days' => $package->total_days,
//                 'member_capacity' => $package->member_capacity,
//                 'price' => $package->price,
//                 'actual_price' => $package->actual_price,
//                 'cover_img' => $package->cover_img,
//                 'start_date' => $formattedStartDate,
//                 'theme_id' => $package->theme ? $package->theme->id : null, 
//                 'theme' => $package->theme ? $package->theme->themes_name : null,
//                 'destination_id' => $package->city ? $package->destination->id : null,
//                 'destination' => $package->city ? $package->destination->city_name : null,
//                 'average_rating' => number_format($averageRating, 1),
//                 'totalReviews' => $totalReviews,

//                 'total_room' => $package->total_room,
//                 'bath_room' => $package->bath_room,
//                 'bed_room' => $package->bed_room,
//                 'hall'=> $package->hall,
//                 'reviews' => $reviews,

//                 // Adding the fetched details
//                 'amenities' => $details['amenities'] ?? [],
//                 'foodBeverages' => $details['foodBeverages'] ?? [],
//                 'activities' => $details['activities'] ?? [],
//                 'safetyFeatures' => $details['safetyFeatures'] ?? [],
//             ];
//         });

//         // Return the formatted data with success status
//         return response()->json([
//             'status' => 'success',
//             'message' => '' . str_replace('_', ' ', $program_type) . ' retrieved successfully.',
//             'data' => $formattedPackages
//         ], 200);
//     } catch (\Exception $e) {
       

//         // Return error response
//         return response()->json([
//             'status' => 'error',
//             'message' => 'An error occurred while fetching ',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// } 



    public function manage_wishlist_stay(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'program_id' => 'required|exists:stays_destination_details,id',
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
            $existingWishlist = stays_whishlist::where('user_id', $userId)
                ->where('stay_id', $programId)
                ->first();

            if ($existingWishlist) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Program already in wishlist.',
                    'data' => $existingWishlist
                ], 200);
            }

            // Create a new wishlist entry
            $wishlist = stays_whishlist::create([
                'user_id' => $userId,
                'stay_id' => $programId
            ]);

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Program added to wishlist successfully.',
                'data' => $wishlist
            ], 201);
        } elseif ($action === 'remove') {
            // Check if the entry exists
            $wishlist = stays_whishlist::where('user_id', $userId)
                ->where('stay_id', $programId)
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

    //getting the wishlist list by id
    public function getWishlists(Request $request)
    {
        // Retrieve user_id from the request query or fallback to the authenticated user
        $userId = $request->query('user_id') ?? ($request->user() ? $request->user()->id : null);
    
        if (!$userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized or missing user ID. Please provide a valid user ID or login to continue.',
                'data' => null
            ], 401);
        }
    
        // Fetch the wishlist entries for the provided user ID
        $wishlist = Program_wishlist::where('user_id', $userId)
            ->with('program_dts') // Assuming the `program_dts` relationship is correctly defined
            ->get();

       
    
        return response()->json([
            'status' => 'success',
            'message' => 'Wishlist retrieved successfully.',
            'data' => $wishlist
        ], 200);
    }
}