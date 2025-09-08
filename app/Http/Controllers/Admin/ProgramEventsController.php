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
}
