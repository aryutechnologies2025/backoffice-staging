<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{

    // Role List
    public function list(Request $request)
    {
        $title = 'Role List';

        $roles = Role::where('is_deleted', '0')
                    ->orderBy('created_at', 'desc') // latest role first
                    ->get();

        return view('admin.roles.rolelist', compact('title', 'roles'));
    }


    // Add Role Form
  public function add_form()
{
    $title = 'Add User';
    $roles = Role::where('is_deleted','0')->get();

    return view('admin.roles.roleadd', compact('title','roles'));
}


    // Insert Role
    public function insert(Request $request)
    {

        $request->validate([
            'role_name' => 'required|unique:roles,role_name'
        ],[
            'role_name.required' => 'Role name is required.',
            'role_name.unique' => 'This role name already exists.'
        ]);

        $role = new Role();

        $role->role_name = $request->role_name;
        $role->status = $request->has('status') ? '1' : '0';
        $role->created_by = auth()->user()->email;
        $role->is_deleted = '0';

        // Timestamp
        $role->created_at = now();
        $role->updated_at = now();

        $role->save();

        return redirect()->route('admin.role_list')
            ->with('success', 'Role created successfully.');
    }


    // Edit Role Form
    public function edit_form(Request $request, $id)
    {

        $title = 'Edit Role';

        $role_details = Role::findOrFail($id);

        return view('admin.roles.roleedit', compact('role_details', 'title'));

    }


    // Update Role
    public function update(Request $request, $id)
    {

        $request->validate([
            'role_name' => 'required|unique:roles,role_name,' . $id
        ],[
            'role_name.required' => 'Role name is required.',
            'role_name.unique' => 'This role name already exists.'
        ]);

        $role = Role::findOrFail($id);

        $role->role_name = $request->role_name;
        $role->status = $request->has('status') ? '1' : '0';

        // update timestamp
        $role->updated_at = now();

        $role->save();

        return redirect()->route('admin.role_list')
            ->with('success', 'Role updated successfully.');
    }


    // Change Status
    public function change_status(Request $request)
    {

        $record_id = $request->record_id;
        $mode = $request->mode;

        $role = Role::find($record_id);

        if ($role) {

            $role->status = $mode == 0 ? "0" : "1";
            $role->updated_at = now();

            $role->save();

            return response()->json([
                'status' => '1',
                'response' => 'Role status changed successfully.'
            ]);
        }

        return response()->json([
            'status' => '0',
            'response' => 'Record not found.'
        ]);
    }


    // Delete Role
    public function delete(Request $request)
    {

        $record_id = $request->record_id;

        $role = Role::find($record_id);

        if ($role) {

            $role->is_deleted = "1";
            $role->updated_at = now();

            $role->save();

            return response()->json([
                'status' => '1',
                'response' => 'Record marked as deleted successfully.'
            ]);
        }

        return response()->json([
            'status' => '0',
            'response' => 'Record not found.'
        ]);
    }

}