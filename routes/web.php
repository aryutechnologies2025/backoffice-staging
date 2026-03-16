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
use App\Http\Controllers\Admin\AssitanceFormController;
use App\Http\Controllers\Admin\CustomerPackage as AdminCustomerPackage;
use App\Http\Controllers\Admin\FacebookController;
use App\Http\Controllers\Admin\InfluencersController;
// use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Admin\HomeEnquiryController;
use App\Http\Controllers\Admin\PdfController;
use App\Http\Controllers\Admin\CustomerPackage;
use App\Http\Controllers\Admin\StayController;
use App\Http\Controllers\Admin\StayDestinationController;
use App\Http\Controllers\Admin\StayDistrictController;
use App\Http\Controllers\Admin\StayPriceController;
use App\Http\Controllers\Admin\CabController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\PricingCalculatorController;
use App\Http\Controllers\Admin\StayreviewController;
use App\Http\Controllers\Admin\ProgramEventsController;
use App\Http\Controllers\Admin\EventRegisterController;
use App\Http\Controllers\Admin\MailTemplateController;

use App\Models\stay_desitination;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\GoogleAnalyticsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\UserPermissionController;

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

Route::get('/update', function () {
    // return view('welcome');
    phpinfo();
});


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
    Route::get('auth/facebook', [FacebookController::class, 'redirectToFacebook']);
    Route::get('auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);
    Route::middleware(['auth:admin', 'admin.status'])->group(function () {
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
                Route::get('/{id}/edit', 'edit_form')->name('admin.package_edit_form');
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
                Route::post('/duplicate-entry-details', 'duplicatePackage')->name('admin.ProgramPackage_dupdetails');
                Route::post('/update-latest', 'updateNew')->name('admin.inclusive_package_update_latest');
            });
        });
        //   Route::get('/dashboard', [All_Inclusive_PackController::class, 'showDashboard'])->name('dashboard');

        //CustomerPackage
        Route::controller(CustomerPackage::class)->group(function () {
            Route::prefix('customer-package')->group(function () {
                Route::get('/', 'list')->name('admin.CustomerPackage_list');
                Route::get('/add', 'add_form')->name('admin.CustomerPackage_form');
                // Route::get('/get-packages-by-city/{city_id}','getPackagesByCity');
                Route::post('/get-packages-by-city', 'getPackagesByCity');

                Route::post('/insert', 'insert')->name('admin.CustomerPackage_insert');
                Route::post('/delete', 'delete')->name('admin.CustomerPackage_delete');
                Route::post('/change-status', 'change_status')->name('admin.CustomerPackage_status');

                Route::get('/{id}/edit', 'edit_form')->name('admin.CustomerPackage_edit_form');
                Route::post('/{id}/update', 'update')->name('admin.CustomerPackage_update');


                Route::post('/package-details', 'package_details')->name('admin.CustomerPackage_details');
                Route::post('/packageStay-details', 'getCustomerStay')->name('admin.CustomerPackageStay_details');
                Route::post('/duplicate-entry-details', 'duplicatePackage')->name('admin.CustomerPackage_dupdetails');
                Route::post('/pricing-details', 'pricing_details')->name('admin.c_pricing_details');
                Route::post('/c_stay-details', 'stay_details')->name('admin.c_stay_details');
                Route::post('/c_activity-details', 'activity_details')->name('admin.c_activity_details');
                Route::post('/c_travel-details', 'travel_details')->name('admin.c_travel_details');
                Route::post('/c_cabs-details', 'cabs_details')->name('admin.c_cabs_details');
                Route::post('/edit-pricing-details', 'edit_pricing_details')->name('admin.edit_pricing_details');
                //Edit stay

                Route::post('/stay-details', 'edit_stay_details')->name('admin.ec_stay_details');
                Route::post('/activity-details', 'edit_activity_details')->name('admin.ec_activity_details');
                Route::post('/cabs-details', 'edit_cabs_details')->name('admin.ec_cabs_details');
                Route::post('/delete-customer-details', 'delete_customer_details')->name('admin.delete_customer_details');
                Route::post('/update-customer-tourplan', 'updatecustomertourplan')->name('admin.updatecustomertourplan');
                Route::post('/delete-customer-tourplan', 'deletecustomertourplan')->name('admin.deletecustomertourplan');
            });
        });

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

        Route::controller(PdfController::class)->group(function () {
            Route::prefix('pdf')->group(function () {
                Route::get('/', 'index')->name('admin.program_pdf_list');
                Route::get('/add', 'add_form')->name('admin.program_pdf_add_form');
                Route::post('/insert', 'store')->name('admin.program_pdf_insert');
                Route::get('/{id}/edit', 'edit')->name('admin.program_pdf_edit_form');
                Route::match(['get', 'post'], '/{id}/update', 'update')->name('admin.program_pdf_updates');
                Route::post('/delete/{id}', 'destroy')->name('admin.program_pdf_delete');
            });
        });



        //address
        Route::controller(AddressController::class)->group(function () {
            Route::prefix('address')->group(function () {
                Route::get('/', 'list')->name('admin.address_list');
                Route::get('/add', 'add_form')->name('admin.address_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.address_edit_form');
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
                Route::get('/{id}/view', 'view_form')->name('admin.influencer_view');
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
        // Route::controller(UserController::class)->group(function () {
        //     Route::prefix('user')->group(function () {
        //         Route::get('/', 'list')->name('admin.user_list')->middleware('check.permission:User,list');
        //         Route::get('/add', 'add_form')->name('admin.user_add_form')->middleware('check.permission:User,add');
        //         Route::get('/{id}/edit', 'edit_form')->name('admin.user_edit_form');
        //         Route::get('/{id}/view', 'view_form')->name('admin.user_view_form');
        //         Route::post('/insert', 'insert')->name('admin.user_insert');
        //         Route::post('/{id}/update', 'update')->name('admin.user_update');
        //         Route::post('/delete', 'delete')->name('admin.user_delete');
        //         Route::post('/change-status', 'change_status')->name('admin.user_status');
        //     });
        // });
        Route::controller(UserController::class)->group(function () {
        Route::prefix('user')->group(function () {
        Route::get('/', 'list')->name('admin.user_list')->middleware('check.permission:User,list');
        Route::get('/add', 'add_form')->name('admin.user_add_form')->middleware('check.permission:User,add');
        Route::get('/{id}/edit', 'edit_form')->name('admin.user_edit_form')->middleware('check.permission:User,edit');
        Route::get('/{id}/view', 'view_form')->name('admin.user_view_form')->middleware('check.permission:User,view');
        Route::post('/insert', 'insert')->name('admin.user_insert')->middleware('check.permission:User,add');
        Route::post('/{id}/update', 'update')->name('admin.user_update')->middleware('check.permission:User,edit');
        Route::post('/delete', 'delete')->name('admin.user_delete')->middleware('check.permission:User,delete');
        Route::post('/change-status', 'change_status')->name('admin.user_status')->middleware('check.permission:User,status');
    });
});

        //Contat-us Details
        Route::controller(Contact_usController::class)->group(function () {
            Route::prefix('contact-us')->group(function () {
                Route::get('/', 'list')->name('admin.contact_list');
                Route::post('/delete', 'delete')->name('admin.contact_delete');
            });
        });



        Route::controller(AssitanceFormController::class)->group(function () {
            Route::prefix('assistance-form')->group(function () {
                Route::get('/', 'list')->name('admin.assistance_form_list');
                Route::post('/delete', 'delete')->name('admin.assistance_delete');
            });
        });

        //Enquiry Details
        Route::controller(EnquiryController::class)->group(function () {
            Route::prefix('enquiry')->group(function () {
                Route::get('/', 'list')->name('admin.enquiry_list');
                Route::get('/download-all', 'downloadAll')->name('admin.downloadAll');
                Route::post('/store', 'insert')->name('admin.enquiry_store');
                Route::get('/add', 'add_form')->name('admin.enquiry_add_form');
                Route::get('/{id}/view', 'view_form')->name('admin.enquiry_view');
                Route::post('/delete', 'delete')->name('admin.enquiry_delete');
                Route::post('/status-change', 'change_status')->name('admin.followupstatuschange');
                Route::post('/mailtemplate', 'mailtemplate')->name('admin.mailtemplate');
            });
        });
        Route::post('/enquiry/followup', [EnquiryController::class, 'markFollowUp']);

        Route::get('admin/home-enquiry/followups/{id}', [EnquiryController::class, 'viewFollowUps'])->name('admin.enquiry.followups');
        Route::post('/admin/home-enquiry/{id}/add-follow-up', [EnquiryController::class, 'addFollowUp'])->name('admin.enquiry.addFollowUp');
        Route::get('/admin/enquiry/{id}/form', [EnquiryController::class, 'showEnquiryForm'])->name('admin.enquiry.form');



        //enquiryFollowup 
        Route::get('admin/enquiry/followups/{id}', [EnquiryController::class, 'viewEnquiryFollowUps'])->name('admin.enquiry.enquiryfollowups');
        Route::post('/admin/enquiry/{id}/add-follow-up', [EnquiryController::class, 'addEnquiryFollowUp'])->name('admin.enquiry.enquiryaddFollowUp');
        Route::get('/admin/enquiry/{id}/form', [EnquiryController::class, 'showEnquiryFollowUpForm'])->name('admin.enquiry.enquiryform');



        //Home Enquiry Details
        Route::controller(HomeEnquiryController::class)->group(function () {
            Route::prefix('home-enquiry')->group(function () {
                Route::get('/', 'list')->name('admin.home_enquiry_list');
                Route::get('/download-enquiry-all', 'downloadAll')->name('admin.download-enquiry-all');
                Route::get('/download-stay-all', 'downloadStayAll')->name('admin.downloadStayAll');
                Route::get('/add', 'add_form')->name('admin.home_enquiry_add_form');
                Route::post('/insert/enquiry', 'insert')->name('admin.home_enquiry_store_form');
                Route::get('/stay', 'stayList')->name('admin.stay_home_enquiry_list');
                Route::get('/{id}/view', 'view_form')->name('admin.home_enquiry_view');
                Route::post('/delete', 'delete')->name('admin.home_enquiry_delete');

                Route::get('/{id}/stay_view', 'stay_view_form')->name('admin.stay_enquiry_view');
                Route::post('/stay-delete', 'staydelete')->name('admin.stay_enquiry_delete');
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
                Route::get('/review_list', 'review_list')->name('admin.review_review_list');
                Route::get('/add', 'add_form')->name('admin.client_review_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.client_review_edit_form');
                Route::post('/insert', 'insert')->name('admin.client_review_insert');
                Route::post('/{id}/update', 'update')->name('admin.client_review_update');
                Route::post('/delete', 'delete')->name('admin.client_review_delete');
                Route::post('/change-status', 'change_status')->name('admin.client_review_status');
                Route::post('/review_delete', 'review_delete')->name('admin.review_delete');
            });
        });


        //stay Review
        Route::controller(StayreviewController::class)->group(function () {
            Route::prefix('stay_review')->group(function () {
                Route::get('/', 'list')->name('admin.stay_review_list');
                Route::get('/add', 'add_form')->name('admin.stay_review_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.stay_review_edit_form');
                Route::post('/insert', 'insert')->name('admin.stay_review_insert');
                Route::post('/{id}/update', 'update')->name('admin.stay_review_update');
                Route::post('/delete', 'delete')->name('admin.stay_review_delete');
                Route::post('/change-status', 'change_status')->name('admin.stay_review_status');
                Route::post('/review_delete', 'review_delete')->name('admin.stay_review_delete');
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

        //stays module
        Route::controller(StayController::class)->group(function () {
            Route::prefix('stay_list')->group(function () {
                Route::get('/', 'list')->name('admin.staylist');
                Route::get('/add', 'add_form')->name('admin.stays_add_form');
                Route::post('/insert', 'insert')->name('admin.stay_details_insert');
                Route::post('/delete', 'delete')->name('admin.stay_details_delete');
                Route::get('/{id}/edit', 'edit_form')->name('admin.stay_details_edit_form');
                Route::post('/{id}/update', 'update')->name('admin.stay_details_update');
                Route::post('/change-status', 'change_status')->name('admin.stay_change_status');
                Route::get('/get-districts-list', [StayController::class, 'getDistrictsList'])
                    ->name('stay_list.getdistrictslist');
            });
        });

        Route::get('/get-districts/{destination}', [StayController::class, 'getDistricts'])
            ->name('get-districts');
        Route::get('/get-multi-districts', [StayController::class, 'getMultiDistricts'])
            ->name('get-multi-districts');
        Route::get('/get-single-districts', [StayController::class, 'getSingleDistricts'])
            ->name('get-single-districts');
        Route::get('/get-districts-name/{destination}', [StayController::class, 'getDistricts_name'])
            ->name('get-name-districts');
        Route::post('/get-districts-list', [StayController::class, 'getUpdateDistricts'])->name('get.districts.list');

        Route::get('/get-districts-program/{destination}', [StayController::class, 'getDistrictsProgram'])
            ->name('get-districts-program');

        Route::controller(StayDestinationController::class)->group(function () {
            Route::prefix('staydestination')->group(function () {
                Route::get('/', 'list')->name('admin.staydestinationlist');
                Route::get('/add', 'add_form')->name('admin.staydestination_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.staydestination_edit_form');
                Route::post('/insert', 'insert')->name('admin.staydestination_insert');
                Route::post('/{id}/update', 'update')->name('admin.staydestination_update');
                Route::post('/delete', 'delete')->name('admin.staydestination_delete');
                Route::post('/change-status', 'change_status')->name('admin.staydestination_status');
            });
        });

        Route::controller(StayDistrictController::class)->group(function () {
            Route::prefix('staydistrict')->group(function () {
                Route::get('/', 'list')->name('admin.staydistrictlist');
                Route::get('/add', 'add_form')->name('admin.staydistrict_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.staydistrict_edit_form');
                Route::post('/insert', 'store')->name('admin.staydistricts_insert');
                Route::post('/{id}/update', 'update')->name('admin.staydistrict_update');
                Route::post('/delete', 'delete')->name('admin.staydistrict_delete');
                Route::post('/change-status', 'change_status')->name('admin.staydistrict_change_status');
            });
        });

        //stay pricing

        Route::controller(StayPriceController::class)->group(function () {
            Route::prefix('staypricing')->group(function () {
                Route::get('/', 'list')->name('admin.staypricinglist');
                Route::get('/add', 'add_form')->name('admin.staypricing_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.staypricing_edit_form');
                Route::post('/insert', 'insert')->name('admin.staypricing_insert');
                Route::post('/{id}/update', 'update')->name('admin.staypricing_update');
                Route::post('/delete', 'delete')->name('admin.staypricingdelete');
                Route::post('/change-status', 'change_status')->name('admin.staypricing_change_status');
            });
        });

        //cab

        Route::controller(CabController::class)->group(function () {
            Route::prefix('cab')->group(function () {
                Route::get('/', 'list')->name('admin.cablist');
                Route::get('/add', 'add_form')->name('admin.cab_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.cab_edit_form');
                Route::post('/insert', 'insert')->name('admin.cab_insert');
                Route::post('/{id}/update', 'update')->name('admin.cab_update');
                Route::post('/delete', 'delete')->name('admin.cabdelete');
                Route::post('/change-status', 'change_status')->name('admin.cab_change_status');
            });
        });

        //activity
        Route::controller(ActivityController::class)->group(function () {
            Route::prefix('activity')->group(function () {
                Route::get('/', 'list')->name('admin.activitylist');
                Route::get('/add', 'add_form')->name('admin.activity_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.activity_edit_form');
                Route::post('/insert', 'insert')->name('admin.activity_insert');
                Route::post('/{id}/update', 'update')->name('admin.activity_update');
                Route::post('/delete', 'delete')->name('admin.activitydelete');
                Route::post('/change-status', 'change_status')->name('admin.activity_change_status');
            });
        });
        //pricing calculator
        Route::controller(PricingCalculatorController::class)->group(function () {
            Route::prefix('pricingcalculator')->group(function () {
                Route::get('/', 'list')->name('admin.pricinglist');
                Route::get('/add', 'add_form')->name('admin.pricing_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.pricing_edit_form');
                Route::post('/insert', 'insert')->name('admin.pricing_insert');
                Route::post('/{id}/update', 'update')->name('admin.pricing_update');
                Route::post('/delete', 'delete')->name('admin.pricingdelete');
                Route::post('/change-status', 'change_status')->name('admin.pricing_change_status');
                Route::post('/pricing-details', 'pricing_details')->name('admin.pricing_details');
                Route::post('/travel-details', 'travel_details')->name('admin.travel_details');
                Route::post('/stay-details', 'stay_details')->name('admin.stay_details');
                Route::post('/stay-edit-details', 'stay_edit_details')->name('admin.stay_edit_details');
                Route::post('/activity-details', 'activity_details')->name('admin.activity_details');
                Route::post('/cabs-details', 'cabs_details')->name('admin.cabs_details');
            });
        });

        //Event Modules
        Route::controller(ProgramEventsController::class)->group(function () {
            Route::prefix('program-events')->group(function () {
                Route::get('/', 'list')->name('admin.programeventslist');
                Route::get('/add', 'add')->name('admin.programeventsadd');
                Route::post('/insert', 'insert')->name('admin.programeventstore');
                Route::get('/{id}/edit', 'edit')->name('admin.programeventedit');
                Route::post('/{id}/update', 'update')->name('admin.programeventupdate');
                Route::post('/delete', 'delete')->name('admin.programeventdelete');
                Route::post('/change-status', 'change_status')->name('admin.programeventstatus');
            });
        });

        //Event Registration

        Route::controller(EventRegisterController::class)->group(function () {
            Route::prefix('events-register')->group(function () {
                Route::get('/', 'list')->name('admin.registereventslist');
                Route::get('/{id}/view', 'view_form')->name('admin.registereventsview');
                Route::post('/delete', 'delete')->name('admin.registereventdelete');
                Route::post('/change-status', 'change_status')->name('admin.registereventstatus');
                Route::post('/update-event-notes', 'updateEventNotes')->name('admin.updateEventNotes');
            });
        });


        //Mail Template
        Route::controller(MailTemplateController::class)->group(function () {
            Route::prefix('mail-template')->group(function () {
                Route::get('/', 'list')->name('admin.mailtemplatelist');
                Route::get('/add', 'add')->name('admin.mailtemplateadd');
                Route::post('/insert', 'insert')->name('admin.mailtemplatestore');
                Route::get('/{id}/edit', 'edit')->name('admin.mailtemplateedit');
                Route::post('/{id}/update', 'update')->name('admin.mailtemplateupdate');
                Route::post('/delete', 'delete')->name('admin.mailtemplatedelete');
                Route::post('/change-status', 'change_status')->name('admin.mailtemplatestatus');
            });
        });

        //google analytics
        Route::controller(GoogleAnalyticsController::class)->group(function () {
            Route::prefix('analytics')->group(function () {
                Route::get('/', [GoogleAnalyticsController::class, 'getindex'])->name('analytics.index');
                Route::get('/channels', [GoogleAnalyticsController::class, 'getChannels'])->name('analytics.channels');
                Route::get('/sources', [GoogleAnalyticsController::class, 'getSources'])->name('analytics.sources');
                Route::get('/top-pages', [GoogleAnalyticsController::class, 'getTopPages'])->name('analytics.top-pages');
                Route::get('/entry-pages', [GoogleAnalyticsController::class, 'getEntryPages'])->name('analytics.entry-pages');
                Route::get('/exit-pages', [GoogleAnalyticsController::class, 'getExitPages'])->name('analytics.exit-pages');
                Route::get('/browser', [GoogleAnalyticsController::class, 'getBrowserData'])->name('analytics.browser');
                Route::get('/os', [GoogleAnalyticsController::class, 'getOSData'])->name('analytics.os');
                Route::get('/size', [GoogleAnalyticsController::class, 'getDeviceSizeData'])->name('analytics.size');
                Route::get('/channels-detailed', [GoogleAnalyticsController::class, 'getChanneldetails'])->name('analytics.channels-detailed');
                Route::get('/pages-detailed', [GoogleAnalyticsController::class, 'getPagedetails'])->name('analytics.pages-detailed');
                Route::get('/devices-detailed', [GoogleAnalyticsController::class, 'getDevicedetails'])->name('analytics.devices-detailed');
                Route::get('/chart-data', [GoogleAnalyticsController::class, 'getChartDataApi'])->name('analytics.chart-data');
                Route::get('/map-data', [GoogleAnalyticsController::class, 'getMapData'])->name('analytics.map-data');
                Route::get('/countries-data', [GoogleAnalyticsController::class, 'getCountriesData'])->name('analytics.countries-data');
                Route::get('/regions-data', [GoogleAnalyticsController::class, 'getRegionsData'])->name('analytics.regions-data');
                Route::get('/cities-data', [GoogleAnalyticsController::class, 'getCitiesData'])->name('analytics.cities-data');
                Route::get('/locations-detailed', [GoogleAnalyticsController::class, 'getLocationdetails'])->name('analytics.locations-detailed');
                Route::get('/website-stats', [GoogleAnalyticsController::class, 'getWebsiteStats'])->name('analytics.website-stats');
                Route::get('/realtime-activity', [GoogleAnalyticsController::class, 'getRealtimeActivity'])->name('analytics.realtime-activity');
            });
        });
        
        //role
        Route::controller(RoleController::class)->group(function () {
            Route::prefix('role')->group(function () {
                Route::get('/', 'list')->name('admin.role_list');
                Route::get('/add', 'add_form')->name('admin.role_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.role_edit_form');
                Route::post('/insert', 'insert')->name('admin.role_insert');
                Route::post('/{id}/update', 'update')->name('admin.role_update');
                Route::post('/delete', 'delete')->name('admin.role_delete');
                Route::post('/change-status', 'change_status')->name('admin.role_status');
            });
        });

        Route::controller(AdminUserController::class)->group(function () {
            Route::prefix('adminuser')->group(function () {
                Route::get('/', 'list')->name('admin.admin_user_list');
                Route::get('/add', 'add_form')->name('admin.admin_user_add_form');
                Route::get('/{id}/edit', 'edit_form')->name('admin.admin_user_edit_form');
                Route::post('/insert', 'insert')->name('admin.admin_user_insert');
                Route::post('/{id}/update', 'update')->name('admin.admin_user_update');
                Route::post('/delete', 'delete')->name('admin.admin_user_delete');
                Route::post('/change-status', 'change_status')->name('admin.admin_user_status');
                Route::post('/reset-password', 'reset_password')->name('admin.reset_user_password');
            });
        });

         Route::controller(UserPermissionController::class)->group(function () {
            Route::prefix('permission')->group(function () {
                Route::get('/', 'list')->name('admin.user_permission_list');
                Route::get('/add', 'add')->name('admin.user_permission_add_form');
                Route::post('/insert', 'insert')->name('admin.user_permission_insert');
                Route::get('/{id}/edit', 'edit')->name('admin.user_permission_edit_form');
                Route::post('/{id}/update', 'update')->name('admin.user_permission_user_update');
                Route::post('/delete', 'delete')->name('admin.user_permission_delete');
                Route::post('/change-status', 'change_status')->name('admin.user_permission_status');
            });
        });
    });
});
