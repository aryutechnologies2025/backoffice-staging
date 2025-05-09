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

        $request->validate([
            'name' => 'required|string',
            // 'phone_number' => 'string|max:10',
            // 'email' => 'email',
            'package_type' => 'required|string',
        ]);

        $customer_package = new customer_package();
        $customer_package->name = ucfirst($request->name);
        $customer_package->phone_number = $request->phone_number;
        $customer_package->email = $request->email;
        $packageData = json_decode($request->package_type, true);
        $customer_package->package_id = $packageData['id'] ;
        $customer_package->package_type = $packageData['name'] ;


        $customer_package->package_inclusion = $request->input('program_inclusion');
        $customer_package->package_exclusion = $request->input('program_exclusion');



        $amenitiesJson = json_encode($request->input('amenity_services'));
        $foodBeveragesJson = json_encode($request->input('food_beverages'));
        $activitiesJson = json_encode($request->input('activities'));
        $safetyFeaturesJson = json_encode($request->input('safety_features'));
        $campRulesJson = json_encode($request->input('camp_rule'));

        $customer_package->camp_rule = $campRulesJson;
        $customer_package->important_info = $request->input('important_info');
        $tourPlanningJson = json_encode([
            // 'plan_title' => $request->input['plan_title'],
            // 'plan_subtitle' => $request->input['plan_subtitle'],
            'tour_planning' => $request->input('tour_planning')
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


        // dd($fileInputs);

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
            $customFileName = rand(1000, 9999) . '_' . time();
            // $customFileName = $customFileName . '_' . $randomSuffix;
            // Generate the final file name with extension
            $coverImageName = $customFileName . '_cover.' . $coverImage->getClientOriginalExtension();

            // Define the upload path
            $coverImagePath = 'uploads/events_package_images/';

            // dump($coverImagePath);
            // dd($coverImageName);


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
        $inclusive_packages->tour_planning = $tourPlanningJson;
        $inclusive_packages->start_date = $request->input('start_date');
        $inclusive_packages->return_date = $request->input('return_date');
        $inclusive_packages->total_days = $request->input('total_days');
        //$inclusive_packages->member_capacity = $request->input['member_capacity'];
        $inclusive_packages->price_tilte = json_encode($request->input('price_title', []));
        $inclusive_packages->price_amount = json_encode($request->input('price_amount', []));
        $inclusive_packages->camp_rule = $campRulesJson;
        $inclusive_packages->important_info = $request->input('important_info');
        //$inclusive_packages->google_map = $request->input['google_map'];
       $inclusive_packages->events_package_images = json_encode(array_values($imagePaths));
       // $inclusive_packages->total_room = $request->input['total_room'];
       // $inclusive_packages->bath_room = $request->input['bath_room'];
       // $inclusive_packages->bed_room = $request->input['bed_room'];
      //  $inclusive_packages->hall = $request->input['hall'];
        $inclusive_packages->amenity_details = $amenitiesJson;
        $inclusive_packages->food_beverages = $foodBeveragesJson;
        $inclusive_packages->activities = $activitiesJson;
        $inclusive_packages->safety_features = $safetyFeaturesJson;
        $inclusive_packages->list_order = $request->input('list_order');
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
}
