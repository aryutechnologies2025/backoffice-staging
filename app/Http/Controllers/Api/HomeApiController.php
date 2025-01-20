<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use App\Models\Themes;
use App\Models\Amenities;
use App\Models\FoodBeverage;
use App\Models\Activities;
use App\Models\Safetyfeatures;
use App\Models\City;
use App\Models\Slider;
use App\Models\InclusivePackages;
use App\Models\Group_tour;
use App\Models\Geo_feature;
use App\Models\Influencers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class HomeApiController extends Controller
{

    public function get_slider()
    {
        // Fetch active, non-deleted sliders ordered by 'list_order'
        $sliders = Slider::where('status', "1")
            ->where('is_deleted', "0")
            ->orderBy('list_order', 'asc')
            ->get(['id', 'slider_name', 'subtitle', 'slider_image']);
    
        // Check if any sliders were found
        if ($sliders->isEmpty()) {
            return response()->json([
                'message' => 'No sliders found.',
                'sliders' => []
            ], 404);
        }
    
        // Return the slider data
        return response()->json([
            'message' => 'Sliders retrieved successfully!',
            'sliders' => $sliders
        ], 200);
    }
    

    public function get_themes()
    {
        // Fetch active, non-deleted themes ordered by 'list_order'
        $themes = Themes::where('status', "1")
            ->where('is_deleted', "0")
            ->orderBy('list_order', 'asc')
            ->get(['id', 'themes_name', 'theme_pic']); // Select only the required fields
    
        // Check if any themes were found
        if ($themes->isEmpty()) {
            return response()->json([
                'message' => 'No themes found.',
                'themes' => []
            ], 404);
        }
    
        // Return the theme data
        return response()->json([
            'message' => 'Themes retrieved successfully!',
            'themes' => $themes
        ], 200);
    }
    
    public function get_destination()
    {
        // Fetch active, non-deleted cities ordered by 'list_order'
        $destination_dts = City::where('status', "1")
            ->where('is_deleted', "0")
            ->orderBy('list_order', 'asc')
            ->get(['id', 'city_name', 'cities_pic']); // Select only the required fields
    
        // Check if any cities were found
        if ($destination_dts->isEmpty()) {
            return response()->json([
                'message' => 'No destinations found.',
                'destination_list' => []
            ], 404);
        }
    
        // Return the city data
        return response()->json([
            'message' => 'Destinations retrieved successfully!',
            'destination_list' => $destination_dts
        ], 200);
    }
  


    public function get_program(Request $request)
    {
        DB::listen(function ($query) {
            Log::info('SQL Query: ' . $query->sql);
        });
        try {
            $requestData = $request->all(); 
    
            $program_type =  $request->input('program_type');
            $theme =  $request->input('theme');
            $destination = $request->input('destination');
            $program_destination =  $request->input('program_destination');
            $view_type =  $request->input('view_type');
            $getCity = City::where('status', "1")
            ->where('is_deleted', "0");

            // Build the query
            $query = InclusivePackages::where('status', "1")
                ->where('is_deleted', "0");
            
            // Conditionally apply filters based on input
            if ($program_type) {
                $query->whereJsonContains('category', $program_type);
            }
    
            if ($theme) {
                $query->where('theme_id', $theme);
                $view_type = 'all';
            }
    
            if($destination) {
                $query->where('city_details', $destination);
                $view_type = 'all';
            }
    
            if ($program_destination) {
                $query->where('city_details', $program_destination);
                $view_type = 'all';
            }
    
            // Apply the limit conditionally
            if ($view_type !== 'all') {
                $query->take(4); // Limit to 4 packages if view_type is not 'all'
            }
    
            // Execute the query
            $packages = $query->with(['theme', 'destination', 'clientReviews'])->paginate(10);
            
            // Check if any packages were found
            if ($packages->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No ' . str_replace('_', ' ', $program_type) . ' found.',
                    'data' => []
                ], 200);
            }
    
          
            // Helper function to get amenities, food & beverage, activities, and safety features
            $getDetailsById = function ($package) {
                $id = $package->id;
                
                // Call your original method logic here (or modify it to return the required data)
                $response = (new ProgramApiController)->getAmenitiesFoodBeverageActivitiesSafetyFeaturesById(new Request(['id' => $id]));
                return json_decode($response->getContent(), true)['data'];
            };
    
            // Process each package to format the output
            $formattedPackages = $packages->map(function ($package) use ($getDetailsById) {
                // Decode JSON fields
                $eventsPackageImages = json_decode($package->cover_img, true);
                $tourPlanning = json_decode($package->tour_planning, true);
                $campRule = json_decode($package->camp_rule, true);
                $amenityDetails = json_decode($package->amenity_details, true);
                $activities = json_decode($package->activities, true);
                $safetyFeatures = json_decode($package->safety_features, true);
                // Process reviews and attach user data
            $reviews = $package->reviews->map(function ($review) {
                $user = $review->user; // Get the related user (reviewer's name and image)
                return [
                    'first_name' => $review->user->first_name ?? null,  // Get user name, if available
                    'profile_image' => $review->user->profile_image ?? null,        // User's image
                    'comment' => $review->comment,
                    'rating' => $review->rating,
                    'date' => $review->created_at->format('M d, Y'),
                ];
            });
                // Fetch amenities, food & beverage, activities, safety features
                $details = $getDetailsById($package);
                
                // Format the start date
                $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');
                $formattedendDate = \Carbon\Carbon::parse($package->return_date)->format('M d, Y');

                // Extract the first image URL
                $formattedLocation = ucfirst($package->address) . ', ' . ucfirst($package->state);
                $totalReviews = $package->clientReviews->count();
                $clientReviewsCount = $package->reviews->count();
                $averageRating = $package->reviews->avg('rating');
                $category = json_decode($package->category, true) ?? [];
                $formattedcategory = is_array($category) ? implode(', ', $category) : $category;
    
                
                // Return the formatted package data, including additional details
                return [
                    'id' => $package->id,
                    'title' => ucfirst($package->title),
                    'category' => ucfirst($formattedcategory),
                    // 'location' => $formattedLocation,
                    'total_days' => $package->total_days,
                    'member_capacity' => $package->member_capacity,
                    'price' => $package->price,
                    'actual_price' => $package->actual_price,
                    'cover_img' => $package->cover_img,
                    'start_date' => $formattedStartDate,
                    'end_date' => $formattedendDate,
                    'theme_id' => $package->theme ? $package->theme->id : null, 
                    'theme' => $package->theme ? $package->theme->themes_name : null,
                    'destination_id' => $package->destination ? $package->destination->id : null,
                    'destination' => $package->destination ? $package->destination->city_name : null,
                    'average_rating' => number_format($averageRating, 1),
                    'totalReviews' => $totalReviews,
                    'clientReviewsCount' => $clientReviewsCount,
                    'location' => $package->location,
                    'total_room' => $package->total_room,
                    'bath_room' => $package->bath_room,
                    'bed_room' => $package->bed_room,
                    'hall'=> $package->hall,
                    'reviews' => $reviews,

                    // Adding the fetched details
                    'amenities' => $details['amenities'] ?? [],
                    'foodBeverages' => $details['foodBeverages'] ?? [],
                    'activities' => $details['activities'] ?? [],
                    'safetyFeatures' => $details['safetyFeatures'] ?? [],
                    'addressDetails' => $details['addressDetails'] ?? [],

                   
                ];
            });
    
            // Return the formatted data with success status
            return response()->json([
                'status' => 'success',
                'message' => '' . str_replace('_', ' ', $program_type) . ' retrieved successfully.',
                'data' => $formattedPackages
            ], 200);
        } catch (\Exception $e) {
           
    
            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching ',
                'error' => $e->getMessage()
            ], 500);
        }
    } 
    
    // public function get_program(Request $request)
    // {
    //     try {
    //         // Step 1: Check for Referral Code
    //         $referralCode = $request->query('ref'); // Extract referral code from URL
    //         if ($referralCode) {
    //             $influencer = Influencers::where('reference_id', $referralCode)->first();
                
    //             if ($influencer) {
    //                 // Optionally log the referral or store the information
    //                 Log::info("Referral used by influencer: " . $influencer->name);
                    
    //                 // You can store the referral in a new table, like "ReferralLogs"
    //                 // ReferralLog::create(['influencer_id' => $influencer->id, 'user_ip' => $request->ip()]);
    //             } else {
    //                 // Return an error response for invalid referral code
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'message' => 'Invalid referral code.',
    //                 ], 400);
    //             }
    //         }
    
    //         // Step 2: Fetch the Programs Based on Filters
    //         $program_type = $request->input('program_type');
    //         $theme = $request->input('theme');
    //         $destination = $request->input('destination');
    //         $program_destination = $request->input('program_destination');
    //         $view_type = $request->input('view_type');
            
    //         $query = InclusivePackages::where('status', '1')->where('is_deleted', '0');
    
    //         if ($program_type) {
    //             $query->whereJsonContains('category', $program_type);
    //         }
    
    //         if ($theme) {
    //             $query->where('theme_id', $theme);
    //             $view_type = 'all'; // Change to 'all' if theme is applied
    //         }
    
    //         if ($destination) {
    //             $query->where('city_details', $destination);
    //             $view_type = 'all'; // Change to 'all' if destination is applied
    //         }
    
    //         if ($program_destination) {
    //             $query->where('city_details', $program_destination);
    //             $view_type = 'all'; // Change to 'all' if program_destination is applied
    //         }
    
    //         // Apply pagination based on view_type
    //         if ($view_type !== 'all') {
    //             $query->take(4); // Limit to 4 packages if view_type is not 'all'
    //         }
    
    //         // Execute the query and paginate results
    //         $packages = $query->with(['theme', 'destination', 'clientReviews'])->paginate(10);
    
    //         // Step 3: Check if Packages are Found
    //         if ($packages->isEmpty()) {
    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'No ' . str_replace('_', ' ', $program_type) . ' found.',
    //                 'data' => []
    //             ], 200);
    //         }
    
    //         // Helper function to get additional details like amenities, food & beverages, activities, etc.
    //         $getDetailsById = function ($package) {
    //             $id = $package->id;
    //             $response = (new ProgramApiController)->getAmenitiesFoodBeverageActivitiesSafetyFeaturesById(new Request(['id' => $id]));
    //             return json_decode($response->getContent(), true)['data'];
    //         };
    
    //         // Step 4: Format the Packages Data
    //         $formattedPackages = $packages->map(function ($package) use ($getDetailsById) {
    //             // Decode JSON fields and format
    //             $eventsPackageImages = json_decode($package->cover_img, true);
    //             $tourPlanning = json_decode($package->tour_planning, true);
    //             $campRule = json_decode($package->camp_rule, true);
    //             $amenityDetails = json_decode($package->amenity_details, true);
    //             $activities = json_decode($package->activities, true);
    //             $safetyFeatures = json_decode($package->safety_features, true);
    
    //             // Process reviews
    //             $reviews = $package->reviews->map(function ($review) {
    //                 return [
    //                     'first_name' => $review->user->first_name ?? null,
    //                     'profile_image' => $review->user->profile_image ?? null,
    //                     'comment' => $review->comment,
    //                     'rating' => $review->rating,
    //                     'date' => $review->created_at->format('M d, Y'),
    //                 ];
    //             });
    
    //             // Fetch amenities, food & beverage, activities, safety features
    //             $details = $getDetailsById($package);
    
    //             // Format start date and location
    //             $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');
    //             $formattedLocation = ucfirst($package->address) . ', ' . ucfirst($package->state);
    //             $totalReviews = $package->clientReviews->count();
    //             $averageRating = $package->reviews->avg('rating');
    //             $category = json_decode($package->category, true) ?? [];
    //             $formattedcategory = is_array($category) ? implode(', ', $category) : $category;
    
    //             // Return the formatted package data
    //             return [
    //                 'id' => $package->id,
    //                 'title' => ucfirst($package->title),
    //                 'category' => ucfirst($formattedcategory),
    //                 'total_days' => $package->total_days,
    //                 'member_capacity' => $package->member_capacity,
    //                 'price' => $package->price,
    //                 'cover_img' => $package->cover_img,
    //                 'start_date' => $formattedStartDate,
    //                 'theme' => $package->theme->themes_name ?? null,
    //                 'destination' => $package->destination->city_name ?? null,
    //                 'average_rating' => number_format($averageRating, 1),
    //                 'totalReviews' => $totalReviews,
    //                 'reviews' => $reviews,
    //                 'amenities' => $details['amenities'] ?? [],
    //                 'foodBeverages' => $details['foodBeverages'] ?? [],
    //                 'activities' => $details['activities'] ?? [],
    //                 'safetyFeatures' => $details['safetyFeatures'] ?? [],
    //             ];
    //         });
    
    //         // Step 5: Return API Response with Program Data
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Programs retrieved successfully.',
    //             'data' => $formattedPackages
    //         ], 200);
    
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An error occurred while fetching programs.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
    





    //getting the title in InclusivePackages
    // public function get_title(Request $request)
    // {
    //     try {
    //         $titles = InclusivePackages::where('is_deleted', '0')->get(['id', 'title']);
    //         $affiliateLinks = $titles->map(function ($title) use ($influencer) {
    //             $baseUrl = url('/' . $title->id . '/' . str_replace(' ', '-', strtolower($title->title)));
    //             return [
    //                 'title' => $title->title,
    //                 'url' => $baseUrl . '?ref=' . $influencer->referral_code,
    //             ];
    //         });
    
    //         return response()->json(['status' => '1', 'data' => $titles]);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => '0', 'message' => 'An error occurred while fetching titles.']);
    //     }
    // }


