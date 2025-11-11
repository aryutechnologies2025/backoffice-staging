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
use App\Models\Settings;
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

    public function getPackagesByCity(Request $request)
    {
        $city_id = $request->city_id;

        $packages = DB::table('inclusive_package_details')
            ->where('is_deleted', '0')
            ->where('status', '1')
            ->whereIn('city_details', $city_id)
            ->select('id', 'title')
            ->get();
        return response()->json($packages);
    }


    public function insert(Request $request)
    {

        // dd($request->all());
        // Validate required fields
        $request->validate([
            'tour_planning' => 'required|array',
            'tour_planning.*.title' => 'required|string',
            'tour_planning.*.description' => 'required|string',
        ]);

        // Create new customer package
        $customer_package = new customer_package();
        $customer_package->name = ucfirst($request->name);
        $customer_package->phone_number = $request->phone_number;
        $customer_package->email = $request->email;
        // Decode package_type safely
        // $packageData = json_decode($request->package_type, true);
        // if (is_array($packageData) && isset($packageData['id'])) {
        //     $customer_package->package_id = $packageData['id'];
        // } else {
        //     $customer_package->package_id = $request->package_type; // fallback to ID directly
        // }
        $customer_package->package_id = $request->input('package_id');
        // $customer_package->package_id = $request->package_type;

        $cityIds = $request->input('city_select');
        if (is_array($cityIds)) {
            $cityIdsString = implode(',', $cityIds);
        }

        $customer_package->destination_id = $cityIdsString;

        $customer_package->package_type = $request->title;
        $customer_package->important_info = $request->input('important_info');
        $customer_package->stay_details_id = $request->input('package_select_stay');
        $customer_package->package_inclusion = json_encode($request->input('program_inclusion'));
        $customer_package->package_exclusion = json_encode($request->input('program_exclusion'));

        $customer_package->amenities = json_encode($request->input('amenity_services', []));
        $customer_package->food_beverages = json_encode($request->input('food_beverages', []));
        $customer_package->activities = json_encode($request->input('activities', []));
        $customer_package->safety_features = json_encode($request->input('safety_features', []));
        $customer_package->camp_rule = json_encode($request->input('camp_rule'));
        $customer_package->tour_planning = json_encode($request->input('tour_planning'));;

        $customer_package->price_title = json_encode($request->input('price_title', []));
        $customer_package->price_amount = json_encode($request->input('price_amount', []));
        $customer_package->list_order = $request->input('list_order');
        $customer_package->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $customer_package->location = json_encode($request->input('location'));
        $customer_package->save();
        // dd($customer_package);

        // Save Tour Planning details
        if ($request->filled('tour_planning')) {
            $tourPlanningData = $request->tour_planning;
            foreach ($tourPlanningData as $index => $day) {
                CustomerTourPlanning::create([
                    'customer_id' => $customer_package->id,
                    // 'package_id' => (is_array($packageData) && isset($packageData['id']))
                    //     ? $packageData['id']
                    //     : $request->package_type,
                    'package_id' => $request->package_type,
                    'day_title' => $day['title'] ?? null,
                    'day_subtitle' => $day['subtitle'] ?? null,
                    'activity_description' => $day['description'] ?? null,
                    'day_order' => $index,
                ]);
            }
        }

        // Send email notification
        Mail::to($customer_package->email)->send(new CustomerPackageNotification([
            'name' => $customer_package->name,
            'phone_number' => $customer_package->phone_number,
            'email' => $customer_package->email,
            'package_type' => $customer_package->package_type,
            'package_id' => $customer_package->package_id,
        ]));

        // Pricing Calculator update
        if ($request->has('pricing_calculator') && !empty($request->pricing_calculator) || $request->has('package_pricing_value') && !empty($request->package_pricing_value)) {
            $pricingcalculator_v = new CustomerPricingCalculator();
            if ($request->has('pricing_calculator') && !empty($request->pricing_calculator)) {
                $pricingcalculator_v->pricing_calculator_id = $request->pricing_calculator;
            }

            $pricingcalculator_v->package_id = $request->input('package_id');
            $pricingcalculator_v->customer_package_id = $customer_package->id;
            $pricingcalculator_v->stays_id = $request->stay_id;
            $pricingcalculator_v->activitys_id = $request->activity_ids;
            $pricingcalculator_v->cab_details_id = $request->cab_types;
            $pricingcalculator_v->selected_value = $request->selected_value;
            $pricingcalculator_v->service_fee = $request->service_fee;
            $pricingcalculator_v->tax_amount = $request->gst_number;
            $pricingcalculator_v->total_amount = $request->total_amount;
            $pricingcalculator_v->package_pricing_id = $request->selected_price_id;
            $pricingcalculator_v->package_pricing = $request->package_pricing_value;
            $pricingcalculator_v->grand_total = $request->grand_total;
            $pricingcalculator_v->save();

            if (!empty($request->pricing_calculator)) {
                // Save Stay pricing
                if (isset($request->stays) && count($request->stays) > 0) {
                    foreach ($request->stays as $val) {
                        foreach ($val as $v) {
                            $pricingcalculator = new CustomerPriceCalculatorList();
                            $pricingcalculator->customer_pricing_id = $pricingcalculator_v->id;
                            $pricingcalculator->type = 'stay';
                            $pricingcalculator->type_id = $v['stay_id'] ?? null;
                            $pricingcalculator->title = $v['title'] ?? null;
                            $pricingcalculator->price_title = $v['price_title'] ?? null;
                            $pricingcalculator->price = $v['price'] ?? 0;
                            $pricingcalculator->save();
                        }
                    }
                }

                // Save Activity pricing
                if (isset($request->activity) && count($request->activity) > 0) {
                    foreach ($request->activity as $val) {
                        foreach ($val as $v) {
                            $pricingcalculator = new CustomerPriceCalculatorList();
                            $pricingcalculator->customer_pricing_id = $pricingcalculator_v->id;
                            $pricingcalculator->type = 'activity';
                            $pricingcalculator->type_id = $v['activity_id'] ?? null;
                            $pricingcalculator->title = $v['title'] ?? null;
                            $pricingcalculator->price_title = $v['price_title'] ?? null;
                            $pricingcalculator->price = $v['price'] ?? 0;
                            $pricingcalculator->save();
                        }
                    }
                }

                // Save Cab pricing
                if (isset($request->cabs) && count($request->cabs) > 0) {
                    foreach ($request->cabs as $val) {
                        foreach ($val as $v) {
                            $pricingcalculator = new CustomerPriceCalculatorList();
                            $pricingcalculator->customer_pricing_id = $pricingcalculator_v->id;
                            $pricingcalculator->type = 'cabs';
                            $pricingcalculator->type_id = $v['cab_id'] ?? null;
                            $pricingcalculator->title = $v['title'] ?? null;
                            $pricingcalculator->price_title = $v['price_title'] ?? null;
                            $pricingcalculator->price = $v['price'] ?? 0;
                            $pricingcalculator->save();
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.CustomerPackage_list')
            ->with('success', 'Customer package inserted successfully');
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
    public function getCustomerStay(Request $request)
    {
        $city_ids = $request->city_ids;

        // dd($city_ids);

        if (!$city_ids || !is_array($city_ids)) {
            return response()->json(['error' => 'Invalid or missing city IDs'], 400);
        }

        $cities_names = City::where('status', '1')
            ->where('is_deleted', '0')
            ->whereIn('id', $city_ids)
            ->pluck('city_name')
            ->toArray();

        $stay_details = stays_destination_details::where('is_deleted', '0')
            ->whereIn('destination', $city_ids)
            ->orderBy('created_at', 'desc')
            ->select('id', 'stay_title')
            ->get();

        return response()->json([
            'cities_details' => $stay_details
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

        // dd($package_details_citys);

        $cities = City::where('status', "1")->where('is_deleted', "0")->whereIn('id', $package_details_citys)->pluck('id')->toArray();
        $cities_ids = City::where('status', "1")->where('is_deleted', "0")->whereIn('id', $request->city_ids)->pluck('city_name')->toArray();
        $stay_details = stays_destination_details::where('is_deleted', '0')->whereIn('destination', $cities_ids)->orderBy('created_at', 'desc')->select('id', 'stay_title')->get();
        // dd($cities);
        if (!$package_details) {
            return redirect()->route('admin.inclusive_package_list')->with('error', 'Package not found');
        }
        // dd($stay_details);

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

        $selectedcities = $request->city_ids;
        $pricingcalculator = [];
        if ($selectedcities) {
            $pricingcalculator = PricingCalculator::where(function ($query) use ($selectedcities) {
                foreach ($selectedcities as $cityId) {
                    $query->orWhereRaw("FIND_IN_SET(?, destination_id)", [$cityId]);
                }
            })
                ->where('is_deleted', '0')
                ->where('status', '1')
                ->select('id', 'title')
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
            'package_id' => json_decode($request->id, true) ?? [],
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
            $customer_package->destination_id = $original->destination_id;
            $customer_package->save();


            $pricing_calculator_v = CustomerPricingCalculator::where('customer_package_id', $request->id)->first();


            if ($pricing_calculator_v) {

                $pricing_calculator = new CustomerPricingCalculator();
                $pricing_calculator->pricing_calculator_id = $pricing_calculator_v->pricing_calculator_id;

                $pricing_calculator->package_id = $pricing_calculator_v->package_id;
                $pricing_calculator->customer_package_id = $customer_package->id;
                $pricing_calculator->stays_id = $pricing_calculator_v->stays_id;
                $pricing_calculator->activitys_id = $pricing_calculator_v->activitys_id;
                $pricing_calculator->cab_details_id = $pricing_calculator_v->cab_details_id;
                $pricing_calculator->selected_value = $pricing_calculator_v->selected_value;
                $pricing_calculator->service_fee = $pricing_calculator_v->service_fee;
                $pricing_calculator->tax_amount = $pricing_calculator_v->tax_amount;
                $pricing_calculator->total_amount = $pricing_calculator_v->total_amount;
                $pricing_calculator->package_pricing_id = $pricing_calculator_v->package_pricing_id;
                $pricing_calculator->package_pricing = $pricing_calculator_v->package_pricing;
                $pricing_calculator->grand_total = $pricing_calculator_v->grand_total;
                $pricing_calculator->save();


                $pricing_calculator_list = CustomerPriceCalculatorList::where('customer_pricing_id', $pricing_calculator_v->id)->get();

                foreach ($pricing_calculator_list as $list) {
                    $pricingcalculator_list = new CustomerPriceCalculatorList();
                    $pricingcalculator_list->customer_pricing_id = $pricing_calculator->id;
                    $pricingcalculator_list->type = $list->type;
                    $pricingcalculator_list->type_id = $list->type_id;
                    $pricingcalculator_list->title = $list->title;
                    $pricingcalculator_list->price_title = $list->price_title;
                    $pricingcalculator_list->price = $list->price;
                    $pricingcalculator_list->save();
                }
            }


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

        // Fetch dropdown data
        $cities = City::where('status', '1')->where('is_deleted', '0')->pluck('city_name', 'id');


        $themes = Themes::where('status', "1")->where('is_deleted', "0")->pluck('themes_name', 'id');
        $amenities = Amenities::where('status', "1")->where('is_deleted', "0")->get();
        $foodBeverages = FoodBeverage::where('status', "1")->where('is_deleted', "0")->get();
        $activities = Activities::where('status', "1")->where('is_deleted', "0")->get();
        $safety_features = Safetyfeatures::where('status', "1")->where('is_deleted', "0")->get();

        $titles = DB::table('inclusive_package_details')
            ->where('is_deleted', '0')
            ->where('status', '1')
            ->pluck('title', 'id');

        // Find customer package
        $customer = customer_package::find($id);
        if (!$customer) {
            return redirect()->route('admin.influencers.list')
                ->with('error', 'Customer package not found.');
        }

        // Get package type
        $titless = customer_package::where('is_deleted', '0')
            ->where('id', $id)
            ->pluck('package_type');

        // Get city IDs associated with the package
        $package_details_citys = InclusivePackages::where('id', $id)
            ->pluck('city_details')
            ->toArray();

        $cities = City::where('status', "1")
            ->where('is_deleted', "0")
            ->whereIn('id', $package_details_citys)
            ->pluck('city_name')
            ->toArray();

        // Fetch stay details for selected cities
        $stay_details = stays_destination_details::where(function ($query) use ($cities) {
            $query->where('is_deleted', '0')->whereIn('destination', $cities);
        })
            ->orWhere('id', $customer->stay_details_id) // Include old stay details even if city missing
            ->orderBy('created_at', 'desc')
            ->select('id', 'stay_title')
            ->get();

        // Fetch pricing details if available
        $customerpricing = CustomerPricingCalculator::with('priceLists')
            ->where('customer_package_id', $id)
            ->first();

        $customerpricingid = CustomerPricingCalculator::where('customer_package_id', $id)->pluck('pricing_calculator_id')->toArray();
        $customerservicefee = CustomerPricingCalculator::select('service_fee', 'tax_amount', 'package_pricing_id', 'id')->where('customer_package_id', $id)->first();

        // dd($customerservicefee);

        // Get customer with relationships
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

        // 🔹 Safely fetch package details
        $package_details = null;
        if ($customer && $customer->package_id) {
            $package_details = InclusivePackages::find($customer->package_id);
        }

        // Handle missing package details gracefully
        if (!$package_details) {
            return redirect()->back()->with('error', 'Package details not found for this customer.');
        }

        $selectedLocationcity = $package_details->city_details ?? null;

        // Fetch destination safely
        $distination = null;
        if ($selectedLocationcity) {
            $distination = City::where('id', $selectedLocationcity)
                ->where('is_deleted', '0')
                ->where('status', '1')
                ->latest()
                ->first();
        }

        $selectedLocationname = $package_details->location_name ?? '';

        // Pricing calculator based on destination
        $pricingcalculator = collect();
        if ($distination) {
            $pricingcalculator = PricingCalculator::where('destination_id', $distination->city_name)
                ->where('is_deleted', '0')
                ->where('status', '1')
                ->select('id', 'title')
                ->get();
        }


        // dd($customer);

        $destinatoncitys = City::where('status', '1')->where('is_deleted', '0')->pluck('city_name', 'id');

        // Return view
        return view('admin.customer_package.customerpackageedit', compact(
            'title',
            'customer',
            'titles',
            'titless',
            'destinatoncitys',
            'themes',
            'amenities',
            'foodBeverages',
            'activities',
            'safety_features',
            'stay_details',
            'customerpricing',
            'pricingcalculator',
            'customerpricingid',
            'customerservicefee'
        ));
    }

    public function update(Request $request, $id)
    {

        // dd($request->all());
        // Fetch the package
        $customer_package = customer_package::findOrFail($id);

        // Basic package info
        $customer_package->name = ucfirst($request->name ?? $customer_package->name ?? '');
        $customer_package->phone_number = $request->phone_number ?? $customer_package->phone_number ?? '';
        $customer_package->email = $request->email ?? $customer_package->email ?? '';
        $customer_package->package_type = $request->title ?? $customer_package->package_type;
        $customer_package->stay_details_id = $request->package_select_stay;
        $customer_package->list_order = $request->input('list_order', $customer_package->list_order);
        $customer_package->status = $request->has('status') && $request->status === 'on' ? '1' : '0';
        $customer_package->package_id = $request->input('package_id') ?? $customer_package->package_id;

        $cityIds = $request->input('city_select');
        if (is_array($cityIds)) {
            $cityIdsString = implode(',', $cityIds);
        }

        $customer_package->destination_id = $cityIdsString;

        // Location handling
        $location = $request->location ?? '';
        $location = strip_tags($location);
        $location = html_entity_decode($location);
        $customer_package->location = json_encode($location, JSON_UNESCAPED_UNICODE);

        // JSON 
        $customer_package->important_info = $request->input('important_info');
        $customer_package->package_inclusion = json_encode($request->input('program_inclusion', []), JSON_UNESCAPED_UNICODE);
        $customer_package->package_exclusion = json_encode($request->input('program_exclusion', []), JSON_UNESCAPED_UNICODE);
        $customer_package->tour_planning = json_encode($request->input('tour_planning', []), JSON_UNESCAPED_UNICODE);
        $customer_package->camp_rule = json_encode($request->input('camp_rule', []), JSON_UNESCAPED_UNICODE);
        $customer_package->amenities = json_encode($request->input('amenity_services', []), JSON_UNESCAPED_UNICODE);
        $customer_package->food_beverages = json_encode($request->input('food_beverages', []), JSON_UNESCAPED_UNICODE);
        $customer_package->activities = json_encode($request->input('activities', []), JSON_UNESCAPED_UNICODE);
        $customer_package->safety_features = json_encode($request->input('safety_features', []), JSON_UNESCAPED_UNICODE);
        $customer_package->price_title = json_encode($request->input('price_title', []), JSON_UNESCAPED_UNICODE);
        $customer_package->price_amount = json_encode($request->input('price_amount', []), JSON_UNESCAPED_UNICODE);
        $customer_package->tour_planning = json_encode($request->input('tour_planning'));;

        $customer_package->save();


        // Save Tour Planning details
        if ($request->filled('tour_planning')) {
            $tourPlanningData = $request->tour_planning;
            foreach ($tourPlanningData as $index => $day) {
                CustomerTourPlanning::create([
                    'customer_id' => $customer_package->id,
                    'package_id' => $request->package_type,
                    'day_title' => $day['title'] ?? null,
                    'day_subtitle' => $day['subtitle'] ?? null,
                    'activity_description' => $day['description'] ?? null,
                    'day_order' => $index,
                ]);
            }
        }

        // Pricing Calculator
        $packageData = json_decode($request->package_type, true);
        $pricingCalculator = CustomerPricingCalculator::where('customer_package_id', $id)->first();

        if ($pricingCalculator) {
             if ($request->has('pricing_calculator') && !empty($request->pricing_calculator)) {
                $pricingCalculator->pricing_calculator_id = $request->pricing_calculator;

            }

            $pricingCalculator->package_id = $packageData['id'] ?? $pricingCalculator->package_id;
            $pricingCalculator->customer_package_id = $id;
            $pricingCalculator->stays_id = $request->stay_id ?? $pricingCalculator->stays_id;
            $pricingCalculator->activitys_id = $request->activity_ids ?? $pricingCalculator->activitys_id;
            $pricingCalculator->cab_details_id = $request->cab_types ?? $pricingCalculator->cab_details_id;
            $pricingCalculator->cab_type = $pricingCalculator->cab_type;
            $pricingCalculator->selected_value = $request->selected_value;
            $pricingCalculator->service_fee = $request->service_fee;
            $pricingCalculator->tax_amount = $request->gst_number;
            $pricingCalculator->total_amount = $request->total_amount;
            $pricingCalculator->grand_total = $request->grand_total;
            $pricingCalculator->package_pricing_id = $request->selected_price_id;
            $pricingCalculator->package_pricing = $request->package_pricing_value;
            $pricingCalculator->save();


            if (!empty($request->pricing_calculator)) {
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
        }

        return redirect()->route('admin.CustomerPackage_list')
            ->with('success', 'Customer package updated successfully!');
    }

    public function pricing_details(Request $request)
    {
        $pricingval = $request->pricingval;

        $pricing_stay1 = PricingCalculator::where('id', $pricingval)->where('is_deleted', '0')->where('status', '1')->first();

        $pricing_stay = PriceCalculatorList::where('type', 'stay')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();
        $pricing_activity = PriceCalculatorList::where('type', 'activity')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();
        $pricing_cabs_id = PriceCalculatorList::where('type', 'cabs')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();


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

        $cabs = Cab::whereIn('id', $pricing_cabs_id)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => ucfirst(strtolower($item->title))];
            })
            ->toArray();

        $settings = Settings::select('gst', 'service_fee')->get();

        return response()->json([
            'stays' => $stays,
            'activities' => $activities,
            'cabs' => $cabs,
            'pricing_stay_id' => $pricing_stay,
            'pricing_activity_id' => $pricing_activity,
            'pricing_cabs_id' => $pricing_cabs_id,
            'settings' => $settings
        ]);
    }


    public function edit_pricing_details(Request $request)
    {
        $pricingval = $request->pricingval;
        $customer_id = $request->customer_id;
        $customer_pricing_id = $request->customer_pricing_id;

        $pricing_stay1 = PricingCalculator::where('id', $pricingval)->where('is_deleted', '0')->where('status', '1')->first();

        $pricing_stay = PriceCalculatorList::where('type', 'stay')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();
        $pricing_activity = PriceCalculatorList::where('type', 'activity')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();
        $pricing_cabs_id = PriceCalculatorList::where('type', 'cabs')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();


        $cpricing_stay = CustomerPriceCalculatorList::where('type', 'stay')->where('customer_pricing_id', $customer_pricing_id)->distinct('type_id')->pluck('type_id')->toArray();
        $cpricing_activity = CustomerPriceCalculatorList::where('type', 'activity')->where('customer_pricing_id', $customer_pricing_id)->distinct('type_id')->pluck('type_id')->toArray();
        $cpricing_cabs_id = CustomerPriceCalculatorList::where('type', 'cabs')->where('customer_pricing_id', $customer_pricing_id)->distinct('type_id')->pluck('type_id')->toArray();

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

        $cabs = Cab::whereIn('id', $pricing_cabs_id)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => ucfirst(strtolower($item->title))];
            })
            ->toArray();

        $settings = Settings::select('gst', 'service_fee')->get();

        return response()->json([
            'stays' => $stays,
            'activities' => $activities,
            'cabs' => $cabs,
            'pricing_stay_id' => $pricing_stay,
            'pricing_activity_id' => $pricing_activity,
            'pricing_cabs_id' => $pricing_cabs_id,
            'settings' => $settings
        ]);
    }

    public function stay_details(Request $request)
    {
        $pricingval = $request->pricingval;
        $staydetails = $request->staydetails;

        // Initialize existing prices
        $existingPrices = collect();
        if ($request->has('pricingval')) {
            $calculatorId = $request->pricingval;
            $existingPrices = PriceCalculatorList::where('pricing_calculator_id', $calculatorId)
                ->where('type', 'stay')
                ->get()
                ->keyBy(function ($item) {
                    return $item->type_id . '|' . $item->price_title;
                });
        }

        // Fetch stay data
        $stays = PriceCalculatorList::where('type', 'stay')
            ->where('pricing_calculator_id', $pricingval)
            ->select('type_id', 'price_title', 'title', 'price', 'id')
            ->get()
            ->groupBy('title') // 👈 Group by title (each hotel/stay will become one group)
            ->map(function ($stayGroup) {
                return $stayGroup->map(function ($stay) {
                    return [
                        'stay_id' => $stay->type_id,
                        'title' => $stay->title,
                        'price_title' => $stay->price_title,
                        'price' => $stay->price,
                    ];
                })->values();
            })
            ->values()
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

        // $pricing_activity = PriceCalculatorList::where('type', 'activity')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();

        $activities = PriceCalculatorList::where('type', 'activity')
            ->where('pricing_calculator_id', $pricingval)
            ->select('type_id', 'price_title', 'title', 'price', 'id')
            ->get()
            ->groupBy('title')
            ->map(function ($stayGroup) {
                return $stayGroup->map(function ($stay) {
                    return [

                        'activity_id' => $stay->type_id,
                        'title' => $stay->title,
                        'price_title' => $stay->price_title,
                        'price' => $stay->price,
                    ];
                })->values();
            })
            ->values()
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

        $cabs = Cab::whereIn('id', $pricing_cab)
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

        // $pricing_cab = PriceCalculatorList::where('type', 'cabs')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();

        $activitys = PriceCalculatorList::where('type', 'stay')
            ->where('pricing_calculator_id', $pricingval)
            ->select('type_id', 'price_title', 'title', 'price', 'id')
            ->get()
            ->groupBy('title') // 👈 Group by title (each hotel/stay will become one group)
            ->map(function ($stayGroup) {
                return $stayGroup->map(function ($stay) {
                    return [
                        'cab_id' => $stay->type_id,
                        'title' => $stay->title,
                        'price_title' => $stay->price_title,
                        'price' => $stay->price,
                    ];
                })->values();
            })
            ->values()
            ->toArray();

        return response()->json([
            'activity_details' => $activitys
        ]);
    }

    //edit stay
    public function edit_stay_details(Request $request)
    {
        $pricingval = $request->pricingval;
        $staydetails = $request->staydetails; // This contains only checked stay IDs

        // Validate input
        if (!$pricingval || !$staydetails || !is_array($staydetails)) {
            return response()->json([
                'stays_details' => []
            ]);
        }

        // Convert staydetails to integers for proper comparison
        $selectedStayIds = array_map('intval', $staydetails);

        // Fetch stay data ONLY for selected stays
        $stays = PriceCalculatorList::where('type', 'stay')
            ->where('pricing_calculator_id', $pricingval)
            ->whereIn('type_id', $selectedStayIds) // 👈 CRITICAL: Filter by selected stay IDs
            ->select('type_id', 'price_title', 'title', 'price', 'id')
            ->get()
            ->groupBy('title')
            ->map(function ($stayGroup) {
                return $stayGroup->map(function ($stay) {
                    return [
                        'stay_id' => $stay->type_id,
                        'title' => $stay->title,
                        'price_title' => $stay->price_title,
                        'price' => $stay->price,
                    ];
                })->values();
            })
            ->filter(function ($stayGroup) {
                return $stayGroup->count() > 0; // Remove empty groups
            })
            ->values()
            ->toArray();

        return response()->json([
            'stays_details' => $stays
        ]);
    }

    public function edit_activity_details(Request $request)
    {
        try {
            $pricingval = $request->pricingval;
            $staydetails = $request->staydetails ?? []; // This should probably be activitydetails
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

                        // Get all priceActivity records for this activity
                        $priceActivityRecords = [];
                        if ($activity->relationLoaded('priceActivity') && $activity->priceActivity && $activity->priceActivity->isNotEmpty()) {
                            $priceActivityRecords = $activity->priceActivity->keyBy('price_title');
                        }

                        foreach ($priceData as $price) {
                            $key = $activity->id . '|' . ($price['title'] ?? '');
                            $existingPrice = $existingPrices->get($key);

                            // Find matching priceActivity record by price_title
                            $priceActivityId = null;
                            $priceTitle = $price['title'] ?? '';

                            if (!empty($priceTitle) && isset($priceActivityRecords[$priceTitle])) {
                                $priceActivityId = $priceActivityRecords[$priceTitle]->id;
                            }

                            // Include all records, not just those with priceActivityId
                            if ($priceActivityId !== null) {
                                $formattedPrices[] = [
                                    'existingPricesid' => $priceActivityId,
                                    'activity_id' => $activity->id,
                                    'title' => $activity->title,
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

                // Get pricing activity IDs from PriceCalculatorList
                $pricingActivityIds = PriceCalculatorList::where('type', 'activity')
                    ->where('pricing_calculator_id', $pricingval)
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
                $existingPrices = CustomerPriceCalculatorList::where('customer_pricing_id', $customerPricing->id)
                    ->where('type', 'cabs')
                    ->where('is_deleted', '0')
                    ->get()
                    ->keyBy(function ($item) {
                        return $item->type_id . '|' . $item->price_title;
                    });

                $pricing_cab = PriceCalculatorList::where('type', 'cabs')
                    ->where('pricing_calculator_id', $pricingval)
                    ->distinct('type_id')
                    ->pluck('type_id')
                    ->toArray();

                $cabs = Cab::with('priceCabs')
                    ->whereIn('id', $pricing_cab)
                    ->whereIn('travel_mode', $travelmodes)
                    ->whereIn('id', $cabdetails)
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->select('id', 'title_price', 'title')
                    ->get()
                    ->map(function ($cab) use ($existingPrices) {
                        $priceData = json_decode($cab->title_price, true) ?? [];
                        $formattedPrices = [];

                        // Get all priceCabs records for this cab
                        $priceCabsRecords = [];
                        if ($cab->relationLoaded('priceCabs') && $cab->priceCabs && $cab->priceCabs->isNotEmpty()) {
                            $priceCabsRecords = $cab->priceCabs->keyBy('price_title');
                        }

                        foreach ($priceData as $price) {
                            $key = $cab->id . '|' . ($price['title'] ?? '');
                            $existingPrice = $existingPrices->get($key);

                            // Find matching priceCabs record by price_title
                            $priceCabsId = null;
                            $priceTitle = $price['title'] ?? '';

                            if (!empty($priceTitle) && isset($priceCabsRecords[$priceTitle])) {
                                $priceCabsId = $priceCabsRecords[$priceTitle]->id;
                            }

                            // Include all records, not just those with priceCabsId
                            if ($priceCabsId !== null) {
                                $formattedPrices[] = [
                                    'existingPricesid' => $priceCabsId,
                                    'cab_id' => $cab->id,
                                    'title' => $cab->title,
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

                // Get pricing cab IDs from PriceCalculatorList
                $pricingCabIds = PriceCalculatorList::where('type', 'cabs')
                    ->where('pricing_calculator_id', $pricingval)
                    ->distinct('type_id')
                    ->pluck('type_id')
                    ->toArray();

                $cabs = Cab::whereIn('id', $pricingCabIds)
                    ->whereIn('travel_mode', $travelmodes)
                    ->whereIn('id', $cabdetails)
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->select('id', 'title_price', 'title')
                    ->get()
                    ->map(function ($cab) use ($existingPrices) {
                        $priceData = json_decode($cab->title_price, true) ?? [];
                        $formattedPrices = [];

                        foreach ($priceData as $price) {
                            $key = $cab->id . '|' . ($price['title'] ?? '');
                            $existingPrice = $existingPrices->get($key);

                            $formattedPrices[] = [
                                'existingPricesid' => null,
                                'cab_id' => $cab->id,
                                'title' => $cab->title,
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
                'activity_details' => $cabs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching cab details: ' . $e->getMessage(),
                'activity_details' => []
            ], 500);
        }
    }
    //customer price delete
    public function delete_customer_details(Request $request)
    {

        // dd($request->all());
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
