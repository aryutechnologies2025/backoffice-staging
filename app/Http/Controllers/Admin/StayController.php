<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activities;
use App\Models\Amenities;
use App\Models\City;
use App\Models\FoodBeverage;
use App\Models\Safetyfeatures;
use App\Models\stays_destination_details;
use App\Models\stays_whishlist;
use Illuminate\Http\Request;
use MongoDB\Operation\Find;

class StayController extends Controller
{
    public function list()
    {
        $stay_details = stays_destination_details::where('is_deleted', '0')->orderBy('created_at', 'desc')->get();
        return view('admin.stays.stays', compact('stay_details'));
    }

    public function add_form()
    {

        $cities = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        // $themes = Themes::where('status', "1")->where('is_deleted', "0")->pluck('themes_name', 'id');
        $amenities = Amenities::where('status', "1")->where('is_deleted', "0")->get();
        $foodBeverages = FoodBeverage::where('status', "1")->where('is_deleted', "0")->get();
        $activities = Activities::where('status', "1")->where('is_deleted', "0")->get();
        $safety_features = Safetyfeatures::where('status', "1")->where('is_deleted', "0")->get();
        return view('admin.stays.stayadd', compact('cities', 'amenities', 'foodBeverages', 'activities', 'safety_features'));
    }


    public function insert(Request $request)
    {

        $imagePaths = [];
        $fileInputs = $request->file();

        foreach ($fileInputs as $key => $files) {
            if (strpos($key, 'img_') === 0) {
                if (is_array($files)) {
                    foreach ($files as $file) {
                        if ($file->isValid()) {
                            $fileName = time() . '_' . $file->getClientOriginalName();
                            $destinationPath = public_path('/uploads/stays_module_img');
                            if (!file_exists($destinationPath)) {
                                mkdir($destinationPath, 0755, true);
                            }
                            $file->move($destinationPath, $fileName);
                            $imagePaths[] = '/uploads/stays_module_img/' . $fileName;
                        }
                    }
                } else {
                    if ($files->isValid()) {
                        $fileName = time() . '_' . $files->getClientOriginalName();
                        $destinationPath = public_path('/uploads/stays_module_img');
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        $files->move($destinationPath, $fileName);
                        $imagePaths[] = '/uploads/stays_module_img/' . $fileName;
                    }
                }
            }
        }

        $amenitiesJson = json_encode($request->input('amenity_services'));
        $foodBeveragesJson = json_encode($request->input('food_beverages'));
        $activitiesJson = json_encode($request->input('activities'));
        $safetyFeaturesJson = json_encode($request->input('safety_features'));

        $stay_details = new stays_destination_details();

        $stay_details->destination = $request->input('cities_name');
        $stay_details->stay_title = $request->input('title');
        $stay_details->stay_description = $request->input('program_description');
        $stay_details->stay_location = $request->input('stay_location');
        $stay_details->tag_line = $request->input('tag_line');

        $stay_details->gallery_image = json_encode($imagePaths);

        $stay_details->discount_price = $request->input('price_amount');
        $stay_details->actual_price = $request->input('actual_price_amount');
        $stay_details->no_of_days = $request->input('price_title');

        $stay_details->amenity_details = $amenitiesJson;
        $stay_details->food_beverages = $foodBeveragesJson;
        $stay_details->activities = $activitiesJson;
        $stay_details->safety_features = $safetyFeaturesJson;

        $stay_details->order = $request->input('list_order');
        $stay_details->is_deleted = '0';
        $stay_details->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $stay_details->created_date = now();
        $stay_details->created_by = 'admin';

        $stay_details->save();


        if ($stay_details) {
            return redirect()->route('admin.staylist')
                ->with('success', 'Record inserted successfully');
        } else {
            return redirect()->route('admin.staylist')
                ->with('error', 'Error inserting record');
        }
    }

