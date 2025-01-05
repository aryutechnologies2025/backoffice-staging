<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodBeverage;

class Food_beverageController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Food&Beverage List';
        $food_beverage = FoodBeverage::where('is_deleted', '0')->paginate(10);
        return view('admin.food_beverage.food_beveragelist', compact('title', 'food_beverage'));
    }

    public function add_form()
    {
        $title = 'Food&Beverage Add';
        return view('admin.food_beverage.food_beverageadd', compact('title'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'food_beverage' => 'required',
            'image_1' => 'required',
        ]);
        $food_beveragePath = public_path('/uploads/food_beverage_pic');
        if (!file_exists($food_beveragePath)) {
            mkdir($food_beveragePath, 0755, true);
        }
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move($food_beveragePath, $filename1);
            $filePath1 = 'uploads/food_beverage_pic/' . $filename1;
        }

        $food_beverage = new FoodBeverage;
        $food_beverage->food_beverage = $request->input('food_beverage');
        $food_beverage->food_beverage_pic = $filePath1;
        $food_beverage->alternate_name = $request->input('alternate_image_name'); // Save alternate name
        $food_beverage->upload_image_name = $request->input('upload_image_name');
        $food_beverage->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $food_beverage->created_date = date('Y-m-d H:i:s');
        $food_beverage->created_by = 'admin';
        $food_beverage->is_deleted = '0';
        $food_beverage->updated_at = null;
        $food_beverage->save();

        return redirect()->route('admin.food_beveragelist')
            ->with('success', 'Food&Beverage created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $food_beverage_details = FoodBeverage::find($id);
        $title = 'Food&Beverage Edit';
        return view('admin.food_beverage.food_beverageedit', compact('food_beverage_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'food_beverage' => 'required',
        ]);

        
        $food_beverage_picPath = public_path('/uploads/food_beverage_pic');
        if (!file_exists($food_beverage_picPath)) {
            mkdir($food_beverage_picPath, 0755, true);
        }

        $food_beverage = FoodBeverage::find($id);
        if (!$food_beverage) {
            return redirect()->route('admin.food_beveragelist')
                ->with('error', 'Food&Beverage not found.');
        }

        $filePath1 = $food_beverage->food_beverage_pic; // Initialize with existing value
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
           
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move($food_beverage_picPath, $filename1);
            $filePath1 = 'uploads/food_beverage_pic/' . $filename1;
        }

        $food_beverage->food_beverage = $request->input('food_beverage');
        $food_beverage->food_beverage_pic = $filePath1;
        $food_beverage->alternate_name = $request->input('alternate_image_name'); // Save alternate name
        $food_beverage->upload_image_name = $request->input('upload_image_name');
  $food_beverage->updated_date = date('Y-m-d H:i:s');
        $food_beverage->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $food_beverage->updated_by = 'admin';
        $food_beverage->save();

        return redirect()->route('admin.food_beveragelist')
            ->with('success', 'Food&Beverage updated successfully');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $food_beverage = FoodBeverage::find($record_id);

        if ($food_beverage) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $food_beverage->status = "0";
            } else {
                $food_beverage->status = "1";
            }
            $role = session('admin_role');
            // Update the updated_date field
            $food_beverage->updated_date = date('Y-m-d H:i:s');
            $food_beverage->status_changed_by = 'admin';
            $food_beverage->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Food&Beverage status changed successfully.'
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
        $food_beverage = FoodBeverage::find($record_id);
        if ($food_beverage) {
            // Update the is_deleted field to 1
            $food_beverage->is_deleted = "1";

            // Set the updated_date field
            $food_beverage->updated_date = date('Y-m-d H:i:s');
            $food_beverage->deleted_by = 'admin';
            // Save the changes
            $food_beverage->save();

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
