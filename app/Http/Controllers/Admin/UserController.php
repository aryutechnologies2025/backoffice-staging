<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function list(Request $request)
    {
        $title = 'User List';
        $user_dts = User::where('is_deleted', '0')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.user.userlist', compact('title', 'user_dts'));
    }

    public function add_form()
    {
        $title = 'User Add';

        return view('admin.user.useradd', compact('title'));
    }

    public function insert(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'required|min:8|confirmed',
            'dob' => 'required',
            'street' => 'required',
            'city' => 'required',
            'state'  => 'required',
            'zip_province_code' => 'required',
            'country' => 'required',
            'preferred_lang' => 'required',

        ]);


        $user = new User;
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->dob = $request->input('dob');
        $user->street = $request->input('street');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->zip_province_code = $request->input('zip_province_code');
        $user->country = $request->input('country');
        $user->preferred_lang = $request->input('preferred_lang');
        $user->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $user->newsletter_sub = $request->has('newsletter_sub') && $request->input('newsletter_sub') === 'on' ? '1' : '0';
        $user->terms_condition = $request->has('terms_condition') && $request->input('terms_condition') === 'on' ? '1' : '0';
        $user->created_date = date('Y-m-d H:i:s');
        $user->created_by = 'admin';
        $user->is_deleted = '0';
        $user->updated_at = null;
        $user->password = Hash::make($request->input('password'));

        $user->save();

        return redirect()->route('admin.user_list')
            ->with('success', 'User created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $user_details = User::find($id);
        $title = 'User Edit';
        return view('admin.user.useredit', compact('user_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Validate input
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:admin,email,' . $id,
            'phone' => 'required',
            'old_password' => 'nullable', // Make old_password nullable
            'new_password' => 'nullable', // New password validation
            'dob' => 'required',
            'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_province_code' => 'required',
            'country' => 'required',
            'preferred_lang' => 'required',
        ]);

        // Check if old password is provided and matches the current password
        if ($request->old_password) {
            if (!Hash::check($request->old_password, $admin->password)) {
                return redirect()->back()->withErrors(['old_password' => 'Old password is incorrect.']);
            }
        }
        if ($request->new_password) {
            if ($request->new_password && $request->new_password != $request->new_password_confirmation) {
                return redirect()->back()->withErrors('error', 'Passwords do not match.');
            }
            $user->password = Hash::make($request->input('new_password'));
        }

        // Update user details
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->dob = $request->input('dob');
        $user->street = $request->input('street');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->updated_by = 'Admin';
        $user->zip_province_code = $request->input('zip_province_code');
        $user->country = $request->input('country');
        $user->preferred_lang = $request->input('preferred_lang');
        $user->status = $request->has('status') ? '1' : '0';
        $user->newsletter_sub = $request->has('newsletter_sub') ? '1' : '0';
        $user->terms_condition = $request->has('terms_condition') ? '1' : '0';



        $user->updated_at = now(); // Update timestamp
        $user->save();

        return redirect()->route('admin.user_list')
            ->with('success', 'User updated successfully.');
    }


    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $user = User::find($record_id);

        if ($user) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $user->status = "0";
            } else {
                $user->status = "1";
            }
            // Update the updated_date field
            $user->updated_date = date('Y-m-d H:i:s');
            $user->status_changed_by = 'admin';
            $user->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'User status changed successfully.'
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
        $user = User::find($record_id);
        if ($user) {
            // Update the is_deleted field to 1
            $user->is_deleted = "1";

            // Set the updated_date field
            $user->updated_date = date('Y-m-d H:i:s');
            $user->deleted_by = 'admin';
            // Save the changes
            $user->save();

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
