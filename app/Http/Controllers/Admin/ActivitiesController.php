<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activities;

class ActivitiesController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Activities List';
        $activities = Activities::where('is_deleted', '0')->paginate(10);
        return view('admin.activities.activitieslist', compact('title', 'activities'));
    }

    public function add_form()
    {
        $title = 'Activities Add';
        return view('admin.activities.activitiesadd', compact('title'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'activities' => 'required',
            'image_1' => 'required',
        ]);
        $activitiesPath = public_path('/uploads/activities_pic');
        if (!file_exists($activitiesPath)) {
            mkdir($activitiesPath, 0755, true);
        }

        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $file1->move($activitiesPath, $filename1);
            $filePath1 = 'uploads/activities_pic/' . $filename1;
        }

        $activities = new Activities;
        $activities->activities = $request->input('activities');
        $activities->activities_pic = $filePath1;
        $activities->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $activities->created_date = date('Y-m-d H:i:s');
        $activities->created_by = 'admin';
        $activities->is_deleted = '0';
        $activities->updated_at = null;
        $activities->save();

        return redirect()->route('admin.activitieslist')
            ->with('success', 'Activities created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $activities_details = Activities::find($id);
        $title = 'Activities Edit';
        return view('admin.activities.activitiesedit', compact('activities_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'activities' => 'required',
        ]);

        $activitiesPath = public_path('/uploads/activities_pic');
        if (!file_exists($activitiesPath)) {
            mkdir($activitiesPath, 0755, true);
        }

        $activities = Activities::find($id);
        if (!$activities) {
            return redirect()->route('admin.activitieslist')
                ->with('error', 'Activities not found.');
        }
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $file1->move($activitiesPath, $filename1);
            $filePath1 = 'uploads/activities_pic/' . $filename1;
            $activities->activities_pic = $filePath1;
        }

        $activities->activities = $request->input('activities');
        $activities->updated_date = date('Y-m-d H:i:s');
        $activities->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $activities->updated_by = 'admin';
        $activities->save();

        return redirect()->route('admin.activitieslist')
            ->with('success', 'Activities updated successfully');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $activities = Activities::find($record_id);

        if ($activities) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $activities->status = "0";
            } else {
                $activities->status = "1";
            }
            $role = session('admin_role');
            // Update the updated_date field
            $activities->updated_date = date('Y-m-d H:i:s');
            $activities->status_changed_by = 'admin';
            $activities->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Activities status changed successfully.'
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
        $activities = Activities::find($record_id);
        if ($activities) {
            // Update the is_deleted field to 1
            $activities->is_deleted = "1";

            // Set the updated_date field
            $activities->updated_date = date('Y-m-d H:i:s');
            $activities->deleted_by = 'admin';
            // Save the changes
            $activities->save();

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