   public function update(Request $request, $id)
{
    $stay_details = stays_destination_details::find($id);

    // $existingImages = json_decode($stay_details->gallery_image, true) ?? [];
    $existingImages = $request->input('existing_images', []);
    $imagePaths = $existingImages; 
    // $imagePaths = [];
    $fileInputs = $request->file();

    foreach ($fileInputs as $key => $files) {
        if (is_array($files)) {
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('/uploads/stays_module_img');
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }
                    $file->move($destinationPath, $fileName);
                    $imagePaths[] = '/uploads/stays_module_img/' . $fileName;
                }
            }
        } else {
            if ($files->isValid()) {
                $fileName = time() . '_' . $files->getClientOriginalName();
                $destinationPath = public_path('/uploads/stays_module_img');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $files->move($destinationPath, $fileName);
                $imagePaths[] = '/uploads/stays_module_img/' . $fileName;
            }
        }
    }

    if (!empty($imagePaths)) {
        $stay_details->gallery_image = json_encode($imagePaths);
    } else {
        $stay_details->gallery_image = json_encode($existingImages);
    }

    $amenitiesJson = json_encode($request->input('amenity_services'));
    $foodBeveragesJson = json_encode($request->input('food_beverages'));
    $activitiesJson = json_encode($request->input('activities'));
    $safetyFeaturesJson = json_encode($request->input('safety_features'));

    $stay_details->destination = $request->input('cities_name');
    $stay_details->stay_title = $request->input('title');
    $stay_details->stay_description = $request->input('description');
    $stay_details->stay_location = $request->input('stay_location');
    $stay_details->tag_line = $request->input('tag_line');

    $stay_details->discount_price = $request->input('price_amount');
    $stay_details->actual_price = $request->input('actual_price_amount');
    $stay_details->no_of_days = $request->input('price_title');

    $stay_details->amenity_details = $amenitiesJson;
    $stay_details->food_beverages = $foodBeveragesJson;
    $stay_details->activities = $activitiesJson;
    $stay_details->safety_features = $safetyFeaturesJson;

    $stay_details->order = $request->input('list_order');
    $stay_details->is_deleted = '0';
    $stay_details->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
    $stay_details->created_date = now();
    $stay_details->created_by = 'admin';

    $stay_details->save();

    if ($stay_details) {
        return redirect()->route('admin.staylist')
            ->with('success', 'Record updated successfully');
    } else {
        return redirect()->route('admin.staylist')
            ->with('error', 'Error updating record');
    }
}


    public function delete(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');

        // Find the admin record by ID
        $stay_details = stays_destination_details::find($record_id);
        if ($stay_details) {
            // Update the is_deleted field to 1
            $stay_details->is_deleted = "1";



            $stay_details->save();

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


    public function edit_form(Request $request, $id)
    {
        $stay_details = stays_destination_details::find($id);
        $cities_dts = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        $amenities_dts = Amenities::where('status', "1")->where('is_deleted', "0")->get();
        $foodBeverages_dts = FoodBeverage::where('status', "1")->where('is_deleted', "0")->get();
        $activities_dts = Activities::where('status', "1")->where('is_deleted', "0")->get();
        $safety_features_dts = Safetyfeatures::where('status', "1")->where('is_deleted', "0")->get();


        if (!$stay_details) {
            return redirect()->route('admin.inclusive_package_list')->with('error', 'Package not found');
        }

        $selectedAmenities = json_decode($stay_details->amenity_details, true) ?? [];
        $selectedfood_beverages = json_decode($stay_details->food_beverages, true) ?? [];
        $selectedactivities = json_decode($stay_details->activities, true) ?? [];
        $selectedsafety_features = json_decode($stay_details->safety_features, true) ?? [];

        $selectedprogram = json_decode($stay_details->category, true) ?? [];
        // Get the selected city ID
        $selectedCityId = $stay_details->city_details;
        $selectedgeo_featureId = $stay_details->geo_feature;
        $selectedthemeId = $stay_details->theme_id;
        $selectedCategoryId = $stay_details->theme_cat_id;
        $selecteddesCategoryId = $stay_details->destination_cat;


        return view('admin.stays.stayedit', compact('stay_details', 'cities_dts', 'amenities_dts', 'foodBeverages_dts', 'activities_dts', 'safety_features_dts', 'selectedCityId', 'selectedAmenities', 'selectedthemeId', 'selectedfood_beverages', 'selectedactivities', 'selectedsafety_features', 'selectedgeo_featureId',    'selecteddesCategoryId', 'selectedCategoryId', 'selectedprogram'));
    }

    public function change_status(Request $request)
    {

        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $stay_details = stays_destination_details::find($record_id);

        if ($stay_details) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $stay_details->status = "0";
            } else {
                $stay_details->status = "1";
            }



            $stay_details->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'status changed successfully.'
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


    public function get_stays(Request $request)
    {
        $destination = $request->destination;

        $stays = stays_destination_details::where('is_deleted', '0')
            ->where('destination', $destination)->orderBy('created_at', 'desc')->get()
            ->map(function ($items) {
                return [
                    'id' => $items->id,
                    'images' => json_decode($items->gallery_image),
                    'destination' => $items->destination,
                    'stay_title' => $items->stay_title,
                    'stay_description' => $items->stay_description,
                    'stay_location' => $items->stay_location,
                    'stay_title' => $items->stay_title,
                    'actual_price' => $items->actual_price,
                    'discount_price' => $items->discount_price,
                    'no_of_days' => $items->no_of_days,
                    'tag_line' => $items->tag_line,
                ];
            });
        return response()->json([
            'status' => 'success',
            'message' => 'stays successfully retrived.',
            'data' => $stays
        ], 200);
    }


    public function get_stay_details(Request $request)
    {
        $programId = $request->program_id; 
        $userId = $request->user_id;

        $stay_details = stays_destination_details
            ::where('is_deleted', '0')
            ->find($programId);


        if (!$stay_details) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stay details not found.',
                'data' => null
            ], 404);
        }

        $stags = stays_destination_details
            ::where('is_deleted', '0')
            ->where('id', $programId)->get()
            ->map(function ($items) {
                return [
                    'images' => json_decode($items->gallery_image),
                    'destination' => $items->destination,
                    'stay_title' => $items->stay_title,
                    'stay_description' => $items->stay_description,
                    'stay_location' => $items->stay_location,
                    'stay_title' => $items->stay_title,
                    'actual_price' => $items->actual_price,
                    'discount_price' => $items->discount_price,
                    'no_of_days' => $items->no_of_days,
                    'tag_line' => $items->tag_line,
                ];
            });

        $amenityIds = json_decode($stay_details->amenity_details, true) ?? [];

        $amenities = Amenities::whereIn('id', $amenityIds)
            ->get(['id', 'amenity_name', 'amenity_pic'])
            ->keyBy('id')
            ->map(function ($item) {
                return [
                    'amenity_name' => $item->amenity_name,
                    'amenity_pic' => $item->amenity_pic,
                ];
            })->values();

        $activitiesIds = json_decode($stay_details->activities, true) ?? [];

        $activities = Activities::whereIn('id', $activitiesIds)
            ->get(['id', 'activities', 'activities_pic'])
            ->keyBy('id')
            ->map(function ($item) {
                return [
                    'activities' => $item->activities,
                    'activities_pic' => $item->activities_pic,
                ];
            })->values();

        $safetyIds = json_decode($stay_details->safety_features, true) ?? [];

        $safety = Safetyfeatures::whereIn('id', $safetyIds)
            ->get(['id', 'safety_features', 'safety_features_pic'])
            ->keyBy('id')
            ->map(function ($item) {
                return [
                    'safety_features' => $item->safety_features,
                    'safety_features_pic' => $item->safety_features_pic,
                ];
            })->values();


        $stay_wishlist = stays_whishlist::where("stay_id",$programId)->where("user_id",$userId)->first();
        $wishlist = false;
        if($stay_wishlist){
            $wishlist = true;
        }



        // Return response
        return response()->json([
            'status' => 'success',
            'message' => 'Stays successfully retrieved.',
            'data' => $stags,
            'amenities' => $amenities,
            'activities' => $activities,
            'safety' => $safety,
            'wishlist' => $wishlist
        ], 200);
    }
}
