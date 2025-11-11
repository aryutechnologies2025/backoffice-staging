<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\stay_desitination;
use Illuminate\Http\Request;
use App\Models\City;

class StayDestinationController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Stay Destination List';
        $city_dts = stay_desitination::where('is_deleted', '0')->get();
        return view('admin.stay_destination.staydestinationlist', compact('title', 'city_dts'));
    }

    public function add_form()
    {
        $title = 'Add Stay Destination';

        return view('admin.stay_destination.staydestinationadd', compact('title'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'destination_name' => 'required',

            'image_1' => 'required',
        ]);

        $cityPath = public_path('/uploads/cities_pic');
        if (!file_exists($cityPath)) {
            mkdir($cityPath, 0755, true);
        }

        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move($cityPath, $filename1);
            $filePath1 = 'uploads/cities_pic/' . $filename1;
        }

        $City = new stay_desitination();
        $City->city_name = $request->input('destination_name');
        // $City->order = $request->input('list_order');
        $City->city_image = $filePath1;
        $City->upload_image_name = $request->input('upload_image_name');
        $City->alternate_name = $request->input('alternate_image_name'); // Save alternate name
        $City->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $City->is_deleted = '0';
        $City->save();

        return redirect()->route('admin.staydestinationlist')
            ->with('success', 'City created successfully.');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $City = stay_desitination::find($record_id);

        if ($City) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $City->status = "0";
            } else {
                $City->status = "1";
            }

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
        $City = stay_desitination::find($record_id);
        if ($City) {
            // Update the is_deleted field to 1
            $City->is_deleted = "1";


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

    public function edit_form(Request $request, $id)
    {
        $destination_details = stay_desitination::find($id);
        $title = 'Edit Stay Destination';
        return view('admin.stay_destination.staydestinationedit', compact('destination_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        // $validatedData = $request->validate([
        //     'city_name' => 'required',
        //     'list_order' => 'required',
        // ]);

        $cityPath = public_path('/uploads/cities_pic');
        if (!file_exists($cityPath)) {
            mkdir($cityPath, 0755, true);
        }

        $City = stay_desitination::find($id);
        if (!$City) {
            return redirect()->route('admin.staydestinationlist')
                ->with('error', 'City not found.');
        }

        $filePath1 = $City->city_image; // Initialize with existing value

        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            // $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('city_image'));
            $filename1 = time() . '.' . $file1->getClientOriginalExtension();
            $file1->move($cityPath, $filename1);
            $filePath1 = 'uploads/cities_pic/' . $filename1;
            $City->city_image = $filePath1;
        }

        $City->city_image = $filePath1;
        $City->city_name = $request->input('city_name');
        // $City->list_order = $request->input('list_order');
        $City->alternate_name = $request->input('alternate_name');
        $City->upload_image_name = $request->input('upload_image_name');
        $City->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';

        $City->save();

        return redirect()->route('admin.staydestinationlist')
            ->with('success', 'City updated successfully');
    }

    public function get_stay_destination(Request $request)
    {
        $city_dts = City::where('is_deleted', '0')
        ->where('status', '1')
        ->select('id','city_name','stay_images','stay_alternate_name','stay_upload_image_name')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Stays city successfully retrieved.',
            'data' => $city_dts,
        ]);
    }
}
