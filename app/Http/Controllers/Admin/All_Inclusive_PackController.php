<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\InclusivePackages;
use App\Models\City;
use App\Models\Amenities;
use App\Models\FoodBeverage;
use App\Models\Activities;
use App\Models\Safetyfeatures;
use App\Models\Geo_feature;
use App\Models\Themes;
use App\Models\Themes_category;
use App\Models\Destination_category;
use App\Models\Address;



class All_Inclusive_PackController extends Controller
{


    public function list(Request $request)
    {
        $title = 'Programs List';
        $inclusive_packages = InclusivePackages::where('is_deleted', '0')->orderBy('created_at', 'desc')->get();
        foreach ($inclusive_packages as $package) {
            $package->category = json_decode($package->category, true);
            // Decode other JSON fields if needed
        }
        return view('admin.inclusive_packages.inclusive_packageslist', compact('title', 'inclusive_packages'));
    }

    public function add_form()
    {
        $title = 'ADD PROGRAM';
        $cities = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        $themes = Themes::where('status', "1")->where('is_deleted', "0")->pluck('themes_name', 'id');
        $amenities = Amenities::where('status', "1")->where('is_deleted', "0")->get();
        $address = Address::where('is_deleted', "0")->get();
        $foodBeverages = FoodBeverage::where('status', "1")->where('is_deleted', "0")->get();
        $activities = Activities::where('status', "1")->where('is_deleted', "0")->get();
        $safety_features = Safetyfeatures::where('status', "1")->where('is_deleted', "0")->get();
        // $geo_feature = Geo_feature::where('status', "1")->where('is_deleted', "0")->pluck('geo_feature', 'id');

        return view('admin.inclusive_packages.inclusive_packagesadd', compact('title', 'cities', 'themes', 'amenities', 'foodBeverages', 'activities', 'safety_features', 'address' ));
    }


    public function getThemeCategories($themeId)
    {
        // Assuming you have a ThemeCategory model
        $categories = Themes_category::where('theme_id', $themeId)
            ->where('status', "1") // Add condition for status
            ->where('is_deleted', "0") // Add condition for is_deleted
            ->pluck('theme_cat', 'id');

        return response()->json($categories);
    }

    public function getDestinationCategories(Request $request)
    {
        $cityId = $request->input('city_id');

        // Assuming you have a DestinationCategory model with a city_id foreign key
        $categories = Destination_category::where('destination_id', $cityId)->where('status', "1")->where('is_deleted', "0")->pluck('destination_cat', 'id');

        return response()->json($categories);
    }



public function insert(Request $request)
{
    // Validate request data
    $validatedData = $request->validate([
        'themes_name' => 'required',
        'cities_name' => 'required',
        'title' => 'required',
        'program_description' => 'required',
        'plan_title' => 'required',
        'plan_subtitle' => 'required',
        'plan_description' => 'required',
        'total_days' => 'required',
        'total_room' => 'required',
        'bath_room' => 'required',
        'bed_room' => 'required',
        'hall' => 'required',
        'member_capacity' => 'required',
        'price' => 'required',
        'actual_price' => 'required',
        'camp_rule' => 'required',
        'important_info' => 'required',
        'google_map' => 'required',
    ]);

    // Handle dynamic image uploads
    $imagePaths = [];
    $fileInputs = $request->file();

    foreach ($fileInputs as $key => $files) {
        if (strpos($key, 'img_') === 0) {
            if (is_array($files)) {
                foreach ($files as $file) {
                    if ($file->isValid()) {
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $destinationPath = public_path('/uploads/events_package_images');
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        $file->move($destinationPath, $fileName);
                        $imagePaths[] = '/uploads/events_package_images/' . $fileName;
                    }
                }
            } else {
                if ($files->isValid()) {
                    $fileName = time() . '_' . $files->getClientOriginalName();
                    $destinationPath = public_path('/uploads/events_package_images');
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }
                    $files->move($destinationPath, $fileName);
                    $imagePaths[] = '/uploads/events_package_images/' . $fileName;
                }
            }
        }
    }

