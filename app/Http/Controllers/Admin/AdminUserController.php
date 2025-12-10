<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Hash;


class AdminUserController extends Controller
{
    public function list(Request $request)
    {
        $title = 'User List';
        $admins = Admin::where('is_deleted', '0')->get();
        return view('admin.adminuser.userlist', compact('title', 'admins'));
    }

    public function add_form()
    {
        $title = ' Add User';

        return view('admin.adminuser.useradd', compact('title'));
    }

    public function insert(Request $request)
    {
        $request->validate([
            'first_name'   => 'required',
            'last_name'    => 'required',
            'email'        => 'required|email|unique:admin,email',
            'phone'        => 'required|unique:admin,phone',
            'profile_pic'  => 'required|image',
            'password'     => 'required',
        ]);

        // Create upload path
        $sliderPath = public_path('uploads/user_profile');
        if (!file_exists($sliderPath)) {
            mkdir($sliderPath, 0755, true);
        }

        $filePath = null;

        // Handle profile pic
        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $filename .= '.' . $file->getClientOriginalExtension();

            $file->move($sliderPath, $filename);

            $filePath = "uploads/user_profile/" . $filename;
        }

        // Create User
        $user = new Admin;
        $user->first_name   = $request->first_name;
        $user->last_name    = $request->last_name;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->password     = Hash::make($request->password);
        $user->profile_pic  = $filePath;
        $user->status       = $request->status ? '1' : '0';
        $user->created_date = now();
        $user->created_by   = 'admin';
        $user->is_deleted   = '0';
        $user->save();

        return redirect()->route('admin.admin_user_list')
            ->with('success', 'User created successfully.');
    }


    public function edit_form(Request $request, $id)
    {
        $user_details = Admin::find($id);
        $title = 'Edit Admin';
        return view('admin.adminuser.useredit', compact('user_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        $user = Admin::findOrFail($id);

        $request->validate([
            'first_name'  => 'required',
            'last_name'   => 'required',
            'email'       => 'required|email|unique:admin,email,' . $id,
            'phone'       => 'required|unique:admin,phone,' . $id,
            'profile_pic' => 'nullable|image',
            'password'    => 'nullable|min:6',
        ]);

        // Profile pic upload
        if ($request->hasFile('profile_pic')) {
            $sliderPath = public_path('uploads/user_profile');

            if (!file_exists($sliderPath)) {
                mkdir($sliderPath, 0755, true);
            }

            $file = $request->file('profile_pic');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $filename .= '.' . $file->getClientOriginalExtension();

            $file->move($sliderPath, $filename);

            $user->profile_pic = "uploads/user_profile/" . $filename;
        }

        // Update fields
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
        $user->phone      = $request->phone;
        $user->status      = $request->status ? '1' : '0';
        $user->save();

        return redirect()->route('admin.admin_user_list')
            ->with('success', 'User updated successfully.');
    }



    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $slider = Admin::find($record_id);

        if ($slider) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $slider->status = "0";
            } else {
                $slider->status = "1";
            }

            $slider->save();

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
        $slider = Admin::find($record_id);
        if ($slider) {
            $slider->is_deleted = "1";
            $slider->save();

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
