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

        // Validation rules
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'hosted_by' => 'required|string|max:255',
            'welcome_msg' => 'required|string',
            'send_link' => 'required|url',
            'embed_map' => 'required|string',
            'event_description' => 'required|string',
            'location_address' => 'required' // 2MB max
        ], [
            // 'cover_img.required' => 'The cover image is required.',
            'end_datetime.after' => 'The end datetime must be after the start datetime.',
            'send_link.url' => 'The send link must be a valid URL.'
        ]);


        $filePath1 = null;
        if ($request->hasFile('cover_img')) {
            $file1 = $request->file('cover_img');

            // Get the original filename without extension
            $originalName = pathinfo($file1->getClientOriginalName(), PATHINFO_FILENAME);

            // Add timestamp and random number to original filename
            $timestamp = now()->format('YmdHis');
            $random = rand(100000, 999999);
            $filename1 = $originalName . '_' . $timestamp . '_' . $random . '.' . $file1->getClientOriginalExtension();

            $file1->move(public_path('/uploads/events_program_images'), $filename1);
            $filePath1 = '/uploads/events_program_images/' . $filename1;
        }

        $programevents = new ProgramEvents();
        $programevents->cover_img = $filePath1;
        $programevents->upload_image_name = $request->input('upload_image_name');
        $programevents->alternate_image_name = $request->input('alternate_image_name');
        $programevents->event_name = $request->input('title');
        $programevents->start_datetime = $request->input('start_datetime');
        $programevents->end_datetime = $request->input('end_datetime');
        $programevents->send_link = $request->input('send_link');
        $programevents->embed_map = $request->input('embed_map');
        $programevents->welcome_msg = $request->input('welcome_msg');
        $programevents->hosted_by = $request->input('hosted_by');
        $programevents->event_description = $request->input('event_description');
        $programevents->location_address = $request->input('location_address');
        
        $programevents->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $programevents->save();

        if ($programevents) {
            return redirect()->route('admin.programeventslist')
                ->with('success', 'Record inserted successfully');
        } else {
            return redirect()->route('admin.programeventslist')
                ->with('error', 'Error inserting record');
        }
    }

    public function edit(Request $request, $id)
    {
        $title = 'Edit Event';
        $programdetails = ProgramEvents::where('id', $id)->where('is_deleted', '0')->orderBy('id', 'desc')->first();
        return view('admin.programevents.programEventEdit', compact('title', 'programdetails'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'hosted_by' => 'required|string|max:255',
            'welcome_msg' => 'required|string',
            'send_link' => 'required|url',
            'embed_map' => 'required|string',
            'event_description' => 'required|string',
            'location_address' => 'required' // 2MB max
        ], [
            // 'cover_img.required' => 'The cover image is required.',
            'end_datetime.after' => 'The end datetime must be after the start datetime.',
            'send_link.url' => 'The send link must be a valid URL.'
        ]);

        $program_events = ProgramEvents::find($id);
        if (!$program_events) {
            return redirect()->route('admin.programeventslist')
                ->with('error', 'Record not found');
        }

        // Handle cover image upload
        if ($request->hasFile('cover_img')) {
            $coverImage = $request->file('cover_img');

            // Sanitize the file name
            $originalName = pathinfo($coverImage->getClientOriginalName(), PATHINFO_FILENAME);
            $timestamp = now()->format('YmdHis');
            $random = rand(100000, 999999);
            $filename = $originalName . '_' . $timestamp . '_' . $random . '.' . $coverImage->getClientOriginalExtension();

            $coverImage->move(public_path('/uploads/events_program_images'), $filename);
            $filePath = '/uploads/events_program_images/' . $filename;

            // Delete old cover image if exists
            if ($program_events->cover_img && file_exists(public_path($program_events->cover_img))) {
                unlink(public_path($program_events->cover_img));
            }

            $program_events->cover_img = $filePath;
        }

        // Update other fields
        $program_events->upload_image_name = $request->input('upload_image_name');
        $program_events->alternate_image_name = $request->input('alternate_image_name');
        $program_events->event_name = $request->input('title'); // This is the title field
        $program_events->start_datetime = $request->input('start_datetime');
        $program_events->end_datetime = $request->input('end_datetime');
        $program_events->send_link = $request->input('send_link');
        $program_events->embed_map = $request->input('embed_map');
        $program_events->welcome_msg = $request->input('welcome_msg');
        $program_events->hosted_by = $request->input('hosted_by');
        $program_events->event_description = $request->input('event_description');
        $program_events->location_address = $request->input('location_address');
        $program_events->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
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
