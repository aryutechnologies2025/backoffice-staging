<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Geo_feature;


class Geo_featureController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Geo Features List';
        $geo_dts = Geo_feature::where('is_deleted', '0')->paginate(10);
        return view('admin.geo_feature.geo_featurelist', compact('title', 'geo_dts'));
    }

    public function add_form()
    {
        $title = 'Geo Features Add';

        return view('admin.geo_feature.geo_featureadd', compact('title'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'geo_feature' => 'required',
            'image_1' => 'required',
        ]);
        $geoPath = public_path('/uploads/geo_feature_pic');
        if (!file_exists($geoPath)) {
            mkdir($geoPath, 0755, true);
        }
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $file1->move($geoPath, $filename1);
            $filePath1 = 'uploads/geo_feature_pic/' . $filename1;
        }

        $geo_feature = new Geo_feature;
        $geo_feature->geo_feature = $request->input('geo_feature');
        $geo_feature->geo_feature_pic = $filePath1;
        $geo_feature->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $geo_feature->created_date = date('Y-m-d H:i:s');
        $geo_feature->created_by = 'admin';
        $geo_feature->is_deleted = '0';
        $geo_feature->updated_at = null;

        $geo_feature->save();

        return redirect()->route('admin.geo_feature_list')
            ->with('success', 'Geo created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $geo_feature = Geo_feature::find($id);
        $title = 'Geo Features Edit';
        return view('admin.geo_feature.geo_featureedit', compact('geo_feature', 'title'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'geo_feature' => 'required',
        ]);

        $geoPath = public_path('/uploads/geo_feature_pic');
        if (!file_exists($geoPath)) {
            mkdir($geoPath, 0755, true);
        }


        $geo_feature = Geo_feature::find($id);
        if (!$geo_feature) {
            return redirect()->route('admin.geo_feature_list')
                ->with('error', 'Geo not found.');
        }
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $file1->move($geoPath, $filename1);
            $filePath1 = 'uploads/geo_feature_pic/' . $filename1;
            $geo_feature->geo_feature_pic = $filePath1;
        }
        $geo_feature->geo_feature = $request->input('geo_feature');
        $geo_feature->updated_date = date('Y-m-d H:i:s');
        $geo_feature->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $geo_feature->updated_by = 'admin';
        $geo_feature->save();

        return redirect()->route('admin.geo_feature_list')
            ->with('success', 'Geo updated successfully');
    }


    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $geo_feature = Geo_feature::find($record_id);

        if ($geo_feature) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $geo_feature->status = "0";
            } else {
                $geo_feature->status = "1";
            }
            // Update the updated_date field
            $geo_feature->updated_date = date('Y-m-d H:i:s');
            $geo_feature->status_changed_by = 'admin';
            $geo_feature->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Geo status changed successfully.'
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
        $geo_feature = Geo_feature::find($record_id);
        if ($geo_feature) {
            // Update the is_deleted field to 1
            $geo_feature->is_deleted = "1";

            // Set the updated_date field
            $geo_feature->updated_date = date('Y-m-d H:i:s');
            $geo_feature->deleted_by = 'admin';
            // Save the changes
            $geo_feature->save();

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
