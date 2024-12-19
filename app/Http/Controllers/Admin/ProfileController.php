<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Geo_feature;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Admin Profile';
        $admin_dts = Admin::where('is_deleted', '0')->paginate(10);
        return view('admin.profile.profilelist', compact('admin_dts', 'title'));
    }

    public function add_form()
    {
        $title = 'Add Profile';

        return view('admin.profile.profileadd', compact('title'));
    }

    public function insert(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email|unique:admin,email',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);


        // Check if new password and confirm password match
        if ($request->new_password && $request->new_password != $request->confirm_password) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        $profilePath = public_path('/uploads/admin_profile_pic');
        if (!file_exists($profilePath)) {
            mkdir($profilePath, 0755, true);
        }
        if ($request->hasFile('profile_pic')) {
            $file1 = $request->file('profile_pic');
            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $file1->move($profilePath, $filename1);
            $filePath1 = 'uploads/admin_profile_pic/' . $filename1;
        }


        // Retrieve or create the admin record (assuming you are using authentication to get the admin ID)
        // $admin = Admin::findOrFail($request->user()->id); // Adjust based on how you get the admin user
        $admin = new Admin;
        // Update admin details
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->phone = $request->phone_number;
        $admin->email = $request->email;
        $admin->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $admin->created_date = date('Y-m-d H:i:s');
        $admin->created_by = 'admin';
        $admin->is_deleted = '0';
        $admin->updated_at = null;
        if ($filePath1) {
            $admin->profile_pic = $filePath1; // Assuming you have a `profile_pic` field in your Admin model
        }
        if ($request->new_password) {
            $admin->password = Hash::make($request->new_password);
        }

        // Save the updated admin record
        $admin->save();

        // return redirect()->back()->with('success', 'Profile updated successfully.');
        return redirect()->route('admin.profile_list')
            ->with('success', 'Profile created successfully.');
    }


    public function edit_form(Request $request, $id)
    {
        $admin_dts = Admin::find($id);
        $title = 'Admin Edit';
        return view('admin.profile.profileedit', compact('admin_dts', 'title'));
    }


    public function update(Request $request, $id)
    {

        // Retrieve the existing admin record
        $admin = Admin::findOrFail($id);

        // Validate the request data
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email|unique:admin,email,' . $id,
            'old_password' => 'nullable', // Optional for when changing password
            'new_password' => 'nullable', // Confirmed ensures new_password matches confirm_password
            'profile_pic' => 'nullable|image|max:4096', // Optional: Adjust validation as needed
        ]);

        // Check if old password is provided and matches the database password
        if ($request->old_password) {
            if (!Hash::check($request->old_password, $admin->password)) {
                return redirect()->back()->withErrors(['old_password' => 'Old password is incorrect.']);
            }
        }
        if ($request->new_password && $request->new_password != $request->confirm_password) {
            return redirect()->back()->withErrors('error', 'Passwords do not match.');
        }


        // Handle profile picture upload
        if ($request->hasFile('profile_pic')) {
            $profilePath = public_path('/uploads/admin_profile_pic');
            if (!file_exists($profilePath)) {
                mkdir($profilePath, 0755, true);
            }

            $file = $request->file('profile_pic');
            $filename = time() . '_1.' . $file->getClientOriginalExtension();
            $file->move($profilePath, $filename);
            $filePath = 'uploads/admin_profile_pic/' . $filename;

            // Delete the old profile picture if it exists
            if ($admin->profile_pic && file_exists(public_path($admin->profile_pic))) {
                unlink(public_path($admin->profile_pic));
            }

            // Update the profile picture path
            $admin->profile_pic = $filePath;
        }

        // Update admin details
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->phone = $request->phone_number;
        $admin->email = $request->email;
        $admin->status = $request->has('status') ? '1' : '0';

        // Update password if a new one is provided
        if ($request->filled('new_password')) {
            $admin->password = Hash::make($request->new_password);
        }

        // Save the updated admin record
        $admin->save();

        return redirect()->route('admin.profile_list')
            ->with('success', 'Profile updated successfully.');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $admin = Admin::find($record_id);

        if ($admin) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $admin->status = "0";
            } else {
                $admin->status = "1";
            }
            // Update the updated_date field
            $admin->updated_date = date('Y-m-d H:i:s');
            $admin->status_changed_by = 'admin';
            $admin->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Admin status changed successfully.'
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
        $admin = Admin::find($record_id);
        if ($admin) {
            // Update the is_deleted field to 1
            $admin->is_deleted = "1";

            // Set the updated_date field
            $admin->updated_date = date('Y-m-d H:i:s');
            $admin->deleted_by = 'admin';
            // Save the changes
            $admin->save();

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