    // Additional file handling for cover image
    $filePath1 = null;
    if ($request->hasFile('cover_img')) {
        $file1 = $request->file('cover_img');
        $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name', 'default_image_name'));
        $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
        $file1->move(public_path('/uploads/events_package_images'), $filename1);
        $filePath1 = '/uploads/events_package_images/' . $filename1;
    }

    // Cache the tour planning data
    $tourPlanningJson = Cache::remember("tour_planning_{$validatedData['title']}", 3600, function () use ($validatedData) {
        return json_encode([
            'plan_title' => $validatedData['plan_title'],
            'plan_subtitle' => $validatedData['plan_subtitle'],
            'plan_description' => $validatedData['plan_description']
        ]);
    });

    // Prepare other JSON fields
    $amenitiesJson = json_encode($request->input('amenity_services'));
    $foodBeveragesJson = json_encode($request->input('food_beverages'));
    $activitiesJson = json_encode($request->input('activities'));
    $safetyFeaturesJson = json_encode($request->input('safety_features'));
    $campRulesJson = json_encode($request->input('camp_rule'));
    $addressJson = json_encode($request->input('address_services'));

    // Insert into MySQL
    $inclusive_packages = new InclusivePackages();
    $inclusive_packages->program_inclusion = $request->input('program_inclusion');
    $inclusive_packages->break_fast = $request->input('break_fast');
    $inclusive_packages->lunch = $request->input('lunch');
    $inclusive_packages->dinner = $request->input('dinner');
    $inclusive_packages->upload_image_name = $request->input('upload_image_name');
    $inclusive_packages->alternate_name = $request->input('alternate_image_name');
    $inclusive_packages->theme_id = $request->input('themes_name');
    $inclusive_packages->city_details = $validatedData['cities_name'];
    $inclusive_packages->title = $validatedData['title'];
    $inclusive_packages->program_description = $validatedData['program_description'];
    $inclusive_packages->category = json_encode($request->input('prop_cat'));
    $inclusive_packages->address = $addressJson;
    $inclusive_packages->tour_planning = $tourPlanningJson;
    $inclusive_packages->start_date = $request->input('start_date');
    $inclusive_packages->return_date = $request->input('return_date');
    $inclusive_packages->total_days = $validatedData['total_days'];
    $inclusive_packages->member_capacity = $validatedData['member_capacity'];
    $inclusive_packages->price = $validatedData['price'];
    $inclusive_packages->actual_price = $validatedData['actual_price'];
    $inclusive_packages->camp_rule = $campRulesJson;
    $inclusive_packages->important_info = $validatedData['important_info'];
    $inclusive_packages->google_map = $validatedData['google_map'];
    $inclusive_packages->events_package_images = json_encode($imagePaths);
    $inclusive_packages->cover_img = $filePath1;
    $inclusive_packages->total_room = $validatedData['total_room'];
    $inclusive_packages->bath_room = $validatedData['bath_room'];
    $inclusive_packages->bed_room = $validatedData['bed_room'];
    $inclusive_packages->hall = $validatedData['hall'];
    $inclusive_packages->amenity_details = $amenitiesJson;
    $inclusive_packages->food_beverages = $foodBeveragesJson;
    $inclusive_packages->activities = $activitiesJson;
    $inclusive_packages->safety_features = $safetyFeaturesJson;
    $inclusive_packages->is_deleted = '0';
    $inclusive_packages->created_date = now();
    $inclusive_packages->created_by = 'admin';
    $inclusive_packages->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
    $inclusive_packages->updated_at = null;
    $inclusive_packages->save();

    // Cache the inserted record
    Cache::put("inclusive_package_{$inclusive_packages->id}", $inclusive_packages, 3700);

