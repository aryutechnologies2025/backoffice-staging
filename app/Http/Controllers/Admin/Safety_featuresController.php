<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Safetyfeatures;

class Safety_featuresController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Safety Features List';
        $safety_features = Safetyfeatures::where('is_deleted', '0')->paginate(10);
        return view('admin.safety_features.safety_featureslist', compact('title', 'safety_features'));
    }

    public function add_form()
    {
        $title = 'Add SafetyFeatures';
        return view('admin.safety_features.safety_featuresadd', compact('title'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'safety_features' => 'required',
            'image_1' => 'required',
        ]);

        $safety_featuresPath = public_path('/uploads/safety_features_pic');
        if (!file_exists($safety_featuresPath)) {
            mkdir($safety_featuresPath, 0755, true);
        }
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move($safety_featuresPath, $filename1);
            $filePath1 = 'uploads/safety_features_pic/' . $filename1;
        }

        $safety_feature = new Safetyfeatures;
        $safety_feature->safety_features = $request->input('safety_features');
        $safety_feature->safety_features_pic = $filePath1;
        $safety_feature->alternate_name = $request->input('alternate_image_name'); // Save alternate name
        $safety_feature->upload_image_name = $request->input('upload_image_name');

        $safety_feature->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $safety_feature->created_date = date('Y-m-d H:i:s');
        $safety_feature->created_by = 'admin';
        $safety_feature->is_deleted = '0';
        $safety_feature->updated_at = null;
        $safety_feature->save();

        return redirect()->route('admin.safety_features_list')
            ->with('success', 'Safety Features created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $safety_feature_details = Safetyfeatures::find($id);
        $title = 'Edit SafetyFeatures';
        return view('admin.safety_features.safety_featuresedit', compact('safety_feature_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'safety_features' => 'required',
        ]);
        $safety_featuresPath = public_path('/uploads/safety_features_pic');
        if (!file_exists($safety_featuresPath)) {
            mkdir($safety_featuresPath, 0755, true);
        }

        $safety_feature = Safetyfeatures::find($id);
        if (!$safety_feature) {
            return redirect()->route('admin.safety_features_list')
                ->with('error', 'Safety Features not found.');
        }
        $filePath1 = $safety_feature->safety_features_pic; // Initialize with existing value
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
           
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move($safety_featuresPath , $filename1);
            $filePath1 = 'uploads/safety_features_pic/' . $filename1;
        }
    
        $safety_feature->safety_features = $request->input('safety_features');
        $safety_feature->safety_features_pic = $filePath1;
        $safety_feature->alternate_name = $request->input('alternate_image_name'); // Save alternate name
        $safety_feature->upload_image_name = $request->input('upload_image_name');

        $safety_feature->updated_date = date('Y-m-d H:i:s');
        $safety_feature->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $safety_feature->updated_by = 'admin';
        $safety_feature->save();

        return redirect()->route('admin.safety_features_list')
            ->with('success', 'Safety Features updated successfully');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $safety_feature = Safetyfeatures::find($record_id);

        if ($safety_feature) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $safety_feature->status = "0";
            } else {
                $safety_feature->status = "1";
            }
            $role = session('admin_role');
            // Update the updated_date field
            $safety_feature->updated_date = date('Y-m-d H:i:s');
            $safety_feature->status_changed_by = 'admin';
            $safety_feature->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Safety Features status changed successfully.'
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
        $safety_feature = Safetyfeatures::find($record_id);
        if ($safety_feature) {
            // Update the is_deleted field to 1
            $safety_feature->is_deleted = "1";

            // Set the updated_date field
            $safety_feature->updated_date = date('Y-m-d H:i:s');
            $safety_feature->deleted_by = 'admin';
            // Save the changes
            $safety_feature->save();

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
