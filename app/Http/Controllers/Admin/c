<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Themes;
use App\Models\Themes_category;


class ThemesCatController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Themes Category List';
        $themes_cat = Themes_category::with('theme') // Eager load the related theme
            ->where('is_deleted', '0')
            ->paginate(10);

        return view('admin.themes_cat.themes_catlist', compact('title', 'themes_cat'));
    }

    public function add_form()
    {
        $title = 'Theme Category Add';
        $themes = Themes::where('status', "1")->where('is_deleted', "0")->pluck('themes_name', 'id');
        return view('admin.themes_cat.themes_catadd', compact('title', 'themes'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'themes_name' => 'required',
            'theme_cat' => 'required',
        ]);


        $themes_cat = new Themes_category;
        $themes_cat->theme_id = $request->input('themes_name');
        $themes_cat->theme_cat = $request->input('theme_cat');
        $themes_cat->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $themes_cat->created_date = date('Y-m-d H:i:s');
        $themes_cat->created_by = 'admin';
        $themes_cat->is_deleted = '0';
        $themes_cat->updated_at = null;
        $themes_cat->save();

        return redirect()->route('admin.themes_cat_list')
            ->with('success', 'Theme Category created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $themes = Themes::where('status', "1")->where('is_deleted', "0")->pluck('themes_name', 'id');

        $themes_cat_details = Themes_category::find($id);
        $selectedThemeId = $themes_cat_details->theme_id;
        $title = 'Theme Edit';
        return view('admin.themes_cat.themes_catedit', compact('themes_cat_details', 'title', 'themes', 'selectedThemeId'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'themes_name' => 'required',
            'theme_cat' => 'required',
        ]);


        $themes_cat = Themes_category::find($id);
        if (!$themes_cat) {
            return redirect()->route('admin.themes_cat_list')
                ->with('error', 'Themes Category not found.');
        }

        $themes_cat->theme_id = $request->input('themes_name');
        $themes_cat->theme_cat = $request->input('theme_cat');
        $themes_cat->updated_date = date('Y-m-d H:i:s');
        $themes_cat->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $themes_cat->updated_by = 'admin';
        $themes_cat->save();

        return redirect()->route('admin.themes_cat_list')
            ->with('success', 'Themes Category updated successfully');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $themes_cat = Themes_category::find($record_id);

        if ($themes_cat) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $themes_cat->status = "0";
            } else {
                $themes_cat->status = "1";
            }
            $role = session('admin_role');
            // Update the updated_date field
            $themes_cat->updated_date = date('Y-m-d H:i:s');
            $themes_cat->status_changed_by = 'admin';
            $themes_cat->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Themes Category status changed successfully.'
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
        $themes_cat = Themes_category::find($record_id);
        if ($themes_cat) {
            // Update the is_deleted field to 1
            $themes_cat->is_deleted = "1";

            // Set the updated_date field
            $themes_cat->updated_date = date('Y-m-d H:i:s');
            $themes_cat->deleted_by = 'admin';
            // Save the changes
            $themes_cat->save();

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
