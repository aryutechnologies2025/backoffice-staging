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

        $roles = Role::where('is_deleted', '0')
                        ->orderBy('id', 'desc')
                        ->get();

        return view('admin.roles.rolelist', compact('title', 'roles'));
    }


    public function add_form()
    {
        $title = 'Add Role';
        return view('admin.roles.roleadd', compact('title'));
    }


    public function insert(Request $request)
    {
        $request->validate([
            'role_name' => 'required|unique:roles,role_name'
        ], [
            'role_name.unique' => 'This role name is already taken.'
        ]);

        $role = new Role();
        $role->role_name = $request->role_name;
        $role->status = $request->has('status') ? '1' : '0';
        $role->created_date = now();
        $role->created_by = 'admin';
        $role->is_deleted = '0';
        $role->save();

        return redirect()->route('admin.role_list')
            ->with('success', 'Role created successfully.');
    }


    public function edit_form(Request $request, $id)
    {
        $title = 'Edit Role';
        $role_details = Role::findOrFail($id);

        return view('admin.roles.roleedit', compact('role_details', 'title'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'role_name' => 'required|unique:roles,role_name,' . $id
        ], [
            'role_name.unique' => 'This role name is already taken.'
        ]);

        $role = Role::findOrFail($id);
        $role->role_name = $request->role_name;
        $role->status = $request->has('status') ? '1' : '0';
        $role->save();

        return redirect()->route('admin.role_list')
            ->with('success', 'Role updated successfully.');
    }


    public function change_status(Request $request)
    {
        $record_id = $request->record_id;
        $mode = $request->mode;

        $role = Role::find($record_id);

        if ($role) {

            if ($mode == 0) {
                $role->status = "0";
            } else {
                $role->status = "1";
            }

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


    public function delete(Request $request)
    {
        $record_id = $request->record_id;

        $role = Role::find($record_id);

        if ($role) {

            $role->is_deleted = "1";
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