<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramEvents;
use Illuminate\Http\Request;

class ProgramEventsController extends Controller
{
    public function list()
    {
        $title = 'Events List';
        $programdetails = ProgramEvents::where('is_deleted', '0')->orderBy('id', 'desc')->get();
        return view('admin.programevents.programEventList', compact('title', 'programdetails'));
    }

    public function add()
    {
        $title = 'Add Event';
        return view('admin.programevents.programEventAdd', compact('title'));
    }

    public function insert(Request $request)
    {
        // dd($request->all());
        // Handle dynamic image uploads
        $imagePaths = [];
        $fileInputs = $request->file();

        foreach ($fileInputs as $key => $files) {
            if (strpos($key, 'img_') === 0) {
                if (is_array($files)) {
                    foreach ($files as $file) {
                        if ($file->isValid()) {
                            $fileName = time() . '_' . $file->getClientOriginalName();
                            $destinationPath = public_path('/uploads/events_program_images');
                            if (!file_exists($destinationPath)) {
                                mkdir($destinationPath, 0755, true);
                            }
                            $file->move($destinationPath, $fileName);
                            $imagePaths[] = '/uploads/events_program_images/' . $fileName;
                        }
                    }
                } else {
                    if ($files->isValid()) {
                        $fileName = time() . '_' . $files->getClientOriginalName();
                        $destinationPath = public_path('/uploads/events_program_images');
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        $files->move($destinationPath, $fileName);
                        $imagePaths[] = '/uploads/events_program_images/' . $fileName;
                    }
                }
            }
        }

        // Additional file handling for cover image
        $filePath1 = null;
        if ($request->hasFile('cover_img')) {
            $file1 = $request->file('cover_img');
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name', 'default_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move(public_path('/uploads/events_program_images'), $filename1);
            $filePath1 = '/uploads/events_program_images/' . $filename1;
        }

        $programevents = new ProgramEvents();
        $programevents->events_package_images = json_encode($imagePaths);
        $programevents->cover_img = $filePath1;
        $programevents->upload_image_name = $request->input('upload_image_name');
        $programevents->alternate_image_name = $request->input('alternate_image_name');
        $programevents->title = $request->input('title');
        $programevents->event_type = $request->input('event_type');
        $programevents->timezone = $request->input('timezone');
        $programevents->start_datetime = $request->input('start_datetime');
        $programevents->end_datetime = $request->input('end_datetime');
        $programevents->location_name = $request->input('location_name');
        $programevents->location_address = $request->input('location_address');
        $programevents->latitude = $request->input('latitude');
        $programevents->longitude = $request->input('longitude');
        $programevents->location_type = $request->input('location_type');
        $programevents->event_description = $request->input('event_description');
        $programevents->save();

        if ($programevents) {
            return redirect()->route('admin.programeventslist')
                ->with('success', 'Record inserted successfully');
        } else {
            return redirect()->route('admin.programeventslist')
                ->with('error', 'Error inserting record');
        }
    }

    public function edit(Request $request)
    {
        $title = 'Events Edit';
        $programdetails = ProgramEvents::where('is_deleted', '0')->orderBy('id', 'desc')->first();
        return view('admin.programevents.programEventEdit', compact('title', 'programdetails'));
    }

    public function update(Request $request, $id)
    {

        // dd($request->input('title'));
        $program_events = ProgramEvents::find($id);
        if (!$program_events) {
            return redirect()->route('admin.programeventslist')
                ->with('error', 'Record not found');
        }

        // Get current images for deletion tracking
        $currentImages = json_decode($program_events->events_package_images, true);
        if (!is_array($currentImages)) {
            $currentImages = [];
        }

        // Handle dynamic image uploads
        $imagePaths = $currentImages; // Start with existing images
        $fileInputs = $request->file();

        // Track deleted images
        $deletedImages = $request->input('deleted_images', []);
        $deletedImages = json_decode($deletedImages, true); // Decode the list of deleted images
        foreach ($deletedImages as $deletedImage) {
            if (in_array($deletedImage, $currentImages)) {
                // Delete the image from the filesystem
                $oldImagePath = public_path($deletedImage);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                // Remove the image from the imagePaths array
                $imagePaths = array_filter($imagePaths, fn($path) => $path !== $deletedImage);
            }
        }

        // Handle new image uploads
        foreach ($fileInputs as $key => $files) {
            if (strpos($key, 'img_') === 0) {
                if (is_array($files)) {
                    foreach ($files as $file) {
                        if ($file->isValid()) {
                            $fileName = time() . '_' . $file->getClientOriginalName();
                            $destinationPath = public_path('/uploads/events_program_images');
                            if (!file_exists($destinationPath)) {
                                mkdir($destinationPath, 0755, true);
                            }
                            $file->move($destinationPath, $fileName);
                            $imagePaths[] = '/uploads/events_program_images/' . $fileName;
                        }
                    }
                } else {
                    if ($files->isValid()) {
                        $fileName = time() . '_' . $files->getClientOriginalName();
                        $destinationPath = public_path('/uploads/events_program_images');
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        $files->move($destinationPath, $fileName);
                        $imagePaths[] = '/uploads/events_program_images/' . $fileName;
                    }
                }
            }
        }

        if ($request->hasFile('cover_img')) {
            // Get the uploaded file
            $coverImage = $request->file('cover_img');

            // Sanitize the file name
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name', 'default_image_name'));
            $customFileName = rand(1000, 9999) . '_' . time();
            $coverImageName = $customFileName . '_cover.' . $coverImage->getClientOriginalExtension();
            $coverImagePath = 'uploads/events_program_images/';
            $coverImage->move(public_path($coverImagePath), $coverImageName);

            // Save the file path in the database
            $program_events->cover_img = $coverImagePath . $coverImageName;
        }


        $program_events->events_package_images = json_encode($imagePaths);
        $program_events->upload_image_name = $request->input('upload_image_name');
        $program_events->alternate_image_name = $request->input('alternate_image_name');
        $program_events->title = $request->input('title');
        $program_events->event_type = $request->input('event_type');
        $program_events->timezone = $request->input('timezone');
        $program_events->start_datetime = $request->input('start_datetime');
        $program_events->end_datetime = $request->input('end_datetime');
        $program_events->location_name = $request->input('location_name');
        $program_events->location_address = $request->input('location_address');
        $program_events->latitude = $request->input('latitude');
        $program_events->longitude = $request->input('longitude');
        $program_events->location_type = $request->input('location_type');
        $program_events->event_description = $request->input('event_description');
        $program_events->save();

        return redirect()->route('admin.programeventslist')
                ->with('success', 'Record updated successfully');
    }

     public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $City = ProgramEvents::find($record_id);

        if ($City) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $City->status = "0";
            } else {
                $City->status = "1";
            }

            $City->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Event status changed successfully.'
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
        $City = ProgramEvents::find($record_id);
        if ($City) {
            // Update the is_deleted field to 1
            $City->is_deleted = "1";
            $City->save();

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
