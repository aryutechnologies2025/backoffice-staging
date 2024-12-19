<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\City;

class EventController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Events List';
        return view('admin.events.eventlist', compact('title'));
    }

    public function add_form()
    {
        $title = 'Event Add';
        $cities = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        return view('admin.events.eventadd', compact('title', 'cities'));
    }

    public function insert(Request $request)
    {

        $validatedData = $request->validate([
            'event_name' => 'required',
            'state' => 'required',
            'city' => 'required',
            'description' => 'required',

        ]);

        $destinationPath = public_path('/uploads/events_img');

        // Ensure the directory exists
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Initialize file paths
        $filePath1 = $filePath2 = $filePath3 = $filePath4 = '-';

        // Handle image uploads
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $file1->move($destinationPath, $filename1);
            $filePath1 = 'uploads/events_img/' . $filename1;
        }

        if ($request->hasFile('image_2')) {
            $file2 = $request->file('image_2');
            $filename2 = time() . '_2.' . $file2->getClientOriginalExtension();
            $file2->move($destinationPath, $filename2);
            $filePath2 = 'uploads/events_img/' . $filename2;
        }

        if ($request->hasFile('image_3')) {
            $file3 = $request->file('image_3');
            $filename3 = time() . '_3.' . $file3->getClientOriginalExtension();
            $file3->move($destinationPath, $filename3);
            $filePath3 = 'uploads/events_img/' . $filename3;
        }

        if ($request->hasFile('image_4')) {
            $file4 = $request->file('image_4');
            $filename4 = time() . '_4.' . $file4->getClientOriginalExtension();
            $file4->move($destinationPath, $filename4);
            $filePath4 = 'uploads/events_img/' . $filename4;
        }


        $Event = new Event;
        $Event->event_name = $request->input('event_name');
        $Event->state = $request->input('state');
        $Event->city = $request->input('city');
        $Event->description = $request->input('description');
        $Event->image_1 = $filePath1;
        $Event->image_2 = $filePath2;
        $Event->image_3 = $filePath3;
        $Event->image_4 = $filePath4;
        $Event->created_date = date('Y-m-d H:i:s');
        $Event->is_deleted = '0';
        $Event->save();

        return redirect()->route('admin.eventList')
            ->with('success', 'Event created successfully.');
    }
}
