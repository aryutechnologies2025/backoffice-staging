<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destination;

class DestinationController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Destination List';
        $destination_dts = Destination::where('is_deleted', '0')->paginate(10);
        return view('admin.destination.destinationlist', compact('title', 'destination_dts'));
    }

    public function add_form()
    {
        $title = 'Destination Add';
        return view('admin.destination.destinationadd', compact('title'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'destination_name' => 'required',
            'place' => 'required',
            'image_1' => 'required',
        ]);

        $destinationPath = public_path('/uploads/destination_pic');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $file1->move($destinationPath, $filename1);
            $filePath1 = 'uploads/destination_pic/' . $filename1;
        }

        $destination = new Destination;
        $destination->destination_name = $request->input('destination_name');
        $destination->place = $request->input('place');
        $destination->destination_pic = $filePath1;
        $destination->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $destination->created_date = date('Y-m-d H:i:s');
        $destination->created_by = 'admin';
        $destination->is_deleted = '0';
        $destination->updated_at = null;
        $destination->save();

        return redirect()->route('admin.destinationlist')
            ->with('success', 'Destination created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $destination_details = Destination::find($id);
        $title = 'Destination Edit';
        return view('admin.destination.destinationedit', compact('destination_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'destination_name' => 'required',
            'place' => 'required',
        ]);

        $destinationPath = public_path('/uploads/destination_pic');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }



        $destination = Destination::find($id);
        if (!$destination) {
            return redirect()->route('admin.destinationlist')
                ->with('error', 'Destination not found.');
        }

        $destination->destination_name = $request->input('destination_name');
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $file1->move($destinationPath, $filename1);
            $filePath1 = 'uploads/destination_pic/' . $filename1;
            $destination->destination_pic = $filePath1;
        }

        $destination->updated_date = date('Y-m-d H:i:s');
        $destination->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $destination->updated_by = 'admin';
        $destination->save();

        return redirect()->route('admin.destinationlist')
            ->with('success', 'Destination updated successfully');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $destination = Destination::find($record_id);

        if ($destination) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $destination->status = "0";
            } else {
                $destination->status = "1";
            }
            $role = session('admin_role');
            // Update the updated_date field
            $destination->updated_date = date('Y-m-d H:i:s');
            $destination->status_changed_by = 'admin';
            $destination->save();

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
        $destination = Destination::find($record_id);
        if ($destination) {
            // Update the is_deleted field to 1
            $destination->is_deleted = "1";

            // Set the updated_date field
            $destination->updated_date = date('Y-m-d H:i:s');
            $destination->deleted_by = 'admin';
            // Save the changes
            $destination->save();

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