//dashboard api 
public function get_combined_data(Request $request)
{
    try {
        $response = [];

        // Fetch programs if the request includes parameters for programs
        if ($request->has('program_type') || $request->has('theme') || $request->has('destination') || $request->has('program_destination')) {
            $response['programs'] = $this->get_program($request)->getData(true);
        }

        // Fetch themes
        $themes = Themes::where('status', "1")
            ->where('is_deleted', "0")
            ->orderBy('list_order', 'asc')
            ->get(['id', 'themes_name', 'theme_pic']);

        $response['themes'] = $themes->isEmpty() ? [] : $themes;

        // Fetch sliders
        $sliders = Slider::where('status', "1")
            ->where('is_deleted', "0")
            ->orderBy('list_order', 'asc')
            ->get(['id', 'slider_name', 'subtitle', 'slider_image']);

        $response['sliders'] = $sliders->isEmpty() ? [] : $sliders;

        // Fetch destinations
        $destinations = City::where('status', "1")
            ->where('is_deleted', "0")
            ->orderBy('list_order', 'asc')
            ->get(['id', 'city_name', 'cities_pic']);

        $response['destinations'] = $destinations->isEmpty() ? [] : $destinations;

        // Return combined data
        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully!',
            'data' => $response
        ], 200);
    } catch (\Exception $e) {
        // Log the exception
        \Log::error('Error fetching combined data: ' . $e->getMessage());

        // Return error response
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while fetching data.',
            'error' => $e->getMessage()
        ], 500);
    }
}



