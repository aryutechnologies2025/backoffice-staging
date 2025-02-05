<?php


use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\PackagesController;
use App\Http\Controllers\Admin\FAQController;
use App\Http\Controllers\Admin\PodcastController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\AmenitiesController;
use App\Http\Controllers\Admin\Food_beverageController;
use App\Http\Controllers\Admin\ActivitiesController;
use App\Http\Controllers\Admin\Safety_featuresController;
use App\Http\Controllers\Admin\All_Inclusive_PackController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\Geo_featureController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ThemesController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\Contact_usController;
use App\Http\Controllers\Admin\ThemesCatController;
use App\Http\Controllers\Admin\DestinationCatController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\GroupTourController;
use App\Http\Controllers\Admin\ClientreviewController;
use App\Http\Controllers\Admin\WishlistController;
use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\InfluencersController;
// use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Admin\HomeEnquiryController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


// Route::get('/clear-all-caches', function () {
//     // Clear the application cache
//     Artisan::call('cache:clear');

//     // Clear the route cache
//     Artisan::call('route:clear');

//     // Clear the configuration cache
//     Artisan::call('config:clear');

//     // Re-cache the configuration
//     Artisan::call('config:cache');

//     // Clear the view cache
//     Artisan::call('view:clear');

//     // Clear the compiled files
//     Artisan::call('optimize:clear');

//     return 'All caches cleared and configuration re-cached!';
// });
// Route::middleware('web')->get('/csrf-token', [AuthController::class, 'getToken']);


