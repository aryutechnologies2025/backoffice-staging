<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cab;
use App\Models\City;

class CabController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Cab List';
        $stay_details = Cab::where('is_deleted', '0')->get();
        return view('admin.cabs.cablist', compact('title', 'stay_details'));
    }

    public function add_form()
    {
        $cities = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');

        $title = 'Add Cab';

        return view('admin.cabs.cabadd', compact('title', 'cities'));
    }

    public function insert(Request $request)
    {
        // dd($request->all());
        $pricing = new Cab();
        $pricing->destination_id = $request->input('cities_name');
        $pricing->district_id = $request->input('district_name');
        $pricing->travel_mode = $request->input('travel_mode');
        // Convert array to JSON before storing
        $pricing->title_price = json_encode($request->input('camp_rules'));

        $pricing->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $pricing->is_deleted = '0';
        $pricing->save();

        return redirect()->route('admin.cablist')
            ->with('success', 'Cab created successfully.');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $City = Cab::find($record_id);

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
                'response' => 'Cab status changed successfully.'
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
        $City = Cab::find($record_id);
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

    public function edit_form(Request $request, $id)
    {
        $destination_details = Cab::find($id);
        $cities = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        $title = 'Edit Cab';

        $camp_rules = json_decode($destination_details->title_price, true);

        // dd($destination_details);
        return view('admin.cabs.cabedit', compact('destination_details', 'title', 'cities', 'camp_rules'));
    }

    public function update(Request $request, $id)
    {

        $pricing = Cab::findOrFail($id);
        $pricing->destination_id = $request->input('cities_name');
        $pricing->district_id = $request->input('district_name');
        $pricing->travel_mode = $request->input('travel_mode');
        $pricing->title_price = json_encode($request->input('camp_rules'));
        $pricing->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $pricing->save();

        return redirect()->route('admin.cablist')
            ->with('success', 'Cab updated successfully.');
    }

   
}
