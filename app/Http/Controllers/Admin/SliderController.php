<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;


class SliderController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Slider List';
        $slider_dts = Slider::where('is_deleted', '0')->paginate(10);
    //   dd($slider_dts);
        return view('admin.slider.sliderlist', compact('title', 'slider_dts'));
    }

    public function add_form()
    {
        $title = ' ADD SLIDER ';

        return view('admin.slider.slideradd', compact('title'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'image_1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sub_title' => 'required',
            'upload_image_name' => 'required|string',
            'alternate_image_name' => 'required|string', // Validate alternate image name
        ]);
    
        $sliderPath = public_path('/uploads/slider_img');
        if (!file_exists($sliderPath)) {
            mkdir($sliderPath, 0755, true);
        }
    
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move($sliderPath, $filename1);
            $filePath1 = 'uploads/slider_img/' . $filename1;
        }
    
        $slider = new Slider;
        $slider->slider_name = $request->input('slider_name');
        $slider->slider_image = $filePath1 ?? null;
        $slider->alternate_name = $request->input('alternate_image_name'); // Save alternate name
        $slider->subtitle = $request->input('sub_title');
        $slider->list_order = $request->input('list_order');
        $slider->upload_image_name = $request->input('upload_image_name');
        $slider->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $slider->created_date = now();
        $slider->created_by = 'admin';
        $slider->is_deleted = '0';
        $slider->updated_at = null;
        $slider->save();
    
        return redirect()->route('admin.slider_list')
            ->with('success', 'Slider created successfully.');
    }
    
    
    public function edit_form(Request $request, $id)
    {
        $slider_details = Slider::find($id);
        $title = 'Slider Edit';
        return view('admin.slider.slideredit', compact('slider_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'list_order' => 'required',
        ]);

        $sliderPath = public_path('/uploads/slider_img');
        if (!file_exists($sliderPath)) {
            mkdir($sliderPath, 0755, true);
        }

        $slider = Slider::find($id);
        if (!$slider) {
            return redirect()->route('admin.slider_list')
                ->with('error', 'Slider not found.');
        }

        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
           
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move($sliderPath, $filename1);
            $filePath1 = 'uploads/slider_img/' . $filename1;
        }
    


        $slider->slider_name = $request->input('slider_name');
        $slider->slider_image = $filePath1 ?? null;
        $slider->alternate_name = $request->input('alternate_image_name'); // Save alternate name
        $slider->subtitle = $request->input('sub_title');
        $slider->list_order = $request->input('list_order');
        $slider->upload_image_name = $request->input('upload_image_name');
        $slider->updated_date = date('Y-m-d H:i:s');
        $slider->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $slider->updated_by = 'admin';
        $slider->save();

        return redirect()->route('admin.slider_list')
            ->with('success', 'Slider updated successfully');
    }


    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $slider = Slider::find($record_id);

        if ($slider) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $slider->status = "0";
            } else {
                $slider->status = "1";
            }
            // Update the updated_date field
            $slider->updated_date = date('Y-m-d H:i:s');
            $slider->status_changed_by = 'admin';
            $slider->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Slider status changed successfully.'
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
        $slider = Slider::find($record_id);
        if ($slider) {
            // Update the is_deleted field to 1
            $slider->is_deleted = "1";

            // Set the updated_date field
            $slider->updated_date = date('Y-m-d H:i:s');
            $slider->deleted_by = 'admin';
            // Save the changes
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
