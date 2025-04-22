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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class All_Inclusive_PackController extends Controller
{


    public function deleteImage(Request $request)
    {
        $imagePath = $request->input('image_path');
        Log::info('Attempting to delete image:', ['image_path' => $imagePath]);

        if ($imagePath && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
            return response()->json(['success' => true]);
        }

        Log::error('Image deletion failed:', ['image_path' => $imagePath]);
        return response()->json(['success' => false, 'message' => 'Image not found or already deleted.']);
    }


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
        $title = 'Add Program';
        $cities = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        $themes = Themes::where('status', "1")->where('is_deleted', "0")->pluck('themes_name', 'id');
        $amenities = Amenities::where('status', "1")->where('is_deleted', "0")->get();
        $foodBeverages = FoodBeverage::where('status', "1")->where('is_deleted', "0")->get();
        $activities = Activities::where('status', "1")->where('is_deleted', "0")->get();
        $safety_features = Safetyfeatures::where('status', "1")->where('is_deleted', "0")->get();
        // $geo_feature = Geo_feature::where('status', "1")->where('is_deleted', "0")->pluck('geo_feature', 'id');

        return view('admin.inclusive_packages.inclusive_packagesadd', compact('title', 'cities', 'themes', 'amenities', 'foodBeverages', 'activities', 'safety_features'));
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

        // dd($request->all());
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
        // $tourPlanningJson = Cache::remember("tour_planning_{$request->input('title')}", 3600, function () use ($request) {
        //     return json_encode([
        //         'plan_description' => $request->input('plan_description')
        //     ]);
        // });

        $tourPlanningJson = Cache::remember("tour_planning_{$request->input('title')}", 3600, function () use ($request) {
            $planDescription = $request->input('plan_description');
        
            // If plan_description is null or empty, return an empty JSON array
            if (empty($planDescription)) {
                return json_encode([]);
            }
        
            return json_encode([
                'plan_description' => $planDescription
            ]);
        });

        $filename = null;
        if ($request->hasFile('program_pdf')) {
            $file = $request->file('program_pdf');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('uploads/program_pdfs'), $filename);
        }

        // Prepare other JSON fields
        $amenitiesJson = json_encode($request->input('amenity_services'));
        $foodBeveragesJson = json_encode($request->input('food_beverages'));
        $activitiesJson = json_encode($request->input('activities'));
        $safetyFeaturesJson = json_encode($request->input('safety_features'));
        $campRulesJson = json_encode($request->input('camp_rule'));

        // Insert into MySQL
        $inclusive_packages = new InclusivePackages();
        $inclusive_packages->program_pdf = $filename;
        $inclusive_packages->program_inclusion = $request->input('program_inclusion');
        $inclusive_packages->program_exclusion = $request->input('program_exclusion');

        $inclusive_packages->break_fast = $request->input('break_fast');
        $inclusive_packages->lunch = $request->input('lunch');
        $inclusive_packages->dinner = $request->input('dinner');
        $inclusive_packages->upload_image_name = $request->input('upload_image_name');
        $inclusive_packages->alternate_name = $request->input('alternate_image_name');
        $inclusive_packages->theme_id = $request->input('themes_name');
        $inclusive_packages->city_details = $request->input('cities_name');
        $inclusive_packages->title = $request->input('title');
        $inclusive_packages->program_description = $request->input('program_description');
        $inclusive_packages->address = $request->input('address') ?? '';
        $inclusive_packages->category = json_encode($request->input('prop_cat'));
        $inclusive_packages->location = $request->input('location');
       $inclusive_packages->tour_planning = $tourPlanningJson;
        $inclusive_packages->start_date = $request->input('start_date');
        $inclusive_packages->return_date = $request->input('return_date');
        $inclusive_packages->total_days = $request->input('total_days');
        $inclusive_packages->member_capacity = $request->input('member_capacity');
        $inclusive_packages->price = $request->input('price');
        $inclusive_packages->actual_price = $request->input('actual_price');
        $inclusive_packages->camp_rule = $campRulesJson;
        $inclusive_packages->important_info = $request->input('important_info');
        $inclusive_packages->google_map = $request->input('google_map');
        $inclusive_packages->events_package_images = json_encode($imagePaths);
        $inclusive_packages->cover_img = $filePath1;
        $inclusive_packages->total_room = $request->input('total_room');
        $inclusive_packages->bath_room = $request->input('bath_room');
        $inclusive_packages->bed_room = $request->input('bed_room');
        $inclusive_packages->hall = $request->input('hall');
        $inclusive_packages->price_tilte = json_encode($request->input('price_title', []));
        $inclusive_packages->price_amount = json_encode($request->input('price_amount', []));
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
        Log::info('Record inserted successfully:', ['id' => $inclusive_packages->id]);

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
        //         echo"<pre>";
        // print_r($package_details);die;
        return view('admin.inclusive_packages.inclusive_packagesedit', compact('package_details', 'title', 'cities_dts', 'themes', 'amenities_dts', 'foodBeverages_dts', 'activities_dts', 'safety_features_dts', 'selectedCityId', 'selectedAmenities', 'selectedthemeId', 'selectedfood_beverages', 'selectedactivities', 'selectedsafety_features', 'geo_feature_dts', 'selectedgeo_featureId', 'categories', 'dest_categories', 'selecteddesCategoryId', 'selectedCategoryId', 'selectedprogram'));
    }


    public function update(Request $request, $id)
    {

        // dd($request->all());
        // Validate the incoming data
        // $validatedData = $request->validate([
        //     'themes_name' => 'required',
        //     'cities_name' => 'required',
        //     'title' => 'required',
        //     'program_description' => 'required',
        //     'plan_title' => 'required',
        //     'plan_subtitle' => 'required',
        //     'plan_description' => 'required',
        //     'total_days' => 'required',
        //     'member_capacity' => 'required',
        //     'price' => 'required',
        //     'actual_price' => 'required',
        //     'camp_rule' => 'required',
        //     'important_info' => 'required',
        //     'google_map' => 'required',
        //     'total_room' => 'required',
        //     'bath_room' => 'required',
        //     'bed_room' => 'required',
        //     'hall' => 'required',
        //     'program_pdf' => 'file|max:10240'
        // ]);

        // Find the record to update
        $inclusive_packages = InclusivePackages::find($id);
        if (!$inclusive_packages) {
            return redirect()->route('admin.inclusive_package_list')
                ->with('error', 'Record not found');
        }

        // Get current images for deletion tracking
        $currentImages = json_decode($inclusive_packages->events_package_images, true);
        if (!is_array($currentImages)) {
            $currentImages = [];
        }

        // Handle dynamic image uploads
        $imagePaths = $currentImages; // Start with existing images
        $fileInputs = $request->file();

        // Track deleted images
        $deletedImages = $request->input('deleted_images', []);
        $deletedImages = json_decode($deletedImages, true); // Decode the list of deleted images
        foreach ($deletedImages as $deletedImage) {
            if (in_array($deletedImage, $currentImages)) {
                // Delete the image from the filesystem
                $oldImagePath = public_path($deletedImage);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                // Remove the image from the imagePaths array
                $imagePaths = array_filter($imagePaths, fn($path) => $path !== $deletedImage);
            }
        }



        // Handle new image uploads
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


        // if ($request->hasFile('cover_img')) {


        if ($request->hasFile('cover_img')) {
            // Get the uploaded file
            $coverImage = $request->file('cover_img');

            // Sanitize the file name
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name', 'default_image_name'));

            // Generate the final file name with extension
            $coverImageName = $customFileName . '_cover.' . $coverImage->getClientOriginalExtension();

            // Define the upload path
            $coverImagePath = 'uploads/events_package_images/';

            // Move the file to the desired location
            $coverImage->move(public_path($coverImagePath), $coverImageName);

            // Save the file path in the database
            $inclusive_packages->cover_img = $coverImagePath . $coverImageName;
        }


        // JSON encode complex fields
        $tourPlanningJson = json_encode([
            // 'plan_title' => $request->input['plan_title'],
            // 'plan_subtitle' => $request->input['plan_subtitle'],
            'plan_description' => $request->input('plan_description')
        ]);


        //storing the program_pdf file upload
        $filename = null;
        if ($request->hasFile('program_pdf')) {
            $file = $request->file('program_pdf');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;

            $file->move(public_path('uploads/program_pdfs'), $filename);
            $inclusive_packages->program_pdf = $filename;
        }



        $amenitiesJson = json_encode($request->input('amenity_services', []));
        $foodBeveragesJson = json_encode($request->input('food_beverages', []));
        $activitiesJson = json_encode($request->input('activities', []));
        $safetyFeaturesJson = json_encode($request->input('safety_features', []));
        $campRulesJson = json_encode($request->input('camp_rule'));

        // Update the model fields
        // $inclusive_packages->program_pdf = $filePath;
        $inclusive_packages->upload_image_name = $request->input('upload_image_name');
        $inclusive_packages->alternate_name = $request->input('alternate_image_name');
        $inclusive_packages->program_inclusion = $request->input('program_inclusion');
        $inclusive_packages->program_exclusion = $request->input('program_exclusion');
        $inclusive_packages->theme_id = $request->input('themes_name');
        $inclusive_packages->location = $request->input('location');
        $inclusive_packages->city_details = $request->input('cities_name');
        $inclusive_packages->title = $request->input('title');
        $inclusive_packages->program_description = $request->input('program_description');
        $inclusive_packages->category = json_encode($request->input('prop_cat', []));
        //$inclusive_packages->tour_planning = $tourPlanningJson;
        $inclusive_packages->start_date = $request->input('start_date');
        $inclusive_packages->return_date = $request->input('return_date');
        $inclusive_packages->total_days = $request->input('total_days');
        //$inclusive_packages->member_capacity = $request->input['member_capacity'];
        $inclusive_packages->price_tilte = json_encode($request->input('price_title', []));
        $inclusive_packages->price_amount = json_encode($request->input('price_amount', []));
        $inclusive_packages->camp_rule = $campRulesJson;
        $inclusive_packages->important_info = $request->input('important_info');
        //$inclusive_packages->google_map = $request->input['google_map'];
       // $inclusive_packages->events_package_images = json_encode(array_values($imagePaths));
       // $inclusive_packages->total_room = $request->input['total_room'];
       // $inclusive_packages->bath_room = $request->input['bath_room'];
       // $inclusive_packages->bed_room = $request->input['bed_room'];
      //  $inclusive_packages->hall = $request->input['hall'];
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
