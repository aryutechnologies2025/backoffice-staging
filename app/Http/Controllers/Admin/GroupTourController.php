<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group_tour;


class GroupTourController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Group Tour Package List';
        $group_tour_dts = Group_tour::where('is_deleted', '0')->paginate(10);
        return view('admin.group_tour.group_tourlist', compact('title', 'group_tour_dts'));
    }

    public function add_form()
    {
        $title = 'Group Tour Package Add';

        return view('admin.group_tour.group_touradd', compact('title'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'tour_title' => 'required',
            'tour_code' => 'required',
            'tour_location' => 'required',
            'tour_desc' => 'required',
            'image_1' => 'required'
        ]);

        $group_tourPath = public_path('/uploads/group_tour_pack');
        if (!file_exists($group_tourPath)) {
            mkdir($group_tourPath, 0755, true);
        }

        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $file1->move($group_tourPath, $filename1);
            $filePath1 = 'uploads/group_tour_pack/' . $filename1;
        }

        $group_tour = new Group_tour;
        $group_tour->tour_title = $request->input('tour_title');
        $group_tour->tour_code = $request->input('tour_code');
        $group_tour->tour_location = $request->input('tour_location');
        $group_tour->tour_desc = $request->input('tour_desc');
        $group_tour->group_tour_img = $filePath1;
        $group_tour->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $group_tour->created_date = date('Y-m-d H:i:s');
        $group_tour->created_by = 'admin';
        $group_tour->is_deleted = '0';
        $group_tour->updated_at = null;
        $group_tour->save();

        return redirect()->route('admin.group_tour_list')
            ->with('success', 'Group Tour created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $group_details = Group_tour::find($id);
        $title = 'Group Tour Edit';
        return view('admin.group_tour.group_touredit', compact('group_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'tour_title' => 'required',
            'tour_code' => 'required',
            'tour_location' => 'required',
            'tour_desc' => 'required',
        ]);

        $group_tourPath = public_path('/uploads/group_tour_pack');
        if (!file_exists($group_tourPath)) {
            mkdir($group_tourPath, 0755, true);
        }

        $group_tour = Group_tour::find($id);
        if (!$group_tour) {
            return redirect()->route('admin.group_tour_list')
                ->with('error', 'Group Tour not found.');
        }

        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $file1->move($group_tourPath, $filename1);
            $filePath1 = 'uploads/group_tour_pack/' . $filename1;
            $group_tour->group_tour_img = $filePath1;
        }


        $group_tour->tour_title = $request->input('tour_title');
        $group_tour->tour_code = $request->input('tour_code');
        $group_tour->tour_location = $request->input('tour_location');
        $group_tour->tour_desc = $request->input('tour_desc');
        $group_tour->updated_date = date('Y-m-d H:i:s');
        $group_tour->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $group_tour->updated_by = 'admin';
        $group_tour->save();

        return redirect()->route('admin.group_tour_list')
            ->with('success', 'Group Tour updated successfully');
    }


    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $group_tour = Group_tour::find($record_id);

        if ($group_tour) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $group_tour->status = "0";
            } else {
                $group_tour->status = "1";
            }
            // Update the updated_date field
            $group_tour->updated_date = date('Y-m-d H:i:s');
            $group_tour->status_changed_by = 'admin';
            $group_tour->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Group booking status changed successfully.'
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
        $group_tour = Group_tour::find($record_id);
        if ($group_tour) {
            // Update the is_deleted field to 1
            $group_tour->is_deleted = "1";

            // Set the updated_date field
            $group_tour->updated_date = date('Y-m-d H:i:s');
            $group_tour->deleted_by = 'admin';
            // Save the changes
            $group_tour->save();

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
