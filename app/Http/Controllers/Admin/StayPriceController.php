<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StayPricing;
use Illuminate\Http\Request;
use App\Models\City;

class StayPriceController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Stay Pricing List';
        $stay_details = StayPricing::where('is_deleted', '0')->get();
        return view('admin.stay_pricing.staypricinglist', compact('title', 'stay_details'));
    }

    public function add_form()
    {
        $cities = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');

        $title = 'Add Stay Pricing';

        return view('admin.stay_pricing.staypricingadd', compact('title', 'cities'));
    }

    public function insert(Request $request)
    {

        // Check if a record with the same destination_id and district_id already exists
        $existingPricing = StayPricing::where('destination_id', $request->input('cities_name'))
            ->where('district_id', $request->input('district_name'))
            ->where('is_deleted', '0')
            ->first();

        if ($existingPricing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['duplicate' => 'This destination and district combination already exists. Duplicate entries are not allowed.']);
        }

        $pricing = new StayPricing();
        $pricing->destination_id = $request->input('cities_name');
        $pricing->district_id = $request->input('district_name');

        $pricing->title_price = json_encode($request->input('camp_rules'));

        $pricing->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $pricing->is_deleted = '0';
        $pricing->save();

        return redirect()->route('admin.staypricinglist')
            ->with('success', 'Pricing created successfully.');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $City = StayPricing::find($record_id);

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
                'response' => 'Pricing status changed successfully.'
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
        $City = StayPricing::find($record_id);
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
        $destination_details = StayPricing::find($id);
        $cities = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        $title = 'Edit Stay Pricing';

        $camp_rules = json_decode($destination_details->title_price, true);

        // dd($destination_details);
        return view('admin.stay_pricing.staypricingedit', compact('destination_details', 'title', 'cities', 'camp_rules'));
    }

    public function update(Request $request, $id)
    {
        $pricing = StayPricing::findOrFail($id);

        // Check for duplicates EXCLUDING the current record
        $existingPricing = StayPricing::where('destination_id', $request->input('cities_name'))
            ->where('district_id', $request->input('district_name'))
            ->where('is_deleted', '0')
            ->where('id', '!=', $id)  // Exclude current record
            ->first();

        if ($existingPricing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['duplicate' => 'This destination and district combination already exists. Duplicate entries are not allowed.']);
        }


        // Filter out removed items and reindex array
        $campRules = array_values(array_filter($request->camp_rules, function ($rule) {
            return !isset($rule['removed']);
        }));

        $pricing->destination_id = $request->input('cities_name');
        $pricing->district_id = $request->input('district_name');
        $pricing->title_price = json_encode($campRules);
        $pricing->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $pricing->save();

        return redirect()->route('admin.staypricinglist')
            ->with('success', 'Pricing updated successfully.');
    }

    public function get_stay_destination(Request $request)
    {
        $city_dts = stay_desitination::where('is_deleted', '0')
            ->where('status', '1')
            ->select('id', 'city_name', 'city_image', 'upload_image_name', 'alternate_name')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Stays city successfully retrieved.',
            'data' => $city_dts,
        ]);
    }
}



