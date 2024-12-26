<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SiteApiController;
use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\Api\ProgramApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// // Route::middleware('auth:sanctum')->get('/getdata', [ApiController::class, 'getData']);


// /* API_ROUTES */

Route::get('/csrf-token', [AuthController::class, 'getToken']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
// Route::post('/contact', [AuthController::class, 'store']);
Route::post('/contact', [AuthController::class, 'store_contact']);


Route::get('/settings', [SiteApiController::class, 'getSettings']);
Route::get('/header-content', [SiteApiController::class, 'getheader_dts']);
Route::get('/footer-content', [SiteApiController::class, 'getfooter_dts']);
Route::get('/faq', [SiteApiController::class, 'getFaq']);
Route::get('/podcast', [SiteApiController::class, 'getPodcasts']);

Route::get('/theme', [HomeApiController::class, 'get_themes']);
Route::get('/destination', [HomeApiController::class, 'get_destination']);
Route::get('/slider', [HomeApiController::class, 'get_slider']);
// Route::get('/upcoming-programs', [HomeApiController::class, 'get_upcoming_programs']);
// Route::get('/popular-programs', [HomeApiController::class, 'get_popular_programs']);

Route::get('/group-booking', [HomeApiController::class, 'get_group_booking']);

Route::get('/get-filter-option', [HomeApiController::class, 'get_filter_options']);
Route::post('/get-program', [HomeApiController::class, 'get_program']);

Route::post('/home-filter',[HomeApiController::class, 'home_filter']);

Route::post('/get-program-details', [ProgramApiController::class, 'get_program_details']);
Route::post('/enquiry-form', [ProgramApiController::class, 'enquiry_form_insert']);
Route::get('/get-amenities', [ProgramApiController::class, 'getAmenities']);
// Route for filtering programs by start date
// Route for filtering programs by start date
Route::post('/filter-program-by-date', [HomeApiController::class, 'filter_program_by_date']);
Route::post('/filter-destination',[HomeApiController::class, 'filter_destination_by_date']);
Route::post('/filter-program-by-price',[ProgramApiController::class, 'filter_program_by_price']);
Route::post('/filter-program',[ProgramApiController::class,'filter_program_by_date_and_price']);
Route::post('/sort-program',[HomeApiController::class,'sort_program']);
Route::post('/sort-destination',[HomeApiController::class,'sort_destination']);
Route::post('/search-program',[HomeApiController::class,'search_program']);
Route::post('/search-destination',[HomeApiController::class,'search_destination']);

// Protected Routes (Require Authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Logout route to revoke all tokens for the authenticated user
    Route::post('/logout', [AuthController::class, 'logout']);

    // Route to get the authenticated user's information
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/add-remove-wishlist', [ProgramApiController::class, 'manage_wishlist']);

    // Route to get data (replace with actual method if needed)
    Route::get('/getdata', [ApiController::class, 'getData']);

    // Route to store contact us form data
    // Route::post('/contact', [AuthController::class, 'store']);
});

// Route::get('/csrf-token', [AuthController::class, 'getToken']);
// Route::middleware('web')->get('/csrf-token', [AuthController::class, 'getToken']);