    if ($inclusive_packages) {
        return redirect()->route('admin.inclusive_package_list')
            ->with('success', 'Record inserted successfully');
    } else {
        return redirect()->route('admin.inclusive_package_list')
            ->with('error', 'Error inserting record');
    }
}


    public function edit_form(Request $request, $id)
    {
        $package_details = InclusivePackages::find($id);
        $cities_dts = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        $amenities_dts = Amenities::where('status', "1")->where('is_deleted', "0")->get();
        $address_dts = Address::where('is_deleted', "0")->get();
        $foodBeverages_dts = FoodBeverage::where('status', "1")->where('is_deleted', "0")->get();
        $activities_dts = Activities::where('status', "1")->where('is_deleted', "0")->get();
        $safety_features_dts = Safetyfeatures::where('status', "1")->where('is_deleted', "0")->get();
        $geo_feature_dts = Geo_feature::where('status', "1")->where('is_deleted', "0")->pluck('geo_feature', 'id');
        $themes = Themes::where('status', "1")->where('is_deleted', "0")->pluck('themes_name', 'id');
        if (!$package_details) {
            return redirect()->route('admin.inclusive_package_list')->with('error', 'Package not found');
        }

        $selectedAmenities = json_decode($package_details->amenity_details, true) ?? [];
        $selectedAddress = json_decode($package_details->address, true) ?? [];
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
//         echo"<pre>";
// print_r($package_details);die;
        return view('admin.inclusive_packages.inclusive_packagesedit', compact('package_details', 'title', 'cities_dts', 'themes', 'amenities_dts', 'foodBeverages_dts', 'activities_dts', 'safety_features_dts', 'selectedCityId', 'selectedAmenities', 'selectedthemeId', 'selectedfood_beverages', 'selectedactivities', 'selectedsafety_features', 'geo_feature_dts', 'selectedgeo_featureId', 'categories', 'dest_categories', 'selecteddesCategoryId', 'selectedCategoryId','selectedprogram','selectedAddress','address_dts'));
    }

    
    public function update(Request $request, $id)
{
    // Validate the incoming data
    $validatedData = $request->validate([
        'themes_name' => 'required',
        'cities_name' => 'required',
        'title' => 'required',
        'program_description' => 'required',
        'plan_title' => 'required',
        'plan_subtitle' => 'required',
        'plan_description' => 'required',
        'total_days' => 'required',
        'member_capacity' => 'required',
        'price' => 'required',
        'actual_price' => 'required',
        'camp_rule' => 'required',
        'important_info' => 'required',
        'google_map' => 'required',
        'total_room' => 'required',
        'bath_room' => 'required',
        'bed_room' => 'required',
        'hall' => 'required',
    ]);

    // Find the record to update
    $inclusive_packages = InclusivePackages::find($id);
    if (!$inclusive_packages) {
        return redirect()->route('admin.inclusive_package_list')
            ->with('error', 'Record not found');
    }

    // Handle dynamic image uploads
    $imagePaths = $inclusive_packages->events_package_images ? json_decode($inclusive_packages->events_package_images, true) : [];
    $fileInputs = $request->file();

    foreach ($fileInputs as $key => $files) {
        if (strpos($key, 'img_') === 0) {
            if (is_array($files)) {
                foreach ($files as $file) {
                    if ($file->isValid()) {
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $destinationPath = public_path('/uploads/events_package_images');

                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }

                        $file->move($destinationPath, $fileName);
                        $imagePaths[] = '/uploads/events_package_images/' . $fileName;
                    }
                }
            } else {
                if ($files->isValid()) {
                    $fileName = time() . '_' . $files->getClientOriginalName();
                    $destinationPath = public_path('/uploads/events_package_images');

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    $files->move($destinationPath, $fileName);
                    $imagePaths[] = '/uploads/events_package_images/' . $fileName;
                }
            }
        }
    }

    // Handle cover image upload
    if ($request->hasFile('cover_img')) {
        $coverImage = $request->file('cover_img');
        $coverImageName = time() . '_cover.' . $coverImage->getClientOriginalExtension();
        $coverImagePath = public_path('/uploads/events_package_images');
        $coverImage->move($coverImagePath, $coverImageName);
        $inclusive_packages->cover_img = '/uploads/events_package_images/' . $coverImageName;
    }

    // JSON encode complex fields
    $tourPlanningJson = json_encode([
        'plan_title' => $validatedData['plan_title'],
        'plan_subtitle' => $validatedData['plan_subtitle'],
        'plan_description' => $validatedData['plan_description']
    ]);
    $amenitiesJson = json_encode($request->input('amenity_services', []));
    $addressJson = json_encode($request->input('address_services', []));
    $foodBeveragesJson = json_encode($request->input('food_beverages', []));
    $activitiesJson = json_encode($request->input('activities', []));
    $safetyFeaturesJson = json_encode($request->input('safety_features', []));
    $campRulesJson = json_encode($validatedData['camp_rule']);

    // Update the model fields
    $inclusive_packages->theme_id = $request->input('themes_name');
    $inclusive_packages->city_details = $validatedData['cities_name'];
    $inclusive_packages->title = $validatedData['title'];
    $inclusive_packages->program_description = strip_tags($validatedData['program_description'], '<p><a><b><i><ul><ol><li><br>');
    $inclusive_packages->category = json_encode($request->input('prop_cat', []));
    $inclusive_packages->address = $addressJson;
    $inclusive_packages->tour_planning = $tourPlanningJson;
    $inclusive_packages->start_date = $request->input('start_date');
    $inclusive_packages->return_date = $request->input('return_date');
    $inclusive_packages->total_days = $validatedData['total_days'];
    $inclusive_packages->member_capacity = $validatedData['member_capacity'];
    $inclusive_packages->member_type = $request->input('mem_type', 'default');
    $inclusive_packages->price = $validatedData['price'];
    $inclusive_packages->actual_price = $validatedData['actual_price'];
    $inclusive_packages->camp_rule = $campRulesJson;
    $inclusive_packages->important_info = $validatedData['important_info'];
    $inclusive_packages->google_map = $validatedData['google_map'];
    $inclusive_packages->events_package_images = json_encode($imagePaths);
    $inclusive_packages->total_room = $validatedData['total_room'];
    $inclusive_packages->bath_room = $validatedData['bath_room'];
    $inclusive_packages->bed_room = $validatedData['bed_room'];
    $inclusive_packages->hall = $validatedData['hall'];
    $inclusive_packages->amenity_details = $amenitiesJson;
    $inclusive_packages->food_beverages = $foodBeveragesJson;
    $inclusive_packages->activities = $activitiesJson;
    $inclusive_packages->safety_features = $safetyFeaturesJson;
    $inclusive_packages->is_deleted = '0';
    $inclusive_packages->updated_at = now();
    $inclusive_packages->created_by = 'admin';
    $inclusive_packages->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';

    // Save the updated record
    $inclusive_packages->save();

    // Redirect with success message
    return redirect()->route('admin.inclusive_package_list')
        ->with('success', 'Record updated successfully');
}

    

    public function change_status(Request $request)
    {

        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $inclusive_packages = InclusivePackages::find($record_id);

        if ($inclusive_packages) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $inclusive_packages->status = "0";
            } else {
                $inclusive_packages->status = "1";
            }

            // Update the updated_date field
            $inclusive_packages->updated_date = date('Y-m-d H:i:s');
            $inclusive_packages->status_changed_by = 'admin';
            $inclusive_packages->save();

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

    public function delete(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');

        // Find the admin record by ID
        $inclusive_packages = InclusivePackages::find($record_id);
        if ($inclusive_packages) {
            // Update the is_deleted field to 1
            $inclusive_packages->is_deleted = "1";

            // Set the updated_date field
            $inclusive_packages->updated_date = date('Y-m-d H:i:s');
            $inclusive_packages->deleted_by = 'admin';
            // Save the changes
            $inclusive_packages->save();

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
// public function showDashboard()
// {
//     // Fetch count of InclusivePackages where 'is_deleted' is '0'
//     $programCount = InclusivePackages::where('is_deleted', '0')->count();

//     // Pass the count to the view
//     return view('dashboard.dashboard', ['programCount' => $programCount]);  // Pass using array
// }

}