public function home_filter(Request $request)
{
    try {
        $destination = $request->input('destination', '');
        $start_date = $request->input('start_date');

        // Base query for fetching packages
        $packagesQuery = InclusivePackages::where('is_deleted', "0");

        // Filter by date range
        if ($start_date) {
            $packagesQuery->whereDate('start_date', '<=', $start_date)
                          ->whereDate('return_date', '>=', $start_date);
        }

        // Filter by destination
        if ($destination) {
            $packagesQuery->whereHas('destination', function ($query) use ($destination) {
                $query->whereRaw('LOWER(REPLACE(city_name, " ", "")) LIKE ?', ['%' . strtolower(str_replace(' ', '', $destination)) . '%']);
            });
        }

        // Fetch packages with relationships
        $packages = $packagesQuery->with(['destination', 'theme', 'clientReviews'])->get();

        if ($packages->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No packages found for the given filters.',
                'data' => []
            ], 200);
        }

        // Initialize IDs for fetching amenities, food, activities, and safety features
        $amenityIds = [];
        $foodBeverageIds = [];
        $activityIds = [];
        $safetyFeatureIds = [];

        foreach ($packages as $package) {
            $amenityIds = array_merge($amenityIds, json_decode($package->amenity_details, true) ?? []);
            $foodBeverageIds = array_merge($foodBeverageIds, json_decode($package->food_beverages, true) ?? []);
            $activityIds = array_merge($activityIds, json_decode($package->activities, true) ?? []);
            $safetyFeatureIds = array_merge($safetyFeatureIds, json_decode($package->safety_features, true) ?? []);
        }

        $amenities = Amenities::whereIn('id', $amenityIds)
        ->get(['id', 'amenity_name', 'amenity_pic'])
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'amenity_name' => $item->amenity_name,
                'amenity_pic' => $item->amenity_pic,
            ];
        })->values();
    
    $foodBeverages = FoodBeverage::whereIn('id', $foodBeverageIds)
        ->get(['id', 'food_beverage', 'food_beverage_pic'])
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'food_beverage' => $item->food_beverage,
                'food_beverage_pic' => $item->food_beverage_pic,
            ];
        })->values();
    
    $activities = Activities::whereIn('id', $activityIds)
        ->get(['id', 'activities', 'activities_pic'])
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'activities' => $item->activities,
                'activities_pic' => $item->activities_pic,
            ];
        })->values();
    
    $safetyFeatures = Safetyfeatures::whereIn('id', $safetyFeatureIds)
        ->get(['id', 'safety_features', 'safety_features_pic'])
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'safety_features' => $item->safety_features,
                'safety_features_pic' => $item->safety_features_pic,
            ];
        })->values();
    
        // Format package data
        $formattedPackages = $packages->map(function ($package) use ($amenities, $foodBeverages, $activities, $safetyFeatures) {
            $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');
            $formattedLocation = ucfirst($package->city) . ', ' . ucfirst($package->state);
            $totalReviews = $package->clientReviews->count();
            $averageRating = $package->clientReviews->avg('rating') ?: 0;
            $category = json_decode($package->category, true) ?? [];
            $formattedCategory = is_array($category) ? implode(', ', $category) : $category;

            return [
                'id' => $package->id,
                'title' => ucfirst($package->title),
                'category' => ucfirst($formattedCategory),
                // 'location' => $formattedLocation,
                'total_days' => $package->total_days,
                'member_capacity' => $package->member_capacity,
                'price' => $package->price,
                'actual_price' => $package->actual_price,
                'cover_img' => $package->cover_img,
                'start_date' => $formattedStartDate,
                'theme_id' => $package->theme ? $package->theme->id : null,
                'theme' => $package->theme ? $package->theme->themes_name : null,
                'destination_id' => $package->destination ? $package->destination->id : null,
                'destination' => $package->destination ? $package->destination->city_name : null,
                'average_rating' => number_format($averageRating, 1),
                'totalReviews' => $totalReviews,
                'amenity_details' => $amenities,
                'foodBeverages' => $foodBeverages,
                'activities' => $activities,
                'safety_features' => $safetyFeatures,
                'total_room' => $package->total_room,
                'bath_room' => $package->bath_room,
                'bed_room' => $package->bed_room,
                'hall' => $package->hall,
                'location' => $package->location
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Packages retrieved successfully.',
            'data' => $formattedPackages
        ], 200);
    } catch (\Exception $e) {
        \Log::error('Error fetching packages: ' . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while fetching packages.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    
    public function filter_program_by_date(Request $request)
    {
        try {
            // Validate the request inputs
            $request->validate([
                'start_date' => 'required|date',
                'to_date' => 'required|date',
                'theme' => 'required|string',
            ]);
    
            // Get the inputs
            $startDate = $request->input('start_date');
            $toDate = $request->input('to_date');
            $themeName = $request->input('theme');
    
            // Query the InclusivePackages model
            $packages = InclusivePackages::whereBetween('start_date', [$startDate, $toDate])
                ->where('is_deleted', "0") // Filter out deleted packages
                ->whereHas('theme', function ($query) use ($themeName) {
                    $query->where('themes_name', $themeName);
                })
                ->with(['destination', 'theme', 'clientReviews'])
                ->get();
    
            // Check if packages were found
            if ($packages->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No programs found for the specified date and theme.',
                    'data' => []
                ], 404);
            }
    
            // Format the packages for the response
            $formattedPackages = $packages->map(function ($package) {
                $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');
                $formattedLocation = ucfirst($package->city) . ', ' . ucfirst($package->state);
                $totalReviews = $package->clientReviews->count();
                $averageRating = $package->clientReviews->avg('rating') ?: 0; // Default to 0 if no reviews
                $category = json_decode($package->category, true) ?? [];
                $formattedCategory = is_array($category) ? implode(', ', $category) : $category;
    
                return [
                    'id' => $package->id,
                    'title' => ucfirst($package->title),
                    'category' => ucfirst($formattedCategory),
                    // 'location' => $formattedLocation,
                    'total_days' => $package->total_days,
                    'member_capacity' => $package->member_capacity,
                    'price' => $package->price,
                    'actual_price' => $package->actual_price,
                    'cover_img' => $package->cover_img,
                    'start_date' => $formattedStartDate,
                    'theme_id' => $package->theme ? $package->theme->id : null,
                    'theme' => $package->theme ? $package->theme->themes_name : null,
                    'destination_id' => $package->destination ? $package->destination->id : null,
                    'destination' => $package->destination ? $package->destination->city_name : null,
                    'average_rating' => number_format($averageRating, 1),
                    'totalReviews' => $totalReviews,
                    'location' => $package->location,
                ];
            });
    
            // Return the formatted data with success status
            return response()->json([
                'status' => 'success',
                'message' => 'Programs retrieved successfully.',
                'data' => $formattedPackages
            ], 200);
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error('Error fetching programs: ' . $e->getMessage());
    
            // Return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching programs.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function filter_destination_by_date(Request $request)
    {
        try {
            // Validate the request inputs
            $request->validate([
                'start_date' => 'required|date',
                'to_date' => 'required|date',
                'destination' => 'required|string',  // Validate theme as a string
            ]);
    
            // Get the start_date and theme from the request
            $startDate = $request->input('start_date');
            $toDate = $request->input('to_date');
            $destinationName = $request->input('destination');
    
            // Query the InclusivePackages model for packages matching the start_date and theme name
            $packages = InclusivePackages::whereBetween('start_date', [$startDate, $toDate])
                ->whereHas('destination', function ($query) use ($destinationName) {
                    $query->where('city_name', $destinationName);
                })
                ->with('destination', 'theme', 'clientReviews')
                ->get()
                ->where('is_deleted', "0");
    
            // Check if packages were found
            if ($packages->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No programs found for the specified date and theme.',
                    'data' => null
                ], 404);
            }
    
            // Format the packages for the response
            $formattedPackages = $packages->map(function ($package) {
                // Decode JSON fields if needed
                // $eventsPackageImages = json_decode($package->cover_img, true);
                // $tourPlanning = json_decode($package->tour_planning, true);
                // $campRule = json_decode($package->camp_rule, true);
                // $amenityDetails = json_decode($package->amenity_details, true);
                // $activities = json_decode($package->activities, true);
                // $safetyFeatures = json_decode($package->safety_features, true);
    
                // Format the start date
                $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');
    
                // Format the location string
                $formattedLocation = ucfirst($package->city) . ', ' . ucfirst($package->state);
                $totalReviews = $package->clientReviews->count();
                $averageRating = $package->clientReviews->avg('rating');
                $category = json_decode($package->category, true) ?? [];
                $formattedCategory = is_array($category) ? implode(', ', $category) : $category;
    
                // Return the formatted package data
                return [
                    'id' => $package->id,
                    'title' => ucfirst($package->title),
                    'category' => ucfirst($formattedCategory),
                    'location' => $formattedLocation,
                    'total_days' => $package->total_days,
                    'member_capacity' => $package->member_capacity,
                    'price' => $package->price,
                    'actual_price' => $package->actual_price,
                    'cover_img' => $package->cover_img,
                    'start_date' => $formattedStartDate,
                    'theme_id' => $package->theme ? $package->theme->id : null,
                    'theme' => $package->theme ? $package->theme->themes_name : null,
                    'destination_id' => $package->destination ? $package->destination->id : null,
                    'destination' => $package->destination ? $package->destination->city_name : null,
                    'average_rating' => number_format($averageRating, 1),
                    'totalReviews' => $totalReviews,
                ];
            });
    
            // Return the formatted data with success status
            return response()->json([
                'status' => 'success',
                'message' => ucfirst($startDate) . ' retrieved successfully.',
                'data' => $formattedPackages
            ], 200);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error fetching ' . ucfirst($startDate) . ': ' . $e->getMessage());
    
            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching ' . ucfirst($startDate) . '.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sort_program(Request $request)
{
    try {
        $request->validate([
            'sort_by' => 'nullable|in:recent,price_low_to_high,price_high_to_low',
            'theme' => 'required|string', 
        ]);


        $sortBy = $request->input('sort_by');
        $themeName = $request->input('theme');

        $query = InclusivePackages::query()
        ->whereHas('theme', function ($query) use ($themeName) {
            $query->where('themes_name', $themeName);
        });

        switch ($sortBy) {
            case 'recent':
                $query->orderBy('id', 'desc');
                break;

            case 'price_low_to_high':
                $query->orderBy('actual_price', 'asc');
                break;

            case 'price_high_to_low':
                $query->orderBy('actual_price', 'desc');
                break;

            default:
                $query->orderBy('id', 'desc');
                break;
        }

        // Fetch the results
        $packages = $query->with('destination', 'theme', 'clientReviews')->get()
            ->where('is_deleted', "0");

            if ($packages->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No programs found for the specified theme.',
                    'data' => null
                ], 404);
            }

         // Format the packages for the response
         $formattedPackages = $packages->map(function ($package) {
            // Format the start date
            $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');

            // Format the location string
            $formattedLocation = ucfirst($package->city) . ', ' . ucfirst($package->state);
            $totalReviews = $package->clientReviews->count();
            $averageRating = $package->clientReviews->avg('rating');
            $category = json_decode($package->category, true) ?? [];
            $formattedCategory = is_array($category) ? implode(', ', $category) : $category;

            // Return the formatted package data
            return [
                'id' => $package->id,
                'title' => ucfirst($package->title),
                'category' => ucfirst($formattedCategory),
                'location' => $formattedLocation,
                'total_days' => $package->total_days,
                'member_capacity' => $package->member_capacity,
                'price' => $package->price,
                'actual_price' => $package->actual_price,
                'cover_img' => $package->cover_img,
                'start_date' => $formattedStartDate,
                'theme_id' => $package->theme ? $package->theme->id : null,
                'theme' => $package->theme ? $package->theme->themes_name : null,
                'destination_id' => $package->destination ? $package->destination->id : null,
                'destination' => $package->destination ? $package->destination->city_name : null,
                'average_rating' => number_format($averageRating, 1),
                'totalReviews' => $totalReviews,
            ];
        });

        // Return the formatted data with success status
        return response()->json([
            'status' => 'success',
            'message' => 'Packages retrieved successfully.',
            'data' => $formattedPackages
        ], 200);
    } catch (\Exception $e) {
        // Log the exception
        \Log::error('Error fetching packages: ' . $e->getMessage());

        // Return error response
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while fetching packages.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function sort_destination(Request $request)
{
    try {
        $request->validate([
            'sort_by' => 'nullable|in:recent,price_low_to_high,price_high_to_low',
            'destination' => 'required|string', 
        ]);

        $sortBy = $request->input('sort_by');
        $destinationName = $request->input('destination');

        $query = InclusivePackages::query()
        ->whereHas('destination', function ($query) use ($destinationName) {
            $query->where('city_name', $destinationName);
        });

        switch ($sortBy) {
            case 'recent':
                $query->orderBy('id', 'desc');
                break;

            case 'price_low_to_high':
                $query->orderBy('actual_price', 'asc');
                break;

            case 'price_high_to_low':
                $query->orderBy('actual_price', 'desc');
                break;

            default:
                $query->orderBy('id', 'desc');
                break;
        }

        // Fetch the results
        $packages = $query->with('destination', 'theme', 'clientReviews')->get()
            ->where('is_deleted', "0");

            if ($packages->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No programs found for the specified theme.',
                    'data' => null
                ], 404);
            }

         // Format the packages for the response
         $formattedPackages = $packages->map(function ($package) {
            // Format the start date
            $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');

            // Format the location string
            $formattedLocation = ucfirst($package->city) . ', ' . ucfirst($package->state);
            $totalReviews = $package->clientReviews->count();
            $averageRating = $package->clientReviews->avg('rating');
            $category = json_decode($package->category, true) ?? [];
            $formattedCategory = is_array($category) ? implode(', ', $category) : $category;

            // Return the formatted package data
            return [
                'id' => $package->id,
                'title' => ucfirst($package->title),
                'category' => ucfirst($formattedCategory),
                'location' => $formattedLocation,
                'total_days' => $package->total_days,
                'member_capacity' => $package->member_capacity,
                'price' => $package->price,
                'actual_price' => $package->actual_price,
                'cover_img' => $package->cover_img,
                'start_date' => $formattedStartDate,
                'theme_id' => $package->theme ? $package->theme->id : null,
                'theme' => $package->theme ? $package->theme->themes_name : null,
                'destination_id' => $package->destination ? $package->destination->id : null,
                'destination' => $package->destination ? $package->destination->city_name : null,
                'average_rating' => number_format($averageRating, 1),
                'totalReviews' => $totalReviews,
            ];
        });

        // Return the formatted data with success status
        return response()->json([
            'status' => 'success',
            'message' => 'Packages retrieved successfully.',
            'data' => $formattedPackages
        ], 200);
    } catch (\Exception $e) {
        // Log the exception
        \Log::error('Error fetching packages: ' . $e->getMessage());

        // Return error response
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while fetching packages.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function search_program(Request $request)
{
    try {
        // Validate the request inputs
        $request->validate([
            'title' => 'nullable|string',  // Validate title as a string if provided
            'theme' => 'nullable|string'  // Validate theme as a string if provided
        ]);

        // Get the title and theme from the request
        $title = $request->input('title');
        $themeName = $request->input('theme');

        // Build the query
        $query = InclusivePackages::query()
            ->where('is_deleted', "0");

        // Apply filtering by title if provided
        if ($title) {
            $query->where('title', 'like', '%' . $title . '%');
        }

        // Apply filtering by theme if provided
        if ($themeName) {
            $query->whereHas('theme', function ($query) use ($themeName) {
                $query->where('themes_name', $themeName);
            });
        }

        // Execute the query
        $packages = $query->with('destination', 'theme', 'clientReviews')->get();

        // Check if packages were found
        if ($packages->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No programs found for the specified title and theme.',
                'data' => null
            ], 404);
        }

        
        // Format the packages for the response
        $formattedPackages = $packages->map(function ($package) {
            // Format the start date
            $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');

            // Format the location string
            $formattedLocation = ucfirst($package->city) . ', ' . ucfirst($package->state);
            $totalReviews = $package->clientReviews->count();
            $averageRating = $package->clientReviews->avg('rating');
            $category = json_decode($package->category, true) ?? [];
            $formattedCategory = is_array($category) ? implode(', ', $category) : $category;

            // Return the formatted package data
            return [
                'id' => $package->id,
                'title' => ucfirst($package->title),
                'category' => ucfirst($formattedCategory),
                'location' => $formattedLocation,
                'total_days' => $package->total_days,
                'member_capacity' => $package->member_capacity,
                'price' => $package->price,
                'actual_price' => $package->actual_price,
                'cover_img' => $package->cover_img,
                'start_date' => $formattedStartDate,
                'theme_id' => $package->theme ? $package->theme->id : null,
                'theme' => $package->theme ? $package->theme->themes_name : null,
                'destination_id' => $package->destination ? $package->destination->id : null,
                'destination' => $package->destination ? $package->destination->city_name : null,
                'average_rating' => number_format($averageRating, 1),
                'totalReviews' => $totalReviews,
            ];
        });

        // Return the formatted data with success status
        return response()->json([
            'status' => 'success',
            'message' => 'Programs retrieved successfully.',
            'data' => $formattedPackages
        ], 200);
    } catch (\Exception $e) {
        // Log the exception
        \Log::error('Error searching programs: ' . $e->getMessage());

        // Return error response
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while searching for programs.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function search_destination(Request $request)
{
    try {
        // Validate the request inputs
        $request->validate([
            'title' => 'nullable|string',  // Validate title as a string if provided
            'destination' => 'nullable|string'  // Validate theme as a string if provided
        ]);

        // Get the title and theme from the request
        $title = $request->input('title');
        $destinationName = $request->input('destination');

        // Build the query
        $query = InclusivePackages::query()
            ->where('is_deleted', "0");

        // Apply filtering by title if provided
        if ($title) {
            $query->where('title', 'like', '%' . $title . '%');
        }

        // Apply filtering by theme if provided
        if ($destinationName) {
            $query->whereHas('destination', function ($query) use ($destinationName) {
                $query->where('city_name', $destinationName);
            });
        }

        // Execute the query
        $packages = $query->with('destination', 'theme', 'clientReviews')->get();

        // Check if packages were found
        if ($packages->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No programs found for the specified title and theme.',
                'data' => null
            ], 404);
        }

        // Format the packages for the response
        $formattedPackages = $packages->map(function ($package) {
            // Format the start date
            $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');

            // Format the location string
            $formattedLocation = ucfirst($package->city) . ', ' . ucfirst($package->state);
            $totalReviews = $package->clientReviews->count();
            $averageRating = $package->clientReviews->avg('rating');
            $category = json_decode($package->category, true) ?? [];
            $formattedCategory = is_array($category) ? implode(', ', $category) : $category;

            // Return the formatted package data
            return [
                'id' => $package->id,
                'title' => ucfirst($package->title),
                'category' => ucfirst($formattedCategory),
                'location' => $formattedLocation,
                'total_days' => $package->total_days,
                'member_capacity' => $package->member_capacity,
                'price' => $package->price,
                'actual_price' => $package->actual_price,
                'cover_img' => $package->cover_img,
                'start_date' => $formattedStartDate,
                'theme_id' => $package->theme ? $package->theme->id : null,
                'theme' => $package->theme ? $package->theme->themes_name : null,
                'destination_id' => $package->destination ? $package->destination->id : null,
                'destination' => $package->destination ? $package->destination->city_name : null,
                'average_rating' => number_format($averageRating, 1),
                'totalReviews' => $totalReviews,
            ];
        });

        // Return the formatted data with success status
        return response()->json([
            'status' => 'success',
            'message' => 'Programs retrieved successfully.',
            'data' => $formattedPackages
        ], 200);
    } catch (\Exception $e) {
        // Log the exception
        \Log::error('Error searching programs: ' . $e->getMessage());

        // Return error response
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while searching for programs.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function get_group_booking(){
        $group_tour = Group_tour::where('status', "1")
        ->where('is_deleted', "0")
        ->get(['id', 'tour_title', 'tour_code','tour_location','tour_desc','group_tour_img']); // Only select the required fields

    // Check if themes were found
    if ($group_tour->isEmpty()) {
        return response()->json([
            'message' => 'No Group Booking found',
            'group_booking' => []
        ], 404);
    }

    // Return themes data
    return response()->json([
        'message' => 'Group Booking retrieved successfully!',
        'group_booking' => $group_tour
    ], 200);
    }



    public function get_filter_options()
    {
        try {
            // Retrieve distinct filter options
            $cities = City::where('status', "1")->where('is_deleted', "0")->get(['id', 'city_name']);
            // Return the filter options
            return response()->json([
                'status' => 'success',
                'data' => [
                    'cities' => $cities,
                ]
            ], 200);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error fetching filter options: ' . $e->getMessage());

            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching filter options.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // public function get_upcoming_programs()
    // {
    //     try {
    //         // Fetch the packages that meet the criteria
    //         $packages = InclusivePackages::where('status', "1")
    //             ->where('is_deleted', "0")
    //             ->where('category', 'upcoming_program')
    //             ->get();

    //         // Check if any packages were found
    //         if ($packages->isEmpty()) {
    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'No Upcoming programs found.',
    //                 'data' => []
    //             ], 200);
    //         }

    //         // Process each package to format the output
    //         $formattedPackages = $packages->map(function ($package) {
    //             // Decode JSON fields
    //             $eventsPackageImages = json_decode($package->events_package_images, true);
    //             $tourPlanning = json_decode($package->tour_planning, true);
    //             $campRule = json_decode($package->camp_rule, true);
    //             $amenityDetails = json_decode($package->amenity_details, true);
    //             $activities = json_decode($package->activities, true);
    //             $safetyFeatures = json_decode($package->safety_features, true);

    //             // Format the start date
    //             $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');

    //             // Extract the first image URL
    //             $firstImage = isset($eventsPackageImages[0]) ? $eventsPackageImages[0] : null;

    //             // Return the formatted package data
    //             return [
    //                 'id' => $package->id,
    //                 'title' => $package->title,
    //                 'category' => $package->category,
    //                 'state' => $package->state,
    //                 'city' => $package->city,
    //                 'events_package_images' => $firstImage,
    //                 'start_date' => $formattedStartDate
    //             ];
    //         });

    //         // Return the formatted data with success status
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Upcoming programs retrieved successfully.',
    //             'data' => $formattedPackages
    //         ], 200);
    //     } catch (\Exception $e) {
    //         // Log the exception
    //         \Log::error('Error fetching Upcoming programs: ' . $e->getMessage());

    //         // Return error response
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An error occurred while fetching Upcoming programs.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }


    // public function get_popular_program()
    // {
    //     try {
    //         // Fetch the packages that meet the criteria
    //         $packages = InclusivePackages::where('status', "1")
    //             ->where('is_deleted', "0")
    //             ->where('category', 'popular_program')
    //             ->get();

    //         // Check if any packages were found
    //         if ($packages->isEmpty()) {
    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'No Popular programs found.',
    //                 'data' => []
    //             ], 200);
    //         }

    //         // Process each package to format the output
    //         $formattedPackages = $packages->map(function ($package) {
    //             // Decode JSON fields
    //             $eventsPackageImages = json_decode($package->events_package_images, true);
    //             $tourPlanning = json_decode($package->tour_planning, true);
    //             $campRule = json_decode($package->camp_rule, true);
    //             $amenityDetails = json_decode($package->amenity_details, true);
    //             $activities = json_decode($package->activities, true);
    //             $safetyFeatures = json_decode($package->safety_features, true);

    //             // Format the start date
    //             $formattedStartDate = \Carbon\Carbon::parse($package->start_date)->format('M d, Y');

    //             // Extract the first image URL
    //             $firstImage = isset($eventsPackageImages[0]) ? $eventsPackageImages[0] : null;

    //             // Return the formatted package data
    //             return [
    //                 'id' => $package->id,
    //                 'title' => $package->title,
    //                 'category' => $package->category,
    //                 'state' => $package->state,
    //                 'city' => $package->city,
    //                 'events_package_images' => $firstImage,
    //                 'start_date' => $formattedStartDate
    //             ];
    //         });

    //         // Return the formatted data with success status
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Popular programs retrieved successfully.',
    //             'data' => $formattedPackages
    //         ], 200);
    //     } catch (\Exception $e) {
    //         // Log the exception
    //         \Log::error('Error fetching Popular programs: ' . $e->getMessage());

    //         // Return error response
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An error occurred while fetching Popular programs.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