Route::prefix('/')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.login');
    Route::get('/sign-up', [AdminController::class, 'signup_form'])->name('admin.signup');

    Route::post('/register', [AdminController::class, 'register'])->name('admin.register');
    Route::post('/do-login', [AdminController::class, 'check_login'])->name('admin.doLogin');
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        // Route::get('/dashboard', [AdminController::class, 'getInclusivePackagesCount'])->name('admin.dashboard');


        //Events
        Route::controller(EventController::class)->group(function () {
            Route::prefix('events')->group(function () {
                Route::get('/', 'list')->name('admin.eventList');
                Route::get('/add', 'add_form')->name('admin.event_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.company_edit_form');
                Route::post('/insert', 'insert')->name('admin.event_insert');
                // Route::post('/{id}/update', 'update')->name('admin.company_update');
                // Route::get('/{id}/view',  'view')->name('admin.company_view');
                // Route::post('/delete', 'delete')->name('admin.company_delete');
                // Route::post('/change-status', 'change_status')->name('admin.company_status');
            });
        });

        //Package
        Route::controller(PackagesController::class)->group(function () {
            Route::prefix('packages')->group(function () {
                Route::get('/', 'list')->name('admin.packageList');
                Route::get('/add', 'add_form')->name('admin.package_add_form');
                // Route::get('/{id}/edit', 'edit_form')->name('admin.package_edit_form');
                // Route::post('/insert', 'insert')->name('admin.event_insert');
                // Route::post('/{id}/update', 'update')->name('admin.company_update');
                // Route::get('/{id}/view',  'view')->name('admin.company_view');
                // Route::post('/delete', 'delete')->name('admin.company_delete');
                // Route::post('/change-status', 'change_status')->name('admin.company_status');
            });
        });

        //All-Inclusive Packages
        Route::controller(All_Inclusive_PackController::class)->group(function () {
            Route::prefix('all-inclusive-package')->group(function () {
                Route::get('/', 'list')->name('admin.inclusive_package_list');
                Route::get('/add', 'add_form')->name('admin.inclusive_package_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.inclusive_package_edit_form');
                Route::post('/insert', 'insert')->name('admin.inclusive_package_insert');
                Route::post('/{id}/update', 'update')->name('admin.inclusive_package_update');
                Route::post('/delete', 'delete')->name('admin.inclusive_package_delete');
                Route::post('/change-status', 'change_status')->name('admin.inclusive_package_status');
                Route::get('/theme-categories/{themeId}', 'getThemeCategories')->name('admin.theme_categories');
                Route::get('/destination-categories',  'getDestinationCategories')->name('admin.destination_categories');

            });
          });
        //   Route::get('/dashboard', [All_Inclusive_PackController::class, 'showDashboard'])->name('dashboard');

         //Program
         Route::controller(ProgramController::class)->group(function () {
            Route::prefix('program')->group(function () {
                Route::get('/', 'list')->name('admin.program_list');
                Route::get('/add', 'add_form')->name('admin.program_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.program_edit_form');
                Route::post('/insert', 'insert')->name('admin.program_insert');
                Route::post('/{id}/update', 'update')->name('admin.program_update');
                Route::post('/delete', 'delete')->name('admin.program_delete');
                Route::post('/change-status', 'change_status')->name('admin.program_status');
            });
        });

        //address
        Route::controller(AddressController::class)->group(function () {
            Route::prefix('address')->group(function () {
                Route::get('/','list')->name('admin.address_list');
                Route::get('/add','add_form')->name('admin.address_add_form');
                Route::get('/{id}/edit','edit_form')->name('admin.address_edit_form');
                Route::post('/insert', 'insert')->name('admin.address_insert');
                Route::post('/{id}/update', 'update')->name('admin.address_update');
                Route::post('/delete', 'delete')->name('admin.address_delete');
                Route::post('/change-status', 'change_status')->name('admin.address_status');
            });
        });

        
        //Property
        // Route::controller(PropertyController::class)->group(function () {
        //     Route::prefix('property')->group(function () {
        //         Route::get('/', 'list')->name('admin.property_list');
        //         Route::get('/add', 'add_form')->name('admin.property_add_form');
        //         Route::get('/{id}/edit', 'edit_form')->name('admin.property_edit_form');
        //         Route::post('/insert', 'insert')->name('admin.property_insert');
        //         Route::post('/{id}/update', 'update')->name('admin.property_update');
        //         Route::post('/delete', 'delete')->name('admin.property_delete');
        //         Route::post('/change-status', 'change_status')->name('admin.property_status');
        //     });
        // });



        //cities
        Route::controller(CityController::class)->group(function () {
            Route::prefix('destination-c')->group(function () {
                Route::get('/', 'list')->name('admin.citylist');
                Route::get('/add', 'add_form')->name('admin.city_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.city_edit_form');
                Route::post('/insert', 'insert')->name('admin.city_insert');
                Route::post('/{id}/update', 'update')->name('admin.city_update');
                Route::post('/delete', 'delete')->name('admin.city_delete');
                Route::post('/change-status', 'change_status')->name('admin.city_status');
            });
        });

        //cities
        Route::controller(DestinationCatController::class)->group(function () {
            Route::prefix('destination-category')->group(function () {
                Route::get('/', 'list')->name('admin.destination_cat_list');
                Route::get('/add', 'add_form')->name('admin.destination_cat_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.destination_cat_edit_form');
                Route::post('/insert', 'insert')->name('admin.destination_cat_insert');
                Route::post('/{id}/update', 'update')->name('admin.destination_cat_update');
                Route::post('/delete', 'delete')->name('admin.destination_cat_delete');
                Route::post('/change-status', 'change_status')->name('admin.destination_cat_status');
            });
        });

        //FAQ
        Route::controller(FAQController::class)->group(function () {
            Route::prefix('faq')->group(function () {
                Route::get('/', 'list')->name('admin.faqlist');
                Route::get('/add', 'add_form')->name('admin.faq_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.faq_edit_form');
                Route::post('/insert', 'insert')->name('admin.faq_insert');
                Route::post('/{id}/update', 'update')->name('admin.faq_update');
                Route::post('/delete', 'delete')->name('admin.faq_delete');
                Route::post('/change-status', 'change_status')->name('admin.faq_status');
            });
        });

         //FAQ
         Route::controller(InfluencersController::class)->group(function () {
            Route::prefix('influencer')->group(function () {
                Route::get('/', 'list')->name('admin.influencer_list');
                Route::get('/add', 'add_form')->name('admin.influencer_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.influencer_edit_form');
                Route::post('/insert', 'insert')->name('admin.influencer_insert');
                Route::post('/{id}/update', 'update')->name('admin.influencer_update');
                Route::post('/delete', 'delete')->name('admin.influencer_delete');
                Route::post('/change-status', 'change_status')->name('admin.influencer_status');
            });
        });
       // web.php
       Route::get('/track-click/{id}', [InfluencersController::class, 'trackClick'])->name('affiliate.link.track');

