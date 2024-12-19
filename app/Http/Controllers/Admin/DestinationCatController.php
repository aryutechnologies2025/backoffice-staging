<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destination_category;
use App\Models\City;


class DestinationCatController extends Controller
{
    public function list(Request $request)
    {

        $title = 'Destination Category List';
        $destination_cat = Destination_category::with('destination') // Eager load the related theme
            ->where('is_deleted', '0')
            ->paginate(10);
        return view('admin.destination_cat.destination_catlist', compact('title', 'destination_cat'));
    }

    public function add_form()
    {
        $title = 'Destination Category Add';
        $city = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        return view('admin.destination_cat.destination_catadd', compact('title', 'city'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'destination_name' => 'required',
            'destination_cat' => 'required',
        ]);


        $destinations_cat = new Destination_category;
        $destinations_cat->destination_id = $request->input('destination_name');
        $destinations_cat->destination_cat = $request->input('destination_cat');
        $destinations_cat->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $destinations_cat->created_date = date('Y-m-d H:i:s');
        $destinations_cat->created_by = 'admin';
        $destinations_cat->is_deleted = '0';
        $destinations_cat->updated_at = null;
        $destinations_cat->save();

        return redirect()->route('admin.destination_cat_list')
            ->with('success', 'Destination Category created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $destination_cat_details = Destination_category::find($id);
        $city = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        $selecteddestinationId = $destination_cat_details->destination_id;
        $title = 'Destination Category Edit';
        return view('admin.destination_cat.destination_catedit', compact('destination_cat_details', 'title', 'city', 'selecteddestinationId'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'destination_name' => 'required',
            'destination_cat' => 'required',
        ]);


        $destination_cat = Destination_category::find($id);
        if (!$destination_cat) {
            return redirect()->route('admin.destination_cat_list')
                ->with('error', 'Destination Category not found.');
        }


        $destination_cat->destination_id = $request->input('destination_name');
        $destination_cat->destination_cat = $request->input('destination_cat');
        $destination_cat->updated_date = date('Y-m-d H:i:s');
        $destination_cat->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $destination_cat->updated_by = 'admin';
        $destination_cat->save();

        return redirect()->route('admin.destination_cat_list')
            ->with('success', 'Destination Category updated successfully');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $destination_cat = Destination_category::find($record_id);

        if ($destination_cat) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $destination_cat->status = "0";
            } else {
                $destination_cat->status = "1";
            }
            $role = session('admin_role');
            // Update the updated_date field
            $destination_cat->updated_date = date('Y-m-d H:i:s');
            $destination_cat->status_changed_by = 'admin';
            $destination_cat->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Destination Category status changed successfully.'
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
        $destination_cat = Destination_category::find($record_id);
        if ($destination_cat) {
            // Update the is_deleted field to 1
            $destination_cat->is_deleted = "1";

            // Set the updated_date field
            $destination_cat->updated_date = date('Y-m-d H:i:s');
            $destination_cat->deleted_by = 'admin';
            // Save the changes
            $destination_cat->save();

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
