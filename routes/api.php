<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SiteApiController;
use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\Api\ProgramApiController;

/*
|----------------------------------------------------------------------
| API Routes
|----------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "api" middleware group. Make something great!
|
*/

// Public Routes (No Authentication)

Route::prefix('v1')->group(function () {
    // Rate-Limited Public Routes
    Route::middleware(['throttle:60,1'])->group(function () {
        // CSRF Token
        Route::get('/csrf-token', [AuthController::class, 'getToken']);

        // Auth Routes
        Route::post('/signup', [AuthController::class, 'signup']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/contact', [AuthController::class, 'store_contact']); // Contact Form

        // Site Content Routes
        Route::get('/settings', [SiteApiController::class, 'getSettings']);
        Route::get('/header-content', [SiteApiController::class, 'getheader_dts']);
        Route::get('/footer-content', [SiteApiController::class, 'getfooter_dts']);
        Route::get('/faq', [SiteApiController::class, 'getFaq']);
        Route::get('/podcast', [SiteApiController::class, 'getPodcasts']);
        Route::get('/get-header-footer', [SiteApiController::class, 'get_header_footer']);
        Route::get('/theme', [HomeApiController::class, 'get_themes']);
        Route::get('/destination', [HomeApiController::class, 'get_destination']);
        Route::get('/slider', [HomeApiController::class, 'get_slider']);
        Route::get('/group-booking', [HomeApiController::class, 'get_group_booking']);
        Route::get('/get-filter-option', [HomeApiController::class, 'get_filter_options']);
        Route::get('/get-combined-data', [HomeApiController::class, 'get_combined_data']);

        // Program Filtering Routes
        Route::post('/get-program', [HomeApiController::class, 'get_program']);
        Route::post('/home-filter', [HomeApiController::class, 'home_filter']);
        Route::post('/filter-program-by-price_sort', [ProgramApiController::class, 'filter_program_by_price_sort']);
        Route::post('/destination-program-by-price_sort', [ProgramApiController::class, 'destination_program_by_price_sort']);
        Route::post('/filter-program-by-date', [HomeApiController::class, 'filter_program_by_date']);
        Route::post('/filter-destination', [HomeApiController::class, 'filter_destination_by_date']);
        Route::post('/filter-program-by-price', [ProgramApiController::class, 'filter_program_by_price']);
        Route::post('/filter-program', [ProgramApiController::class, 'filter_program_by_date_and_price']);
        Route::post('/sort-program', [HomeApiController::class, 'sort_program']);
        Route::post('/sort-destination', [HomeApiController::class, 'sort_destination']);
        Route::post('/search-program', [HomeApiController::class, 'search_program']);
        Route::post('/search-destination', [HomeApiController::class, 'search_destination']);
        Route::post('/get-program-details', [ProgramApiController::class, 'get_program_details']);
    });
});

// Protected Routes (Require Authentication)

Route::middleware('auth:sanctum')->group(function () {
    // User-related Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Wishlist Management
    Route::post('/add-remove-wishlist', [ProgramApiController::class, 'manage_wishlist']);

    // Program Details
    
    Route::post('/enquiry-form', [ProgramApiController::class, 'enquiry_form_insert']);

    // Amenities
    Route::get('/get-amenities', [ProgramApiController::class, 'getAmenities']);
    Route::post('/getActivitiesbyId', [ProgramApiController::class, 'getAmenitiesFoodBeverageActivitiesSafetyFeaturesById']);
});

