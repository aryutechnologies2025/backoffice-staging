<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\stay_desitination;
use App\Models\stay_district;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class StayDistrictController extends Controller
{
    public function list()
    {
        $title = 'Stay District List';
        $destination_dts = stay_district::with('city')->where('is_deleted', "0")->get();

        return view('admin.stay_district.staydistrictlist', compact('title', 'destination_dts'));
    }

    public function add_form()
    {
        $title = 'Add District';
        // $destination_dts = stay_desitination::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');

        $destination_dts = City::where('status', '1')->where('is_deleted', '0')->pluck('city_name', 'id');
        return view('admin.stay_district.staydistrictsadd', compact('title', 'destination_dts'));
    }


    public function store(Request $request)
    {
        // First check if districts data exists
        if (!$request->has('districts') || !is_array($request->districts)) {
            return redirect()->back()
                ->with('error', 'Invalid districts data')
                ->withInput();
        }

        $validatedData = [];

        foreach ($request->districts as $index => $districtData) {
            // Skip empty entries
            if (empty($districtData)) continue;

            $validator = Validator::make($districtData, [
                'destination' => 'required|string|max:255',
                'image' => 'nullable',
                'description' => 'nullable|string',
                'is_featured' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validatedData[$index] = $validator->validated();
        }

        // Check if we have any valid districts
        if (empty($validatedData)) {
            return redirect()->back()
                ->with('error', 'No valid district data provided')
                ->withInput();
        }

        // Process image uploads
        $processedData = [];

        foreach ($validatedData as $district) {
            $imagePath = $district['existing_image'] ?? null;

            // Handle new image upload
            if (!empty($district['image']) && $district['image']->isValid()) {
                // Delete old image if exists
                if ($imagePath && File::exists(public_path($imagePath))) {
                    File::delete(public_path($imagePath));
                }

                // Generate unique filename
                $extension = $district['image']->getClientOriginalExtension();
                $filename = 'district_' . time() . '_' . Str::random(10) . '.' . $extension;

                // Move to public folder
                $district['image']->move(public_path('uploads/district_images'), $filename);
                $imagePath = 'uploads/district_images/' . $filename;
            }

            // Handle image removal
            if (!empty($district['remove_image'])) {
                if ($imagePath && File::exists(public_path($imagePath))) {
                    File::delete(public_path($imagePath));
                }
                $imagePath = null;
            }

            $processedData[] = [
                'destination' => $district['destination'] ?? null,
                'image_path' => $imagePath,
                'description' => $district['description'] ?? null,
                'slug' => isset($district['destination']) ? Str::slug($district['destination']) : null,
                'is_featured' => $district['is_featured'] ?? false,
            ];
        }

        // Store all districts in a single row as JSON
        stay_district::create([
            'destination' =>  $request->cities_name,
            'districts_data' => $processedData
        ]);

        return redirect()->route('admin.staydistrictlist')
            ->with('success', 'Districts added successfully!');
    }

    public function edit_form($id)
    {
        $destination_dts = City::where('status', '1')->where('is_deleted', '0')->pluck('city_name', 'id');
        $destination = stay_district::findOrFail($id);
        return view('admin.stay_district.staydistrictedit', compact('destination', 'destination_dts'));
    }

    public function update(Request $request, $id)
    {

        // dd($request->all());
        // First check if districts data exists
        if (!$request->has('districts') || !is_array($request->districts)) {
            return redirect()->back()
                ->with('error', 'Invalid districts data')
                ->withInput();
        }

        $validatedData = [];

        foreach ($request->districts as $index => $districtData) {
            // Skip empty entries
            if (empty($districtData)) continue;

            $validator = Validator::make($districtData, [
                'destination' => 'required|string|max:255',
                'image' => 'nullable',
                'description' => 'nullable|string',
                'is_featured' => 'sometimes|boolean',
                'existing_image' => 'nullable|string',
                'remove_image' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validatedData[$index] = $validator->validated();
        }

        // Check if we have any valid districts
        if (empty($validatedData)) {
            return redirect()->back()
                ->with('error', 'No valid district data provided')
                ->withInput();
        }

        // Process image uploads
        $processedData = [];

        foreach ($validatedData as $district) {
            $imagePath = $district['existing_image'] ?? null;

            // Handle new image upload
            if (!empty($district['image']) && $district['image']->isValid()) {
                // Delete old image if exists
                if ($imagePath && File::exists(public_path($imagePath))) {
                    File::delete(public_path($imagePath));
                }

                // Generate unique filename
                $extension = $district['image']->getClientOriginalExtension();
                $filename = 'district_' . time() . '_' . Str::random(10) . '.' . $extension;

                // Ensure directory exists
                if (!File::exists(public_path('uploads/district_images'))) {
                    File::makeDirectory(public_path('uploads/district_images'), 0755, true);
                }

                // Move to public folder
                $district['image']->move(public_path('uploads/district_images'), $filename);
                $imagePath = 'uploads/district_images/' . $filename;
            }

            // Handle image removal
            if (!empty($district['remove_image'])) {
                if ($imagePath && File::exists(public_path($imagePath))) {
                    File::delete(public_path($imagePath));
                }
                $imagePath = null;
            }

            $processedData[] = [
                'destination' => $district['destination'] ?? null,
                'image_path' => $imagePath,
                'description' => $district['description'] ?? null,
                'slug' => isset($district['destination']) ? Str::slug($district['destination']) : null,
                'is_featured' => $district['is_featured'] ?? false,
            ];
        }

        // Update the destination
        $destination = stay_district::findOrFail($id);
        $destination->update([
            'destination' => $request->cities_name,
            'districts_data' => $processedData,
        ]);

        return redirect()->route('admin.staydistrictlist')
            ->with('success', 'Destination updated successfully!');
    }

      public function delete(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');

        // Find the admin record by ID
        $stay_district = stay_district::find($record_id);
        if ($stay_district) {
            // Update the is_deleted field to 1
            $stay_district->is_deleted = "1";



            $stay_district->save();

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

     public function change_status(Request $request)
    {

        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $stay_district = stay_district::find($record_id);

        if ($stay_district) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $stay_district->status = "0";
            } else {
                $stay_district->status = "1";
            }



            $stay_district->save();

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


}
