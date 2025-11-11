<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;


class CityController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Destination List';
        $city_dts = City::where('is_deleted', '0')->get();
        return view('admin.city.citylist', compact('title', 'city_dts'));
    }

    public function add_form()
    {
        $title = 'Add Destination';

        return view('admin.city.cityadd', compact('title'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'city_name' => 'required',
            'list_order' => 'required',
            'program_image' => 'required',
            'stay_image' => 'required',
        ]);

        $cityPath = public_path('/uploads/cities_pic');
        if (!file_exists($cityPath)) {
            mkdir($cityPath, 0755, true);
        }

        $filePath1 = null;
        $filePath2 = null;

        if ($request->hasFile('program_image')) {
            $file1 = $request->file('program_image');
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('program_upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move($cityPath, $filename1);
            $filePath1 = 'uploads/cities_pic/' . $filename1;
        }

        if ($request->hasFile('stay_image')) {
            $file1 = $request->file('stay_image');
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('stay_upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move($cityPath, $filename1);
            $filePath2 = 'uploads/cities_pic/' . $filename1;
        }

        $City = new City;
        $City->city_name = $request->input('city_name');
        $City->description = $request->input('description');
        $City->list_order = $request->input('list_order');
        $City->cities_pic = $filePath1;
        $City->stay_images = $filePath2;
        $City->upload_image_name = $request->input('program_upload_image_name');
        $City->alternate_name = $request->input('program_alternate_image_name'); // Save alternate name
        $City->stay_upload_image_name = $request->input('stay_upload_image_name');
        $City->stay_alternate_name = $request->input('stay_alternate_image_name'); // Save alternate name
        $City->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $City->created_date = date('Y-m-d H:i:s');
        $City->created_by = 'admin';
        $City->is_deleted = '0';
        $City->updated_at = null;
        $City->save();

        return redirect()->route('admin.citylist')
            ->with('success', 'City created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $city_details = City::find($id);
        $title = 'Edit Destination';
        return view('admin.city.cityedit', compact('city_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'city_name' => 'required',
            'list_order' => 'required',
            // 'program_image' => 'required',
            // 'stay_image' => 'required',
        ]);

        $cityPath = public_path('/uploads/cities_pic');
        if (!file_exists($cityPath)) {
            mkdir($cityPath, 0755, true);
        }

        $City = City::find($id);
        if (!$City) {
            return redirect()->route('admin.citylist')
                ->with('error', 'City not found.');
        }

        $filePath1 = $City->cities_pic; // Initialize with existing value
        $filePath2 = $City->stay_images;

        if ($request->hasFile('program_image')) {
            $file1 = $request->file('program_image');
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('program_upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move($cityPath, $filename1);
            $filePath1 = 'uploads/cities_pic/' . $filename1;
        }

        if ($request->hasFile('stay_image')) {
            $file1 = $request->file('stay_image');
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('stay_upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move($cityPath, $filename1);
            $filePath2 = 'uploads/cities_pic/' . $filename1;
        }

        $City->cities_pic = $filePath1;
        $City->stay_images = $filePath2;
        $City->city_name = $request->input('city_name');
        $City->description = $request->input('description');
        $City->list_order = $request->input('list_order');
        $City->upload_image_name = $request->input('program_upload_image_name');
        $City->alternate_name = $request->input('program_alternate_image_name'); // Save alternate name
        $City->stay_upload_image_name = $request->input('stay_upload_image_name');
        $City->stay_alternate_name = $request->input('stay_alternate_image_name'); // Save alternate name
        $City->updated_date = date('Y-m-d H:i:s');
        $City->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $City->updated_by = 'admin';
        $City->save();

        return redirect()->route('admin.citylist')
            ->with('success', 'City updated successfully');
    }


    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $City = City::find($record_id);

        if ($City) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $City->status = "0";
            } else {
                $City->status = "1";
            }
            // Update the updated_date field
            $City->updated_date = date('Y-m-d H:i:s');
            $City->status_changed_by = 'admin';
            $City->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Destination status changed successfully.'
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
        $City = City::find($record_id);
        if ($City) {
            // Update the is_deleted field to 1
            $City->is_deleted = "1";

            // Set the updated_date field
            $City->updated_date = date('Y-m-d H:i:s');
            $City->deleted_by = 'admin';
            // Save the changes
            $City->save();

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
}
