<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\City;
use App\Models\Amenities;
use App\Models\FoodBeverage;
use App\Models\Activities;
use App\Models\Safetyfeatures;

class PropertyController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Property List';
        $property_dts = Property::where('is_deleted', '0')->paginate(10);
        return view('admin.property.propertylist', compact('title', 'property_dts'));
    }

    public function add_form()
    {
        $title = 'Property Add';
        $cities = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        $amenities = Amenities::where('status', "1")->where('is_deleted', "0")->get();
        $foodBeverages = FoodBeverage::where('status', "1")->where('is_deleted', "0")->get();
        $activities = Activities::where('status', "1")->where('is_deleted', "0")->get();
        $safety_features = Safetyfeatures::where('status', "1")->where('is_deleted', "0")->get();

        return view('admin.property.propertyadd', compact('title', 'cities', 'amenities', 'foodBeverages', 'activities', 'safety_features'));
    }

    public function insert(Request $request)
    {

        // Validate request data
        $validatedData = $request->validate([
            'property_title' => 'required',
            'property_type' => 'required',
            'prop_cat' => 'required',
            'type' => 'required',
            'cities_name' => 'required',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'country' => 'required',
            'plan_title' => 'required',
            'plan_description' => 'required',
            'start_date' => 'required',
            'return_date' => 'required',
            'total_days' => 'required',
            'total_room' => 'required',
            'member_capacity' => 'required',
            'price' => 'required',
            'coupon_code' => 'required',
            'camp_rule' => 'required',
            'important_info' => 'required',
            'amenity_services' => 'required',
            'food_beverages' => 'required',
            'activities' => 'required',
            'safety_features' => 'required',
            'geo_feature' => 'required',
        ]);

        // Handle dynamic image uploads
        $imagePaths = [];
        if ($request->hasFile('property_images')) {
            foreach ($request->file('property_images') as $file) {
                if ($file->isValid()) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('/uploads/property_images');

                    // Ensure the directory exists
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    // Move the file to the destination path
                    $file->move($destinationPath, $fileName);

                    // Store the file path
                    $imagePaths[] = '/uploads/property_images/' . $fileName;
                }
            }
        }


        $tourPlanningJson = json_encode([
            'plan_title' => $validatedData['plan_title'],
            'plan_description' => $validatedData['plan_description']
        ]);






        // Insert into MySQL
        $property = new Property();
        $property->property_title = $validatedData['property_title'];
        $property->property_type = $validatedData['property_type'];
        $property->prop_cat = $validatedData['prop_cat'];
        $property->type = $validatedData['type'];
        $property->state = $validatedData['state'];
        $property->city = $validatedData['city'];
        $property->city_details = $validatedData['cities_name'];
        $property->address = $validatedData['address'];
        $property->country = $validatedData['country'];
        $property->geo_feature = $validatedData['geo_feature'];
        $property->tour_planning = $tourPlanningJson;
        $property->property_images = json_encode($imagePaths);
        $property->start_date = $validatedData['start_date'];
        $property->return_date = $validatedData['return_date'];
        $property->total_days = $validatedData['total_days'];
        $property->total_room = $validatedData['total_room'];
        $property->member_capacity = $validatedData['member_capacity'];
        $property->price = $validatedData['price'];
        $property->coupon_code = $validatedData['coupon_code'];
        $property->camp_rule = json_encode($validatedData['camp_rule']);
        $property->important_info = $validatedData['important_info'];
        $property->is_deleted = '0';
        $property->created_date = now();
        $property->created_by = 'admin';
        $property->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $property->is_featured = $request->has('is_featured') && $request->input('is_featured') === 'on' ? 'yes' : 'no';
        $property->save();


        if ($property) {
            return redirect()->route('admin.property_list')
                ->with('success', 'Property inserted successfully');
        } else {
            return redirect()->route('admin.property_list')
                ->with('error', 'Error inserting property');
        }
    }
}
