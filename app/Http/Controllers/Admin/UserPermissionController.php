<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\ModulePermission;

class UserPermissionController extends Controller
{
    public function list()
    {
        $title = 'Permission List';
        $users = Permission::with(['role', 'modules'])
            ->orderBy('id', 'DESC')
            ->get();
        return view('admin.permission.list', compact('title', 'users'));
    }

    public function add()
    {
        $roles = Role::where('status', '1')->where('is_deleted', '0')->select('role_name', 'id')->get();
        $modules = config('app.modules');
        return view('admin.permission.add', compact('roles', 'modules'));
    }

    

    public function insert(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'role_name' => 'required|exists:roles,id',
                'moduleList' => 'required|array|min:1'
            ]);

            // Create main permission row
            $permission = new Permission();
            $permission->role_id = $request->role_name;
            $permission->created_by = auth()->id();
            $permission->created_date = now();
            $permission->status       = '1';
            $permission->is_deleted   = '0';
            $permission->save();

            // Save module permissions
            foreach ($request->moduleList as $moduleData) {
                $modulepermission = new ModulePermission();
                $modulepermission->permission_id = $permission->id;
                $modulepermission->module = $moduleData['module'];
                $modulepermission->is_create = $moduleData['permission']['create'] ?? 0;
                $modulepermission->is_edit = $moduleData['permission']['edit'] ?? 0;
                $modulepermission->is_delete = $moduleData['permission']['delete'] ?? 0;
                $modulepermission->is_list = $moduleData['permission']['list'] ?? 0;
                $modulepermission->is_view = $moduleData['permission']['view'] ?? 0;
                $modulepermission->save();
            }

            // If request expects JSON (AJAX/fetch), return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Permissions assigned successfully.',
                    // optional: send redirect_url for frontend to handle
                    'redirect_url' => route('admin.user_permission_list')
                ], 200);
            }

            // Normal web request: redirect
            return redirect()->route('admin.user_permission_list')
                ->with('success', 'Permissions assigned successfully.');
        } catch (\Exception $e) {

            \Log::error('Permission Insert Error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to save permissions: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Something went wrong! ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function edit($id, Request $request)
    {
        // All roles
        $roles = Role::where('status', '1')
            ->where('is_deleted', '0')
            ->select('role_name', 'id')
            ->get();

        // All modules from config
        $modules = config('app.modules');

        // Fetch Permission + Module Permissions
        $permission = Permission::with('modules')
            ->where('id', $id)
            ->first();


        if (!$permission) {
            return redirect()->back()->with('error', 'Permission record not found');
        }

        return view('admin.permission.edit', [
            'roles' => $roles,
            'modules' => $modules,
            'users' => $permission
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'role_name'   => 'required',
                'moduleList'  => 'required|array',
            ]);

            // Get Permission record
            $permission = Permission::where('id', $id)->first();

            if (!$permission) {
                return response()->json([
                    'message' => 'Permission record not found'
                ], 404);
            }

            // Update role
            $permission->role_id = $request->role_name;
            $permission->save();

            // Remove old module permissions
            ModulePermission::where('permission_id', $id)->delete();

            // Insert new modules
            foreach ($request->moduleList as $module) {
                ModulePermission::create([
                    'permission_id' => $id,
                    'module'        => $module['module'],
                    'is_view'       => $module['permission']['create'],
                    'is_edit'       => $module['permission']['edit'],
                    'is_delete'     => $module['permission']['delete']
                ]);
            }


            //  'permission_id' => $id,
            //         'module'        => $module['module'],
            //         'is_view'       => $module['permission']['view'] ?? 0,
            //         'is_create'     => $module['permission']['create'] ?? 0,
            //         'is_edit'       => $module['permission']['edit'] ?? 0,
            //         'is_delete'     => $module['permission']['delete'] ?? 0,
            //         'is_list'       => $module['permission']['list'] ?? 0

            return response()->json([
                'message' => 'Permissions updated successfully',
                'redirect_url' => route('admin.user_permission_list') // optional
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $slider = Permission::find($record_id);

        if ($slider) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $slider->status = "0";
            } else {
                $slider->status = "1";
            }
            $slider->status_changed_by = auth()->id();
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
        $slider = Permission::find($record_id);
        if ($slider) {
            $slider->is_deleted = "1";
            $slider->deleted_by = auth()->id();
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
