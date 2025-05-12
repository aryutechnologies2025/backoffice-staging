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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerPackage extends Controller
{
    public function list(Request $request)
    {
        $title = 'Customer Package List';
        $customer_package_list = customer_package::where('is_deleted', '0')->latest()->paginate(10);
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


        $titles = DB::table('inclusive_package_details')->where('is_deleted', '0')->pluck('title', 'id');

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

        $customer_package = new customer_package();
        $customer_package->name = ucfirst($request->name);
        $customer_package->phone_number = $request->phone_number;
        $customer_package->email = $request->email;
        $packageData = json_decode($request->package_type, true);
        $customer_package->package_id = $packageData['id'];
        $customer_package->package_type = $packageData['name'];


        $customer_package->important_info = $request->input('important_info');
        $customer_package->package_inclusion = json_encode($request->input('program_inclusion'));

        $customer_package->package_exclusion = json_encode($request->input('program_exclusion'));




        $amenitiesJson = json_encode($request->input('amenity_services', []));
        $foodBeveragesJson = json_encode($request->input('food_beverages', []));
        $activitiesJson = json_encode($request->input('activities', []));
        $safetyFeaturesJson = json_encode($request->input('safety_features', []));
        $campRulesJson = json_encode($request->input('camp_rule'));

        $customer_package->camp_rule = $campRulesJson;

        $tourPlanningJson = json_encode([
            // 'plan_title' => $request->input['plan_title'],
            // 'plan_subtitle' => $request->input['plan_subtitle'],
            'plan_description' => $request->input('plan_description')
        ]);

        $customer_package->tour_planning = $tourPlanningJson;


        $customer_package->price_title = json_encode($request->input('price_title', []));
        $customer_package->price_amount = json_encode($request->input('price_amount', []));
        $customer_package->amenities = $amenitiesJson;
        $customer_package->food_beverages = $foodBeveragesJson;
        $customer_package->activities = $activitiesJson;
        $customer_package->safety_features = $safetyFeaturesJson;
        $customer_package->list_order = $request->input('list_order');

        $customer_package->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';

        // dd($request->location);

        // $location = $request->location ?? '';
        // $location = trim($location);
        // $location = str_replace('&nbsp;', ' ', $location); // Replace HTML spaces
        // $location = html_entity_decode($location);
        $location = $request->input('location');

        $customer_package->location =  json_encode($location);
        $customer_package->save();

        // Mail::to($customer_package->email) // or use a different recipient
        // ->send(new CustomerPackageNotification([
        //     'name'=> $customer_package->name,
        //     'phone_number'=> $customer_package->phone_number,
        //     'email'=> $customer_package->email,
        //     'package_type'=> $customer_package->package_type,
        //     'package_id'=> $customer_package->package_id
        // ]));

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
        $selectedgeo_featureId = $package_details->geo_feature;
        $selectedthemeId = $package_details->theme_id;
        $selectedCategoryId = $package_details->theme_cat_id;
        $selecteddesCategoryId = $package_details->destination_cat;
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
        ];

        return json_encode($data);
    }
}
