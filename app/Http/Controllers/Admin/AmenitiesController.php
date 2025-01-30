<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Amenities;


class AmenitiesController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Amenities List';
        $amenities = Amenities::where('is_deleted', '0')->paginate(100);
        return view('admin.amenities.amenitieslist', compact('title', 'amenities'));
    }

    public function add_form()
    {
        $title = 'Add Amenities';
        return view('admin.amenities.amenitiesadd', compact('title'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'amenity_name' => 'required',
            'image_1' => 'required',
        ]);

        $amenityPath = public_path('/uploads/amenity_pic');
        if (!file_exists($amenityPath)) {
            mkdir($amenityPath, 0755, true);
        }
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move($amenityPath, $filename1);
            $filePath1 = 'uploads/amenity_pic/' . $filename1;
        }


        $amenities = new Amenities;
        $amenities->amenity_name = $request->input('amenity_name');
        $amenities->amenity_pic = $filePath1;
        $amenities->alternate_name = $request->input('alternate_image_name'); // Save alternate name
        $amenities->upload_image_name = $request->input('upload_image_name');
 $amenities->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $amenities->created_date = date('Y-m-d H:i:s');
        $amenities->created_by = 'admin';
        $amenities->is_deleted = '0';
        $amenities->updated_at = null;
        $amenities->save();

        return redirect()->route('admin.amenitieslist')
            ->with('success', 'Amenity created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $amenities_details = Amenities::find($id);
        $title = 'Edit Amenities';
        return view('admin.amenities.amenitiesedit', compact('amenities_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'amenity_name' => 'required',
        ]);
        $amenityPath = public_path('/uploads/amenity_pic');
        if (!file_exists($amenityPath)) {
            mkdir($amenityPath, 0755, true);
        }

        $amenities = Amenities::find($id);
        if (!$amenities) {
            return redirect()->route('admin.amenitieslist')
                ->with('error', 'Amenities not found.');
        }
     
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
           
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move(  $amenityPath, $filename1);
            $filePath1 = 'uploads/amenity_pic/' . $filename1;
        }
        $amenities->amenity_pic = $filePath1 ?? null;
        $amenities->alternate_name = $request->input('alternate_image_name'); // Save alternate name
        $amenities->upload_image_name = $request->input('upload_image_name');

        $amenities->amenity_name = $request->input('amenity_name');
        $amenities->updated_date = date('Y-m-d H:i:s');
        $amenities->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $amenities->updated_by = 'admin';
        $amenities->save();

        return redirect()->route('admin.amenitieslist')
            ->with('success', 'Amenities updated successfully');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $amenities = Amenities::find($record_id);

        if ($amenities) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $amenities->status = "0";
            } else {
                $amenities->status = "1";
            }
            $role = session('admin_role');
            // Update the updated_date field
            $amenities->updated_date = date('Y-m-d H:i:s');
            $amenities->status_changed_by = 'admin';
            $amenities->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Amenities status changed successfully.'
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
        $amenities = Amenities::find($record_id);
        if ($amenities) {
            // Update the is_deleted field to 1
            $amenities->is_deleted = "1";

            // Set the updated_date field
            $amenities->updated_date = date('Y-m-d H:i:s');
            $amenities->deleted_by = 'admin';
            // Save the changes
            $amenities->save();

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
