<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\customer_package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerPackageNotification;
use App\Models\Activities;
use App\Models\Amenities;
use App\Models\City;
use App\Models\Destination_category;
use App\Models\FoodBeverage;
use App\Models\Geo_feature;
use App\Models\InclusivePackages;
use App\Models\Safetyfeatures;
use App\Models\Themes;
use App\Models\Themes_category;
use App\Models\stays_destination_details;
use App\Models\stay_desitination;
use App\Models\PricingCalculator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\StayPricing;
use App\Models\Cab;
use App\Models\ActivityP;
use App\Models\PriceCalculatorList;
use App\Models\CustomerPricingCalculator;
use App\Models\CustomerPriceCalculatorList;
use App\Models\stay_district;
use App\Models\CustomerTourPlanning;
use Illuminate\Support\Facades\Validator;


class CustomerPackage extends Controller
{
    public function list(Request $request)
    {
        $title = 'Customer Package List';
        $customer_package_list = customer_package::where('is_deleted', '0')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.customer_package.customerpackagelist', compact('title', 'customer_package_list'));
    }

    public function add_form(Request $request)
    {
        $title = 'Add Customer Package';
        $cities = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        $themes = Themes::where('status', "1")->where('is_deleted', "0")->pluck('themes_name', 'id');
        $amenities = Amenities::where('status', "1")->where('is_deleted', "0")->get();
        $foodBeverages = FoodBeverage::where('status', "1")->where('is_deleted', "0")->get();
        $activities = Activities::where('status', "1")->where('is_deleted', "0")->get();
        $safety_features = Safetyfeatures::where('status', "1")->where('is_deleted', "0")->get();


        //     return view('admin.inclusive_packages.inclusive_packagesadd', compact('title', 'cities', 'themes', 'amenities', 'foodBeverages', 'activities', 'safety_features'));
        // }


        $titles = DB::table('inclusive_package_details')->where('is_deleted', '0')->where('status', '1')->pluck('title', 'id');

        return view('admin.customer_package.customerpackageadd', compact(
            'title',
            'titles',
            'cities',
            'themes',
            'amenities',
            'foodBeverages',
            'activities',
            'safety_features'
        ));
    }