Route::get('/admin/influencer/{influencerId}/affiliate-links', [InfluencersController::class, 'getAffiliateLinks']);
// routes/web.php

// Route::get('/{program_slug}', [InfluencersController::class, 'showProgramWithReferral']);

    
        //Podcast
        Route::controller(PodcastController::class)->group(function () {
            Route::prefix('podcast')->group(function () {
                Route::get('/', 'list')->name('admin.podcastlist');
                Route::get('/add', 'add_form')->name('admin.podcast_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.podcast_edit_form');
                Route::post('/insert', 'insert')->name('admin.podcast_insert');
                Route::post('/{id}/update', 'update')->name('admin.podcast_update');
                Route::post('/delete', 'delete')->name('admin.podcast_delete');
                Route::post('/change-status', 'change_status')->name('admin.podcast_status');
            });
        });

        //Destination
        Route::controller(DestinationController::class)->group(function () {
            Route::prefix('destination')->group(function () {
                Route::get('/', 'list')->name('admin.destinationlist');
                Route::get('/add', 'add_form')->name('admin.destination_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.destination_edit_form');
                Route::post('/insert', 'insert')->name('admin.destination_insert');
                Route::post('/{id}/update', 'update')->name('admin.destination_update');
                Route::post('/delete', 'delete')->name('admin.destination_delete');
                Route::post('/change-status', 'change_status')->name('admin.destination_status');
            });
        });

        //Amenities
        Route::controller(AmenitiesController::class)->group(function () {
            Route::prefix('amenities')->group(function () {
                Route::get('/', 'list')->name('admin.amenitieslist');
                Route::get('/add', 'add_form')->name('admin.amenities_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.amenities_edit_form');
                Route::post('/insert', 'insert')->name('admin.amenities_insert');
                Route::post('/{id}/update', 'update')->name('admin.amenities_update');
                Route::post('/delete', 'delete')->name('admin.amenities_delete');
                Route::post('/change-status', 'change_status')->name('admin.amenities_status');
            });
        });

        //Food & beverage
        Route::controller(Food_beverageController::class)->group(function () {
            Route::prefix('food_beverage')->group(function () {
                Route::get('/', 'list')->name('admin.food_beveragelist');
                Route::get('/add', 'add_form')->name('admin.food_beverage_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.food_beverage_edit_form');
                Route::post('/insert', 'insert')->name('admin.food_beverage_insert');
                Route::post('/{id}/update', 'update')->name('admin.food_beverage_update');
                Route::post('/delete', 'delete')->name('admin.food_beverage_delete');
                Route::post('/change-status', 'change_status')->name('admin.food_beverage_status');
            });
        });

        //Activities
        Route::controller(ActivitiesController::class)->group(function () {
            Route::prefix('activities')->group(function () {
                Route::get('/', 'list')->name('admin.activitieslist');
                Route::get('/add', 'add_form')->name('admin.activities_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.activities_edit_form');
                Route::post('/insert', 'insert')->name('admin.activities_insert');
                Route::post('/{id}/update', 'update')->name('admin.activities_update');
                Route::post('/delete', 'delete')->name('admin.activities_delete');
                Route::post('/change-status', 'change_status')->name('admin.activities_status');
            });
        });

        //Safety Features
        Route::controller(Safety_featuresController::class)->group(function () {
            Route::prefix('safety_features')->group(function () {
                Route::get('/', 'list')->name('admin.safety_features_list');
                Route::get('/add', 'add_form')->name('admin.safety_features_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.safety_features_edit_form');
                Route::post('/insert', 'insert')->name('admin.safety_features_insert');
                Route::post('/{id}/update', 'update')->name('admin.safety_features_update');
                Route::post('/delete', 'delete')->name('admin.safety_features_delete');
                Route::post('/change-status', 'change_status')->name('admin.safety_features_status');
            });
        });

        //Blog
        Route::controller(BlogController::class)->group(function () {
            Route::prefix('blog')->group(function () {
                Route::get('/', 'list')->name('admin.blog_list');
                Route::get('/add', 'add_form')->name('admin.blog_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.blog_edit_form');
                Route::post('/insert', 'insert')->name('admin.blog_insert');
                Route::post('/{id}/update', 'update')->name('admin.blog_update');
                Route::post('/delete', 'delete')->name('admin.blog_delete');
                Route::post('/change-status', 'change_status')->name('admin.blog_status');
            });
        });

        //geographical features
        Route::controller(Geo_featureController::class)->group(function () {
            Route::prefix('geo_feature')->group(function () {
                Route::get('/', 'list')->name('admin.geo_feature_list');
                Route::get('/add', 'add_form')->name('admin.geo_feature_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.geo_feature_edit_form');
                Route::post('/insert', 'insert')->name('admin.geo_feature_insert');
                Route::post('/{id}/update', 'update')->name('admin.geo_feature_update');
                Route::post('/delete', 'delete')->name('admin.geo_feature_delete');
                Route::post('/change-status', 'change_status')->name('admin.geo_feature_status');
            });
        });

         //themes
         Route::controller(ThemesController::class)->group(function () {
            Route::prefix('themes')->group(function () {
                Route::get('/', 'list')->name('admin.themes_list');
                Route::get('/add', 'add_form')->name('admin.themes_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.themes_edit_form');
                Route::post('/insert', 'insert')->name('admin.themes_insert');
                Route::post('/{id}/update', 'update')->name('admin.themes_update');
                Route::post('/delete', 'delete')->name('admin.themes_delete');
                Route::post('/change-status', 'change_status')->name('admin.themes_status');
            });
        });

        //themes category
        Route::controller(ThemesCatController::class)->group(function () {
            Route::prefix('themes-category')->group(function () {
                Route::get('/', 'list')->name('admin.themes_cat_list');
                Route::get('/add', 'add_form')->name('admin.themes_cat_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.themes_cat_edit_form');
                Route::post('/insert', 'insert')->name('admin.themes_cat_insert');
                Route::post('/{id}/update', 'update')->name('admin.themes_cat_update');
                Route::post('/delete', 'delete')->name('admin.themes_cat_delete');
                Route::post('/change-status', 'change_status')->name('admin.themes_cat_status');
            });
        });

         //profile
         Route::controller(ProfileController::class)->group(function () {
            Route::prefix('profile')->group(function () {
                Route::get('/', 'list')->name('admin.profile_list');
                Route::get('/add', 'add_form')->name('admin.profile_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.profile_edit_form');
                Route::post('/insert', 'insert')->name('admin.profile_insert');
                Route::post('/{id}/update', 'update')->name('admin.profile_update');
                Route::post('/delete', 'delete')->name('admin.profile_delete');
                Route::post('/change-status', 'change_status')->name('admin.profile_status');
            });
        });

         //User Details
         Route::controller(UserController::class)->group(function () {
            Route::prefix('user')->group(function () {
                Route::get('/', 'list')->name('admin.user_list');
                Route::get('/add', 'add_form')->name('admin.user_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.user_edit_form');
                Route::post('/insert', 'insert')->name('admin.user_insert');
                Route::post('/{id}/update', 'update')->name('admin.user_update');
                Route::post('/delete', 'delete')->name('admin.user_delete');
                Route::post('/change-status', 'change_status')->name('admin.user_status');
            });
        });

         //Contat-us Details
         Route::controller(Contact_usController::class)->group(function () {
            Route::prefix('contact-us')->group(function () {
                Route::get('/', 'list')->name('admin.contact_list');
            });
        });

        //Enquiry Details
        Route::controller(EnquiryController::class)->group(function () {
            Route::prefix('enquiry')->group(function () {
                Route::get('/', 'list')->name('admin.enquiry_list');
            });
        });
        Route::post('/enquiry/followup', [EnquiryController::class, 'markFollowUp']);

        Route::get('admin/enquiry/followups/{id}', [EnquiryController::class, 'viewFollowUps'])->name('admin.enquiry.followups');
        Route::post('/admin/enquiry/{id}/add-follow-up', [EnquiryController::class, 'addFollowUp'])->name('admin.enquiry.addFollowUp');
        Route::get('/admin/enquiry/{id}/form', [EnquiryController::class, 'showEnquiryForm'])->name('admin.enquiry.form');

        //Home Enquiry Details
        Route::controller(HomeEnquiryController::class)->group(function () {
            Route::prefix('home-enquiry')->group(function () {
                Route::get('/', 'list')->name('admin.home_enquiry_list');
            });
        });

        Route::post('/mark-followup/{id}', [HomeEnquiryController::class, 'markFollowUp'])->name('mark.followup');

        //settings features
        Route::controller(SettingController::class)->group(function () {
            Route::prefix('settings')->group(function () {
                Route::get('/', 'list')->name('admin.settings_list');
                Route::post('/insert', 'insert')->name('admin.settings_insert');
            });
        });

        //Slider Details
        Route::controller(SliderController::class)->group(function () {
            Route::prefix('slider')->group(function () {
                Route::get('/', 'list')->name('admin.slider_list');
                Route::get('/add', 'add_form')->name('admin.slider_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.slider_edit_form');
                Route::post('/insert', 'insert')->name('admin.slider_insert');
                Route::post('/{id}/update', 'update')->name('admin.slider_update');
                Route::post('/delete', 'delete')->name('admin.slider_delete');
                Route::post('/change-status', 'change_status')->name('admin.slider_status');
            });
        });

        //Group booking
        Route::controller(GroupTourController::class)->group(function () {
            Route::prefix('group_tour')->group(function () {
                Route::get('/', 'list')->name('admin.group_tour_list');
                Route::get('/add', 'add_form')->name('admin.group_tour_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.group_tour_edit_form');
                Route::post('/insert', 'insert')->name('admin.group_tour_insert');
                Route::post('/{id}/update', 'update')->name('admin.group_tour_update');
                Route::post('/delete', 'delete')->name('admin.group_tour_delete');
                Route::post('/change-status', 'change_status')->name('admin.group_tour_status');
            });
        });

        //client Review
        Route::controller(ClientreviewController::class)->group(function () {
            Route::prefix('client_review')->group(function () {
                Route::get('/', 'list')->name('admin.client_review_list');
                Route::get('/review_list' , 'review_list')->name('admin.review_review_list');
                Route::get('/add', 'add_form')->name('admin.client_review_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.client_review_edit_form');
                Route::post('/insert', 'insert')->name('admin.client_review_insert');
                Route::post('/{id}/update', 'update')->name('admin.client_review_update');
                Route::post('/delete', 'delete')->name('admin.client_review_delete');
                Route::post('/change-status', 'change_status')->name('admin.client_review_status');
                Route::post('/review_delete', 'review_delete')->name('admin.review_delete');
            });
        });

        //client Review
        Route::controller(WishlistController::class)->group(function () {
            Route::prefix('wish-list')->group(function () {
                Route::get('/', 'list')->name('admin.wish_list');
                Route::get('/add', 'add_form')->name('admin.wishlist_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.wishlist_edit_form');
                Route::post('/insert', 'insert')->name('admin.wishlist_insert');
                Route::post('/{id}/update', 'update')->name('admin.wishlist_update');
                Route::post('/delete', 'delete')->name('admin.wishlist_delete');
                Route::post('/change-status', 'change_status')->name('admin.wishlist_status');
            });
        });
        

       
    });
});


