<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Themes;


class ThemesController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Themes List';
        $themes = Themes::where('is_deleted', '0')->paginate(10);
        return view('admin.themes.themeslist', compact('title', 'themes'));
    }

    public function add_form()
    {
        $title = 'Add Theme';
        return view('admin.themes.themesadd', compact('title'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'theme_name' => 'required',
            'image_1' => 'required',
            'list_order' => 'required',
        ]);

        $themesPath = public_path('/uploads/themes_pic');
        if (!file_exists($themesPath)) {
            mkdir($themesPath, 0755, true);
        }
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move( $themesPath, $filename1);
            $filePath1 = 'uploads/themes_pic/' . $filename1;
        }

        $themes = new Themes;
        $themes->themes_name = $request->input('theme_name');
        $themes->theme_pic = $filePath1 ?? null;
        $themes->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $themes->created_date = date('Y-m-d H:i:s');
        $themes->list_order = $request->input('list_order');
        $themes->upload_image_name = $request->input('upload_image_name');
        $themes->alternate_name = $request->input('alternate_image_name'); // Save alternate name

        $themes->created_by = 'admin';
        $themes->is_deleted = '0';
        $themes->theme_pic = $filePath1;
        $themes->updated_at = null;
        $themes->save();

        return redirect()->route('admin.themes_list')
            ->with('success', 'Theme created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $themes_details = Themes::find($id);
        $title = 'Edit Theme';
        return view('admin.themes.themesedit', compact('themes_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'theme_name' => 'required',
            'list_order' => 'required',
        ]);

        $themesPath = public_path('/uploads/themes_pic');
        if (!file_exists($themesPath)) {
            mkdir($themesPath, 0755, true);
        }



        $themes = Themes::find($id);
        if (!$themes) {
            return redirect()->route('admin.themes_list')
                ->with('error', 'Themes not found.');
        }
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move( $themesPath, $filename1);
            $filePath1 = 'uploads/themes_pic/' . $filename1;
        }

        $themes->themes_name = $request->input('theme_name');
        $themes->theme_pic= $filePath1 ?? null;
        $themes->updated_date = date('Y-m-d H:i:s');
        $themes->list_order = $request->input('list_order');
        $themes->alternate_name = $request->input('alternate_image_name'); // Save alternate name
        $themes->upload_image_name = $request->input('upload_image_name');
        $themes->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $themes->updated_by = 'admin';
        $themes->save();

        return redirect()->route('admin.themes_list')
            ->with('success', 'Themes updated successfully');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $themes = Themes::find($record_id);

        if ($themes) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $themes->status = "0";
            } else {
                $themes->status = "1";
            }
            $role = session('admin_role');
            // Update the updated_date field
            $themes->updated_date = date('Y-m-d H:i:s');
            $themes->status_changed_by = 'admin';
            $themes->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Themes status changed successfully.'
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
        $themes = Themes::find($record_id);
        if ($themes) {
            // Update the is_deleted field to 1
            $themes->is_deleted = "1";

            // Set the updated_date field
            $themes->updated_date = date('Y-m-d H:i:s');
            $themes->deleted_by = 'admin';
            // Save the changes
            $themes->save();

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