    public function insert(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'tour_planning' => 'required|array',
            'tour_planning.*.title' => 'required|string',
            'tour_planning.*.description' => 'required|string',
        ]);
        $customer_package = new customer_package();
        $customer_package->name = ucfirst($request->name);
        $customer_package->phone_number = $request->phone_number;
        $customer_package->email = $request->email;
        $packageData = json_decode($request->package_type, true);
        $customer_package->package_id = $packageData['id'];
        $customer_package->package_type = $request->title;


        // $customer_package->important_info = json_encode($request->input('important_info'));
        $customer_package->important_info = $request->input('important_info');
        $customer_package->stay_details_id = $request->input('package_stay');
        $customer_package->package_inclusion = json_encode($request->input('program_inclusion'));
        $customer_package->package_exclusion = json_encode($request->input('program_exclusion'));

        $amenitiesJson = json_encode($request->input('amenity_services', []));
        $foodBeveragesJson = json_encode($request->input('food_beverages', []));
        $activitiesJson = json_encode($request->input('activities', []));
        $safetyFeaturesJson = json_encode($request->input('safety_features', []));
        $campRulesJson = json_encode($request->input('camp_rule'));

        $customer_package->camp_rule = $campRulesJson;

        $customer_package->price_title = json_encode($request->input('price_title', []));
        $customer_package->price_amount = json_encode($request->input('price_amount', []));
        $customer_package->amenities = $amenitiesJson;
        $customer_package->food_beverages = $foodBeveragesJson;
        $customer_package->activities = $activitiesJson;
        $customer_package->safety_features = $safetyFeaturesJson;
        $customer_package->list_order = $request->input('list_order');

        $customer_package->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';

        $customer_package->location = json_encode($request->input('location'));
        $customer_package->save();

        if ($request->filled('tour_planning')) {
            $tourPlanningData = $request->tour_planning;

            foreach ($tourPlanningData as $index => $day) {
                CustomerTourPlanning::create([
                    'customer_id' => $customer_package->id,
                    'package_id' => $packageData['id'],
                    'day_title' => $day['title'] ?? null,
                    'day_subtitle' => $day['subtitle'] ?? null,
                    'activity_description' => $day['description'] ?? null,
                    'day_order' => $index
                ]);
            }
        }
        // Mail::to($customer_package->email) // or use a different recipient
        // ->send(new CustomerPackageNotification([
        //     'name'=> $customer_package->name,
        //     'phone_number'=> $customer_package->phone_number,
        //     'email'=> $customer_package->email,
        //     'package_type'=> $customer_package->package_type,
        //     'package_id'=> $customer_package->package_id
        // ]));

        //pricing calculator update
        if ($request->has('pricing_calculator') && !empty($request->pricing_calculator)) {

            $pricingcalculator_v = new CustomerPricingCalculator();

            $pricingcalculator_v->pricing_calculator_id = $request->pricing_calculator;
            // $pricingcalculator_v->title = $request->title;
            $pricingcalculator_v->package_id = $packageData['id'];
            $pricingcalculator_v->customer_package_id = $customer_package->id;
            $pricingcalculator_v->stays_id = $request->stay_id;
            $pricingcalculator_v->activitys_id = $request->activity_ids;
            $pricingcalculator_v->cab_details_id = $request->selected_cab_options;
            $pricingcalculator_v->cab_type = $request->cab_types;
            $pricingcalculator_v->save();


            if (isset($request->stays) && count($request->stays) > 0) {

                $stays = $request->stays;
                foreach ($stays as $val) {

                    foreach ($val as $v) {
                        //  dd($v['stay_id']);
                        $pricingcalculator = new CustomerPriceCalculatorList();
                        $pricingcalculator->customer_pricing_id = $pricingcalculator_v->id;
                        $pricingcalculator->type = 'stay';
                        $pricingcalculator->type_id = $v['stay_id'];
                        $pricingcalculator->title = $v['title'];
                        $pricingcalculator->price_title = $v['price_title'];
                        $pricingcalculator->price = $v['price'];
                        $pricingcalculator->save();
                    }
                }
            }

            if (isset($request->activity) && count($request->activity) > 0) {

                $stays = $request->activity;

                foreach ($stays as $val) {
                    foreach ($val as $v) {

                        // dd($v);
                        $pricingcalculator = new CustomerPriceCalculatorList();
                        $pricingcalculator->customer_pricing_id = $pricingcalculator_v->id;
                        $pricingcalculator->type = 'activity';
                        $pricingcalculator->type_id = $v['activity_id'];
                        $pricingcalculator->title = $v['title'];
                        $pricingcalculator->price_title = $v['price_title'];
                        $pricingcalculator->price = $v['price'];
                        $pricingcalculator->save();
                    }
                }
            }

            if (isset($request->cabs) && count($request->cabs) > 0) {

                $stays = $request->cabs;

                foreach ($stays as $val) {
                    foreach ($val as $v) {
                        $pricingcalculator = new CustomerPriceCalculatorList();
                        $pricingcalculator->customer_pricing_id = $pricingcalculator_v->id;
                        $pricingcalculator->type = 'cabs';
                        $pricingcalculator->type_id = $v['cab_id'];
                        $pricingcalculator->title = $v['title'];
                        $pricingcalculator->price_title = $v['price_title'];
                        $pricingcalculator->price = $v['price'];
                        $pricingcalculator->save();
                    }
                }
            }
        }


        return redirect()->route('admin.CustomerPackage_list')
            ->with('success', 'customer inserted successfully');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $customer_package = customer_package::find($record_id);

        if ($customer_package) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $customer_package->status = "0";
            } else {
                $customer_package->status = "1";
            }

            $customer_package->save();

            $response = [
                'status' => '1',
                'response' => 'Customer status changed successfully.'
            ];
        } else {
            // Record not found
            $response = [
                'status' => '0',
                'response' => 'Record not found.'
            ];
        }

        // Return the response as JSON
        return response()->json($response);
    }

    public function delete(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');

        // Find the admin record by ID
        $program = customer_package::find($record_id);
        if ($program) {
            // Update the is_deleted field to 1
            $program->is_deleted = "1";
            $program->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Record marked as deleted successfully.'
            ];
        } else {
            // Record not found
            $response = [
                'status' => '0',
                'response' => 'Record not found.'
            ];
        }

        // Return the response as JSON
        return response()->json($response);
    }

    public function getNameById($id)
    {
        // Validate ID is numeric
        if (!is_numeric($id)) {
            return response()->json([
                'error' => 'Invalid ID format'
            ], 400);
        }

        $customer = customer_package::find($id);

        if (!$customer) {
            return response()->json([
                'error' => 'Customer not found'
            ], 404);
        }

        return response()->json([
            'id' => $customer->id,
            'name' => $customer->name
        ]);
    }

    // In your PackageController.php
    public function package_details(Request $request)
    {
        $package_details = InclusivePackages::find($request->id);
        $cities_dts = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        $amenities_dts = Amenities::where('status', "1")->where('is_deleted', "0")->get();
        $foodBeverages_dts = FoodBeverage::where('status', "1")->where('is_deleted', "0")->get();
        $activities_dts = Activities::where('status', "1")->where('is_deleted', "0")->get();
        $safety_features_dts = Safetyfeatures::where('status', "1")->where('is_deleted', "0")->get();
        $geo_feature_dts = Geo_feature::where('status', "1")->where('is_deleted', "0")->pluck('geo_feature', 'id');
        $themes = Themes::where('status', "1")->where('is_deleted', "0")->pluck('themes_name', 'id');

        $package_details_citys = InclusivePackages::where('id', $request->id)
            ->pluck('city_details')
            ->toArray();

        // dump($package_details_citys);
        $cities = City::where('status', "1")->where('is_deleted', "0")->whereIn('id', $package_details_citys)->pluck('city_name')->toArray();

        $stay_details = stays_destination_details::where('is_deleted', '0')->whereIn('destination', $cities)->orderBy('created_at', 'desc')->select('id', 'stay_title')->get();
        // dd($cities);
        if (!$package_details) {
            return redirect()->route('admin.inclusive_package_list')->with('error', 'Package not found');
        }

        $selectedAmenities = json_decode($package_details->amenity_details, true) ?? [];
        $selectedfood_beverages = json_decode($package_details->food_beverages, true) ?? [];
        $selectedactivities = json_decode($package_details->activities, true) ?? [];
        $selectedsafety_features = json_decode($package_details->safety_features, true) ?? [];

        $selectedprogram = json_decode($package_details->category, true) ?? [];
        // Get the selected city ID
        $selectedCityId = $package_details->city_details;

        // dd($selectedCityId);

        $selectedgeo_featureId = $package_details->geo_feature;
        $selectedthemeId = $package_details->theme_id;
        $selectedCategoryId = $package_details->theme_cat_id;
        $selecteddesCategoryId = $package_details->destination_cat;

        //pricing calculator 

        $selectedCityId = $package_details->city_details;
        $selectedLocationname = $package_details->location_name;
        //  dd($selectedCityId);
        $distination = City::where('id', $selectedCityId)->where('is_deleted', '0')->where('status', '1')->latest()->first();

        // dd($distination);


        $pricingcalculator = [];
        if ($distination) {
            $pricingcalculator = PricingCalculator::where('destination_id', $distination->city_name)
                // ->where('district_id', $selectedLocationname) // Removed extra space after district_id
                ->where('is_deleted', '0')
                ->select('id', 'title')
                ->where('status', '1')
                ->get();
        }
        $categories = Themes_category::where('theme_id', $selectedthemeId)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->pluck('theme_cat', 'id');


        $dest_categories =  Destination_category::where('destination_id', $selectedCityId)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->pluck('destination_cat', 'id');

        $title = ' Edit Program';

        $data = [
            'package_details' => $package_details,
            'cities_dts' => $cities_dts,
            'cities_details' => $stay_details,
            'themes' => $themes,
            'amenities_dts' => $amenities_dts,
            'foodBeverages_dts' => $foodBeverages_dts,
            'activities_dts' => $activities_dts,
            'safety_features_dts' => $safety_features_dts,
            'selectedCityId' => $selectedCityId,
            'selectedAmenities' => $selectedAmenities,
            'selectedthemeId' => $selectedthemeId,
            'selectedfood_beverages' => $selectedfood_beverages,
            'selectedactivities' => $selectedactivities,
            'selectedsafety_features' => $selectedsafety_features,
            'geo_feature_dts' => $geo_feature_dts,
            'selectedgeo_featureId' => $selectedgeo_featureId,
            'categories' => $categories,
            'dest_categories' => $dest_categories,
            'selecteddesCategoryId' => $selecteddesCategoryId,
            'selectedCategoryId' => $selectedCategoryId,
            'selectedprogram' => $selectedprogram,
            'selectedLocationname' => $selectedLocationname,
            'pricingcalculator' => $pricingcalculator
        ];

        return json_encode($data);
    }

    //duplicate entry 
    public function duplicatePackage(Request $request)
    {
        try {
            // Find the original package
            $original = customer_package::find($request->id);
            // dd($original);
            $customer_package = new customer_package;
            $customer_package->name = $original->name . ' (Duplicate)';
            $customer_package->phone_number = $original->phone_number;
            $customer_package->email = $original->email;
            $customer_package->package_id = $original->package_id;
            $customer_package->package_type = $original->package_type;


            $customer_package->important_info = $original->important_info;
            $customer_package->stay_details_id = $original->stay_details_id;
            $customer_package->package_inclusion = $original->package_inclusion;
            $customer_package->package_exclusion = $original->package_exclusion;

            $customer_package->camp_rule = $original->camp_rule;

            $customer_package->tour_planning = $original->tour_planning;


            $customer_package->price_title = $original->price_title;
            $customer_package->price_amount = $original->price_amount;
            $customer_package->amenities = $original->amenities;
            $customer_package->food_beverages = $original->food_beverages;
            $customer_package->activities = $original->activities;
            $customer_package->safety_features = $original->safety_features;
            $customer_package->list_order = $original->list_order;

            $customer_package->status =  $original->status;

            $location = $original->location;

            $customer_package->location =  $location;
            $customer_package->save();



            return response()->json([
                'success' => true,
                'message' => 'Package duplicated successfully',
                'new_entry' => $customer_package
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate package: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit_form(Request $request, $id)
    {
        $title = 'Edit Customer Package';
        $cities = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        $themes = Themes::where('status', "1")->where('is_deleted', "0")->pluck('themes_name', 'id');
        $amenities = Amenities::where('status', "1")->where('is_deleted', "0")->get();
        $foodBeverages = FoodBeverage::where('status', "1")->where('is_deleted', "0")->get();
        $activities = Activities::where('status', "1")->where('is_deleted', "0")->get();
        $safety_features = Safetyfeatures::where('status', "1")->where('is_deleted', "0")->get();


        $titles = DB::table('inclusive_package_details')->where('is_deleted', '0')->where('status', '1')->pluck('title', 'id');


        $customer = customer_package::find($id);
        $titless = customer_package::where('is_deleted', '0')
            ->where('id', $id)
            ->pluck('package_type');
        // dd($titless);
        $package_details_citys = InclusivePackages::where('id', $id)
            ->pluck('city_details')
            ->toArray();

        // dump($package_details_citys);
        $cities = City::where('status', "1")->where('is_deleted', "0")->whereIn('id', $package_details_citys)->pluck('city_name')->toArray();

        $stay_details = stays_destination_details::where(function ($query) use ($cities) {
            $query->where('is_deleted', '0')
                ->whereIn('destination', $cities);
        })
            ->orWhere('id', $customer->stay_details_id) // Always include selected old stay
            ->orderBy('created_at', 'desc')
            ->select('id', 'stay_title')
            ->get();
        if (!$customer) {
            return redirect()->route('admin.influencers.list')
                ->with('error', 'Influencer not found.');
        }


        $customerpricing = CustomerPricingCalculator::with('priceLists')
            ->where('customer_package_id', $id)->first();


        // $customer = customer_package::with('customerpackage', 'customertourplanning')->find($id);
        $customer = customer_package::with([
            'customerpackage',
            'customertourplanning' => function ($query) use ($id) {
                $query->where(function ($q) use ($id) {
                    $q->where('package_id', function ($subquery) use ($id) {
                        $subquery->select('package_id')
                            ->from('customer_packages')
                            ->where('id', $id);
                    })
                        ->where('customer_id', function ($subquery) use ($id) {
                            $subquery->select('customer_id')
                                ->from('customer_packages')
                                ->where('id', $id);
                        });
                });
            }
        ])->find($id);
        // dd($customer);
        $package_details = InclusivePackages::where('id', $customer->package_id)
            ->first();
        $selectedLocationcity = $package_details->city_details;

        // dd($selectedLocationcity);
        $distination = City::where('id', $selectedLocationcity)->where('is_deleted', '0')->where('status', '1')->latest()->first();

        // dd($distination);

        $selectedLocationname = $package_details->location_name;

        // dd($selectedLocationname);
        $pricingcalculator = [];
        if ($distination && $selectedLocationname) {
            $pricingcalculator = PricingCalculator::where('destination_id', $distination->city_name)
                ->where('district_id', $selectedLocationname)
                ->where('is_deleted', '0')
                ->select('id', 'title')
                ->where('status', '1')
                ->get();
        }

        return view('admin.customer_package.customerpackageedit', compact(
            'title',
            'customer',
            'titles',
            'titless',
            'cities',
            'themes',
            'amenities',
            'foodBeverages',
            'activities',
            'safety_features',
            'stay_details',
            'customerpricing',
            'pricingcalculator'
        ));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        // Fetch the package
        $customer_package = customer_package::findOrFail($id);

        // Basic package info
        $customer_package->name = ucfirst($request->name ?? $customer_package->name);
        $customer_package->phone_number = $request->phone_number ?? $customer_package->phone_number;
        $customer_package->email = $request->email ?? $customer_package->email;
        $customer_package->package_type = $request->title ?? $customer_package->package_type;
        $customer_package->stay_details_id = $request->package_stay ?? $customer_package->stay_details_id;
        $customer_package->list_order = $request->input('list_order', $customer_package->list_order);
        $customer_package->status = $request->has('status') && $request->status === 'on' ? '1' : '0';

        // Location handling
        $location = $request->location ?? '';
        $location = strip_tags($location);
        $location = html_entity_decode($location);
        $customer_package->location = json_encode($location, JSON_UNESCAPED_UNICODE);

        // JSON fields
        $customer_package->package_inclusion = json_encode($request->input('program_inclusion', []), JSON_UNESCAPED_UNICODE);
        $customer_package->package_exclusion = json_encode($request->input('program_exclusion', []), JSON_UNESCAPED_UNICODE);
        // $customer_package->tour_planning = json_encode($request->input('tour_planning', []), JSON_UNESCAPED_UNICODE);
        $customer_package->camp_rule = json_encode($request->input('camp_rule', []), JSON_UNESCAPED_UNICODE);
        $customer_package->amenities = json_encode($request->input('amenity_services', []), JSON_UNESCAPED_UNICODE);
        $customer_package->food_beverages = json_encode($request->input('food_beverages', []), JSON_UNESCAPED_UNICODE);
        $customer_package->activities = json_encode($request->input('activities', []), JSON_UNESCAPED_UNICODE);
        $customer_package->safety_features = json_encode($request->input('safety_features', []), JSON_UNESCAPED_UNICODE);
        $customer_package->price_title = json_encode($request->input('price_title', []), JSON_UNESCAPED_UNICODE);
        $customer_package->price_amount = json_encode($request->input('price_amount', []), JSON_UNESCAPED_UNICODE);

        $customer_package->save();


        // if ($request->filled('tour_planning')) {
        //     $tourPlanningData = $request->tour_planning;


        //     foreach ($tourPlanningData as $index => $day) {
        //         CustomerTourPlanning::create([
        //             'customer_id' => $customer_package->id,
        //             'package_id' => $packageData['id'],
        //             'day_title' => $day['title'] ?? null,
        //             'day_subtitle' => $day['subtitle'] ?? null,
        //             'activity_description' => $day['description'] ?? null,
        //             'day_order' => $index
        //         ]);
        //     }
        // }

        // Pricing Calculator
        $packageData = json_decode($request->package_type, true);
        $pricingCalculator = CustomerPricingCalculator::where('customer_package_id', $id)->first();

        if ($pricingCalculator) {
            $pricingCalculator->pricing_calculator_id = $request->pricing_calculator;
            $pricingCalculator->package_id = $packageData['id'] ?? $pricingCalculator->package_id;
            $pricingCalculator->customer_package_id = $id;
            $pricingCalculator->stays_id = $request->stay_id ?? $pricingCalculator->stays_id;
            $pricingCalculator->activitys_id = $request->activity_ids ?? $pricingCalculator->activitys_id;
            $pricingCalculator->cab_details_id = $request->selected_cab_options ?? $pricingCalculator->cab_details_id;
            $pricingCalculator->cab_type = $request->cab_types ?? $pricingCalculator->cab_type;
            $pricingCalculator->save();

            // Delete old entries
            CustomerPriceCalculatorList::where('customer_pricing_id', $pricingCalculator->id)
                ->where('is_deleted', '0')
                ->delete();

            // Insert new stays
            foreach ($request->stays ?? [] as $stayChunk) {
                foreach ($stayChunk as $stay) {
                    CustomerPriceCalculatorList::create([
                        'customer_pricing_id' => $pricingCalculator->id,
                        'type' => 'stay',
                        'type_id' => $stay['stay_id'] ?? null,
                        'title' => $stay['title'] ?? null,
                        'price_title' => $stay['price_title'] ?? null,
                        'price' => $stay['price'] ?? null
                    ]);
                }
            }

            // Insert new activities
            foreach ($request->activity ?? [] as $activityChunk) {
                foreach ($activityChunk as $activity) {
                    CustomerPriceCalculatorList::create([
                        'customer_pricing_id' => $pricingCalculator->id,
                        'type' => 'activity',
                        'type_id' => $activity['activity_id'] ?? null,
                        'title' => $activity['title'] ?? null,
                        'price_title' => $activity['price_title'] ?? null,
                        'price' => $activity['price'] ?? null
                    ]);
                }
            }

            // Insert new cabs
            foreach ($request->cabs ?? [] as $cabChunk) {
                foreach ($cabChunk as $cab) {
                    CustomerPriceCalculatorList::create([
                        'customer_pricing_id' => $pricingCalculator->id,
                        'type' => 'cabs',
                        'type_id' => $cab['cab_id'] ?? null,
                        'title' => $cab['title'] ?? null,
                        'price_title' => $cab['price_title'] ?? null,
                        'price' => $cab['price'] ?? null
                    ]);
                }
            }
        }

        return redirect()->route('admin.CustomerPackage_list')
            ->with('success', 'Customer package updated successfully!');
    }

    public function pricing_details(Request $request)
    {
        $pricingval = $request->pricingval;

        $pricing_stay1 = PricingCalculator::where('id', $pricingval)->where('is_deleted', '0')->where('status', '1')->first();

        // $travelmodes = explode(',', $pricing_stay->cab_type);

        $pricing_stay = PriceCalculatorList::where('type', 'stay')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();

        $pricing_activity = PriceCalculatorList::where('type', 'activity')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();

        $pricing_cab = PriceCalculatorList::where('type', 'cabs')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();
        $stays = StayPricing::whereIn('id', $pricing_stay)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => ucfirst(strtolower($item->title))];
            })
            ->toArray();

        $activities = ActivityP::whereIn('id', $pricing_activity)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => ucfirst(strtolower($item->title))];
            })
            ->toArray();

        // $cabs = Cab::whereIn('id', $pricing_cab)
        //     ->whereIn('travel_mode', $travelmodes)
        //     ->where('status', '1')
        //     ->where('is_deleted', '0')
        //     ->select('id', 'title')
        //     ->get()
        //     ->mapWithKeys(function ($item) {
        //         return [$item->id => ucfirst(strtolower($item->title))];
        //     })
        //     ->toArray();

        $travelmodes = $pricing_stay1->cab_type ?? '';
        $travelmodesArray = !empty($travelmodes) ? explode(',', $travelmodes) : [];
        $travelmodesArray = array_map('trim', $travelmodesArray);

        $cabs = [];
        foreach ($travelmodesArray as $mode) {
            if (!empty($mode)) {
                $cabs[$mode] = $mode;
            }
        }

        return response()->json([
            'stays' => $stays,
            'activities' => $activities,
            'cabs' => $cabs,
            'pricing_stay_id' => $pricing_stay,
            'pricing_activity_id' => $pricing_activity,
            'pricing_cabs' => $cabs

        ]);
    }

    public function stay_details(Request $request)
    {
        $pricingval = $request->pricingval;
        $staydetails = $request->staydetails;

        // Initialize exsting prices as empty collection
        $existingPrices = collect();
        // Only check existing prices if calculator ID exists (update case)
        if ($request->has('pricingval')) {
            $calculatorId = $request->pricingval;
            $existingPrices = PriceCalculatorList::where('pricing_calculator_id', $calculatorId)
                ->where('type', 'stay')
                ->get()
                ->keyBy(function ($item) {
                    return $item->type_id . '|' . $item->price_title;
                });
        }

        $pricing_stay = PriceCalculatorList::where('type', 'stay')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();

        $stays = StayPricing::whereIn('id', $pricing_stay)
            ->whereIn('id', $staydetails)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title_price', 'title')
            ->get()
            ->map(function ($stay) use ($existingPrices) {
                $priceData = json_decode($stay->title_price);
                $formattedPrices = [];

                foreach ($priceData as $price) {
                    $key = $stay->id . '|' . $price->title;
                    $existingPrice = $existingPrices->get($key);

                    $formattedPrices[] = [
                        'stay_id' => $stay->id,
                        'title' => $stay->title,
                        'price_title' => $price->title,
                        'price' => $existingPrice ? $existingPrice->price : $price->price
                    ];
                }

                return $formattedPrices;
            })
            ->toArray();

        return response()->json([
            'stays_details' => $stays
        ]);
    }

    public function activity_details(Request $request)
    {
        $pricingval = $request->pricingval;
        $activitydetails = $request->staydetails;
        $existingPrices = collect();

        // Only check existing prices if calculator ID exists (update case)
        if ($request->has('pricingval')) {
            $calculatorId = $request->pricingval;
            $existingPrices = PriceCalculatorList::where('pricing_calculator_id', $calculatorId)
                ->where('type', 'activity')
                ->get()
                ->keyBy(function ($item) {
                    return $item->type_id . '|' . $item->price_title;
                });
        }

        $pricing_activity = PriceCalculatorList::where('type', 'activity')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();

        $activities = ActivityP::whereIn('id', $pricing_activity)
            ->whereIn('id', $activitydetails)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title_price', 'title')
            ->get()
            ->map(function ($activity) use ($existingPrices) {
                $priceData = json_decode($activity->title_price);
                $formattedPrices = [];

                foreach ($priceData as $price) {
                    $key = $activity->id . '|' . $price->title;
                    $existingPrice = $existingPrices->get($key);

                    $formattedPrices[] = [
                        'activity_id' => $activity->id,
                        'title' => $activity->title,
                        'price_title' => $price->title,
                        'price' => $existingPrice ? $existingPrice->price : $price->price
                    ];
                }

                return $formattedPrices;
            })
            ->toArray();

        return response()->json([
            'activity_details' => $activities
        ]);
    }

    public function travel_details(Request $request)
    {

        // dd($request->all());
        $pricingval = $request->pricingval;
        $travelmodes = $request->travelmodes;

        $pricing_cab = PriceCalculatorList::where('type', 'cabs')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();


        $cabs = Cab::whereIn('travel_mode', $travelmodes)
            ->whereIn('id', $pricing_cab)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => ucfirst(strtolower($item->title))];
            })
            ->toArray();

        return response()->json([
            'cabs' => $cabs,
            'pricing_cab' => $pricing_cab
        ]);
    }

    public function cabs_details(Request $request)
    {
        // dd($request->all());
        $pricingval = $request->pricingval;
        $travelmodes = $request->travelmodes;
        $cabdetails = $request->cabdetails;

        $existingPrices = collect();

        // Only check existing prices if calculator ID exists (update case)
        if ($request->has('pricingval')) {
            $calculatorId = $request->pricingval;
            $existingPrices = PriceCalculatorList::where('pricing_calculator_id', $calculatorId)
                ->where('type', 'cabs')
                ->get()
                ->keyBy(function ($item) {
                    return $item->type_id . '|' . $item->price_title;
                });
        }

        $pricing_cab = PriceCalculatorList::where('type', 'cabs')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();

        $activitys = Cab::whereIn('id', $pricing_cab)
            ->whereIn('travel_mode', $travelmodes)
            ->whereIn('id', $cabdetails)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title_price', 'title')
            ->get()
            ->map(function ($stay) use ($existingPrices) {
                $priceData = json_decode($stay->title_price);
                $formattedPrices = [];

                foreach ($priceData as $price) {
                    $key = $stay->id . '|' . $price->title;
                    $existingPrice = $existingPrices->get($key);
                    $formattedPrices[] = [
                        'cab_id' => $stay->id,
                        'title' => $stay->title,
                        'price_title' => $price->title,
                        // 'price' => $price->price
                        'price' => $existingPrice ? $existingPrice->price : $price->price
                    ];
                }

                return $formattedPrices;
            })
            // ->flatten(1) // Flatten the array of arrays
            ->toArray();

        return response()->json([
            'activity_details' => $activitys
        ]);
    }

    //edit stay
    public function edit_stay_details(Request $request)
    {
        try {
            $pricingval = $request->pricingval;
            $staydetails = $request->staydetails ?? [];
            $customerid = $request->customerid;

            $customerPricing = CustomerPricingCalculator::where('pricing_calculator_id', $pricingval)
                ->where('customer_package_id', $customerid)
                ->first();

            if ($customerPricing) {
                // Get existing prices from CustomerPriceCalculatorList
                $existingPrices = CustomerPriceCalculatorList::where('customer_pricing_id', $customerPricing->id)
                    ->where('type', 'stay')
                    ->where('is_deleted', '0')
                    ->get()
                    ->keyBy(function ($item) {
                        return $item->type_id . '|' . $item->price_title;
                    });

                // Get distinct stay IDs
                $pricingStayIds = CustomerPriceCalculatorList::where('type', 'stay')
                    ->where('customer_pricing_id', $customerPricing->id)
                    ->where('is_deleted', '0')
                    ->distinct('type_id')
                    ->pluck('type_id')
                    ->toArray();

                $stays = StayPricing::with('priceStay')
                    ->whereIn('id', $pricingStayIds)
                    ->when(!empty($staydetails), function ($query) use ($staydetails) {
                        return $query->whereIn('id', $staydetails);
                    })
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->select('id', 'title_price', 'title')
                    ->get()
                    ->map(function ($stay) use ($existingPrices) {
                        $priceData = json_decode($stay->title_price, true) ?? [];
                        $formattedPrices = [];

                        // Get all priceStay records for this stay
                        $priceStayRecords = [];
                        if ($stay->relationLoaded('priceStay') && $stay->priceStay && $stay->priceStay->isNotEmpty()) {
                            $priceStayRecords = $stay->priceStay->keyBy('price_title');
                        }

                        foreach ($priceData as $price) {
                            $key = $stay->id . '|' . ($price['title'] ?? '');
                            $existingPrice = $existingPrices->get($key);

                            // Find matching priceStay record by price_title
                            $priceStayId = null;
                            $priceTitle = $price['title'] ?? '';

                            if (!empty($priceTitle) && isset($priceStayRecords[$priceTitle])) {
                                $priceStayId = $priceStayRecords[$priceTitle]->id;
                            }

                            // Include all records, not just those with priceStayId
                            if ($priceStayId !== null) {
                                $formattedPrices[] = [
                                    'existingPricesid' => $priceStayId,
                                    'stay_id' => $stay->id,
                                    'title' => $stay->title,
                                    'price_title' => $priceTitle,
                                    'price' => $existingPrice ? $existingPrice->price : ($price['price'] ?? 0)
                                ];
                            }
                        }

                        return $formattedPrices;
                    })
                    ->filter()
                    ->values()
                    ->toArray();
            } else {
                // Handle case when no customer pricing record exists
                $existingPrices = collect();

                // Get pricing stay IDs from PriceCalculatorList (not CustomerPriceCalculatorList)
                $pricingStayIds = PriceCalculatorList::where('type', 'stay')
                    ->where('pricing_calculator_id', $pricingval)
                    // ->where('is_deleted', '0')
                    ->distinct('type_id')
                    ->pluck('type_id')
                    ->toArray();

                $stays = StayPricing::whereIn('id', $pricingStayIds)
                    ->when(!empty($staydetails), function ($query) use ($staydetails) {
                        return $query->whereIn('id', $staydetails);
                    })
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->select('id', 'title_price', 'title')
                    ->get()
                    ->map(function ($stay) use ($existingPrices) {
                        $priceData = json_decode($stay->title_price, true) ?? [];
                        $formattedPrices = [];

                        foreach ($priceData as $price) {
                            $key = $stay->id . '|' . ($price['title'] ?? '');
                            $existingPrice = $existingPrices->get($key);

                            $formattedPrices[] = [
                                'existingPricesid' => null, // No existing prices in this case
                                'stay_id' => $stay->id,
                                'title' => $stay->title,
                                'price_title' => $price['title'] ?? '',
                                'price' => $existingPrice ? $existingPrice->price : ($price['price'] ?? 0)
                            ];
                        }

                        return $formattedPrices;
                    })
                    ->filter()
                    ->values()
                    ->toArray();
            }

            return response()->json([
                'success' => true,
                'stays_details' => $stays
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching stay details: ' . $e->getMessage(),
                'stays_details' => []
            ], 500);
        }
    }

    public function edit_activity_details(Request $request)
    {
        try {
            $pricingval = $request->pricingval;
            $staydetails = $request->staydetails ?? [];
            $customerid = $request->customerid;

            $customerPricing = CustomerPricingCalculator::where('pricing_calculator_id', $pricingval)
                ->where('customer_package_id', $customerid)
                ->first();

            if ($customerPricing) {
                // Get existing activity prices from CustomerPriceCalculatorList
                $existingPrices = CustomerPriceCalculatorList::where('customer_pricing_id', $customerPricing->id)
                    ->where('type', 'activity')
                    ->where('is_deleted', '0')
                    ->get()
                    ->keyBy(function ($item) {
                        return $item->type_id . '|' . $item->price_title;
                    });

                // Get distinct activity IDs
                $pricingActivityIds = CustomerPriceCalculatorList::where('type', 'activity')
                    ->where('customer_pricing_id', $customerPricing->id)
                    ->where('is_deleted', '0')
                    ->distinct('type_id')
                    ->pluck('type_id')
                    ->toArray();

                $activities = ActivityP::with('priceActivity')
                    ->whereIn('id', $pricingActivityIds)
                    ->when(!empty($staydetails), function ($query) use ($staydetails) {
                        return $query->whereIn('id', $staydetails);
                    })
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->select('id', 'title_price', 'title')
                    ->get()
                    ->map(function ($activity) use ($existingPrices) {
                        $priceData = json_decode($activity->title_price, true) ?? [];
                        $formattedPrices = [];

                        foreach ($priceData as $price) {
                            $key = $activity->id . '|' . ($price['title'] ?? '');
                            $existingPrice = $existingPrices->get($key);

                            // Get priceActivity ID safely
                            // $priceActivityId = null;
                            // if ($activity->relationLoaded('priceActivity') && $activity->priceActivity && $activity->priceActivity->isNotEmpty()) {
                            //     $priceActivityId = $activity->priceActivity->first()->id;
                            // }

                            $priceActivityId = [];
                            if ($activity->relationLoaded('priceActivity') && $activity->priceActivity && $activity->priceActivity->isNotEmpty()) {
                                $priceActivityId = $activity->priceActivity->keyBy('price_title');
                            }

                            if ($priceActivityId !== null) {
                                $formattedPrices[] = [
                                    'existingPricesid' => $priceActivityId,
                                    'activity_id' => $activity->id,
                                    'title' => $activity->title,
                                    'price_title' => $price['title'] ?? '',
                                    'price' => $existingPrice ? $existingPrice->price : ($price['price'] ?? 0)
                                ];
                            }
                        }

                        return $formattedPrices;
                    })
                    ->filter()
                    ->values()
                    ->toArray();
            } else {
                // Handle case when no customer pricing record exists
                $existingPrices = collect();

                // Get pricing activity IDs from PriceCalculatorList
                $pricingActivityIds = PriceCalculatorList::where('type', 'activity')
                    ->where('pricing_calculator_id', $pricingval)
                    // ->where('is_deleted', '0')
                    ->distinct('type_id')
                    ->pluck('type_id')
                    ->toArray();

                $activities = ActivityP::whereIn('id', $pricingActivityIds)
                    ->when(!empty($staydetails), function ($query) use ($staydetails) {
                        return $query->whereIn('id', $staydetails);
                    })
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->select('id', 'title_price', 'title')
                    ->get()
                    ->map(function ($activity) use ($existingPrices) {
                        $priceData = json_decode($activity->title_price, true) ?? [];
                        $formattedPrices = [];

                        foreach ($priceData as $price) {
                            $key = $activity->id . '|' . ($price['title'] ?? '');
                            $existingPrice = $existingPrices->get($key);

                            $formattedPrices[] = [
                                'existingPricesid' => null,
                                'activity_id' => $activity->id,
                                'title' => $activity->title,
                                'price_title' => $price['title'] ?? '',
                                'price' => $existingPrice ? $existingPrice->price : ($price['price'] ?? 0)
                            ];
                        }

                        return $formattedPrices;
                    })
                    ->filter()
                    ->values()
                    ->toArray();
            }

            return response()->json([
                'success' => true,
                'activity_details' => $activities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching activity details: ' . $e->getMessage(),
                'activity_details' => []
            ], 500);
        }
    }

    public function edit_cabs_details(Request $request)
    {
        try {
            $pricingval = $request->pricingval;
            $travelmodes = $request->travelmodes;
            $cabdetails = $request->cabdetails;
            $customerid = $request->customerid;

            $customerPricing = CustomerPricingCalculator::where('pricing_calculator_id', $pricingval)
                ->where('customer_package_id', $customerid)
                ->first();

            if ($customerPricing) {
                $existingPrices = CustomerPriceCalculatorList::where('customer_pricing_id', $customerPricing->id) // Changed from $customar_priceid->id
                    ->where('type', 'cabs')
                    ->get()
                    ->keyBy(function ($item) {
                        return $item->type_id . '|' . $item->price_title; // Fixed key format
                    });

                $pricing_cab = PriceCalculatorList::where('type', 'cabs')
                    ->where('pricing_calculator_id', $pricingval)
                    ->distinct('type_id')
                    ->pluck('type_id')
                    ->toArray();

                $activities = Cab::with('priceCabs')
                    ->whereIn('id', $pricing_cab)
                    ->whereIn('travel_mode', $travelmodes)
                    ->whereIn('id', $cabdetails)
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->select('id', 'title_price', 'title')
                    ->get()
                    ->map(function ($cab) use ($existingPrices) { // Changed variable name from $stay to $cab
                        $priceData = json_decode($cab->title_price);
                        $formattedPrices = [];

                        foreach ($priceData as $price) {
                            $key = $cab->id . '|' . $price->title;
                            $existingPrice = $existingPrices->get($key);
                            // $priceCabsId = null;

                            // if ($cab->relationLoaded('priceCabs') && $cab->priceCabs->isNotEmpty()) {
                            //     $priceCabsId = $cab->priceCabs->first()->id;
                            // }

                            $priceCabsId = [];
                            if ($cab->relationLoaded('priceCabs') && $cab->priceCabs && $cab->priceCabs->isNotEmpty()) {
                                $priceCabsId = $cab->priceCabs->keyBy('price_title');
                            }

                            // dd($priceCabsId);


                            if ($priceCabsId != null) {
                                $formattedPrices[] = [
                                    'existingPricesid' => $priceCabsId,
                                    'cab_id' => $cab->id,
                                    'title' => $cab->title,
                                    'price_title' => $price->title,
                                    'price' => $existingPrice ? $existingPrice->price : $price->price
                                ];
                            }
                        }
                        return $formattedPrices;
                    })
                    ->filter()
                    ->values()
                    ->toArray();
            } else {
                // Handle case when no customer pricing record exists
                $existingPrices = collect();

                // Get pricing cab IDs from PriceCalculatorList
                $pricingCabIds = PriceCalculatorList::where('type', 'cabs')
                    ->where('pricing_calculator_id', $pricingval)
                    ->distinct('type_id')
                    ->pluck('type_id')
                    ->toArray();

                $activities = Cab::whereIn('id', $pricingCabIds)
                    ->whereIn('travel_mode', $travelmodes)
                    ->whereIn('id', $cabdetails)
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->select('id', 'title_price', 'title')
                    ->get()
                    ->map(function ($cab) use ($existingPrices) { // Changed variable name from $stay to $cab
                        $priceData = json_decode($cab->title_price, true);
                        $formattedPrices = [];

                        if (!is_array($priceData)) {
                            return $formattedPrices;
                        }

                        foreach ($priceData as $price) {
                            $key = $cab->id . '|' . ($price['title'] ?? '');
                            $existingPrice = $existingPrices->get($key);

                            $formattedPrices[] = [
                                'existingPricesid' => null,
                                'cab_id' => $cab->id, // Changed from $activity->id
                                'title' => $cab->title, // Changed from $activity->title
                                'price_title' => $price['title'] ?? '',
                                'price' => $existingPrice ? $existingPrice->price : ($price['price'] ?? 0)
                            ];
                        }

                        return $formattedPrices;
                    })
                    ->filter()
                    ->values()
                    ->toArray();
            }

            // dd($activities);
            return response()->json([
                'success' => true,
                'activity_details' => $activities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching activity details: ' . $e->getMessage(),
                'activity_details' => []
            ], 500);
        }
    }
    //customer price delete

    public function delete_customer_details(Request $request)
    {
        try {
            $item = CustomerPriceCalculatorList::where('id', $request->customerid)
                ->where('type', $request->type)
                ->first();

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found'
                ], 404);
            }

            if ($item) {
                // Update the is_deleted field to 1
                $item->is_deleted = "1";
                $item->save();

                // Prepare the response
                $response = [
                    'status' => '1',
                    'response' => 'Record marked as deleted successfully.'
                ];
            } else {
                // Record not found
                $response = [
                    'status' => '0',
                    'response' => 'Record not found.'
                ];
            }
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatecustomertourplan(Request $request)
    {
        try {
            // Validate required fields
            $validator = Validator::make($request->all(), [
                'day_title' => 'required',
                'day_subtitle' => 'required',
                'activity_description' => 'required',
            ], [
                'day_title.required' => 'Day title is required',
                'day_subtitle.required' => 'Day subtitle is required',
                'activity_description.required' => 'Activity description is required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $tourplanid = $request->day_id;
            $customerid = $request->customerid;
            $packageid = $request->packageid;

            // Safely handle package ID - check if it's JSON or already an ID
            if (is_string($packageid) && json_decode($packageid) !== null) {
                $packageData = json_decode($packageid, true);
                $packageIdValue = $packageData['id'] ?? $packageid; // Fallback to original value
            } else {
                $packageIdValue = $packageid;
            }

            // Ensure packageIdValue is not null
            if (empty($packageIdValue)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package ID is required'
                ], 422);
            }

            $customertourplan = CustomerTourPlanning::where('id', $tourplanid)->first();

            if ($customertourplan) {
                // Update existing record
                $customertourplan->update([
                    'day_title' => $request->day_title ?? null,
                    'day_subtitle' => $request->day_subtitle ?? null,
                    'activity_description' => $request->activity_description ?? null
                ]);

                $message = 'Day updated successfully';
            } else {
                // Create new record - validate required fields for creation
                if (empty($customerid) || empty($packageIdValue)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Customer ID and Package ID are required for creating new records'
                    ], 422);
                }

                CustomerTourPlanning::create([
                    'customer_id' => $customerid,
                    'package_id' => $packageIdValue,
                    'day_title' => $request->day_title ?? null,
                    'day_subtitle' => $request->day_subtitle ?? null,
                    'activity_description' => $request->activity_description ?? null,
                ]);

                $message = 'Day created successfully';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


    public function deletecustomertourplan(Request $request)
    {
        try {
            $tourplanid = $request->day_id;
            $customerid = $request->customerid;
            $packageid = $request->packageid;

            // Add debugging
            \Log::info('Delete request received:', [
                'tourplanid' => $tourplanid,
                'customerid' => $customerid,
                'packageid' => $packageid
            ]);

            $customertourplan = CustomerTourPlanning::where('id', $tourplanid)->first();

            // Add more detailed logging
            \Log::info('Record found:', [
                'exists' => !is_null($customertourplan),
                'record' => $customertourplan ? $customertourplan->toArray() : null
            ]);

            if ($customertourplan) {
                // Update existing record
                $customertourplan->is_deleted = "1";
                $customertourplan->save();

                $message = 'Day deleted successfully';

                return response()->json([
                    'success' => true,
                    'message' => $message,
                ]);
            } else {
                \Log::warning('Record not found for ID: ' . $tourplanid);
                return response()->json([
                    'success' => false,
                    'message' => 'Tour plan not found',
                ], 404);
            }
        } catch (\Exception $e) {
            \Log::error('Delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
