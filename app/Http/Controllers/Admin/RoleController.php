<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Role List';
        $roles = Role::where('is_deleted', '0')->get();
        return view('admin.roles.rolelist', compact('title', 'roles'));
    }

    public function add_form()
    {
        $title = ' Add Role';

        return view('admin.roles.roleadd', compact('title'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'role_name' => 'required|unique:roles,role_name'
        ]);

        $slider = new Role;
        $slider->role_name = $request->input('role_name');
        $slider->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $slider->created_date = now();
        $slider->created_by = 'admin';
        $slider->is_deleted = '0';
        $slider->save();

        return redirect()->route('admin.role_list')
            ->with('success', 'Role created successfully.');
    }


    public function edit_form(Request $request, $id)
    {
        $role_details = Role::find($id);
        $title = 'Edit Role';
        return view('admin.roles.roleedit', compact('role_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        // Validate (ignore unique check on the current record)
        $validatedData = $request->validate([
            'role_name' => 'required|unique:roles,role_name,' . $id,
        ]);

        // Find Role
        $role = Role::findOrFail($id);
        $role->role_name = $request->input('role_name');
        $role->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $role->save();

        return redirect()->route('admin.role_list')
            ->with('success', 'Role updated successfully.');
    }



    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $slider = Role::find($record_id);

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
                'response' => 'Role status changed successfully.'
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
        $slider = Role::find($record_id);
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
