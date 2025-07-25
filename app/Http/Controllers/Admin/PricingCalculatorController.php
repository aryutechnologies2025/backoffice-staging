<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\PricingCalculator;
use App\Models\StayPricing;
use App\Models\Cab;
use App\Models\ActivityP;
use App\Models\PriceCalculatorList;

class PricingCalculatorController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Pricing Calculator List';
        $stay_details = PricingCalculator::where('is_deleted', '0')->get();
        return view('admin.pricingcalculator.pricinglist', compact('title', 'stay_details'));
    }

    public function add_form()
    {
        $cities = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');

        $title = 'Add Pricing Calculator';

        return view('admin.pricingcalculator.pricingadd', compact('title', 'cities'));
    }

    public function insert(Request $request)
    {

        $pricing = new PricingCalculator();

        // Check if a record with the same destination_id and district_id already exists
        $existingPricing = PricingCalculator::where('destination_id', $request->input('cities_name'))
            ->where('district_id', $request->input('district_name'))
            ->where('is_deleted', '0')
            ->first();

        if ($existingPricing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['duplicate' => 'This destination and district combination already exists. Duplicate entries are not allowed.']);
        }

        $pricing->destination_id = $request->input('cities_name');
        $pricing->district_id = $request->input('district_name');
        $pricing->total_pricing = $request->input('total_amount');

        $pricing->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $pricing->is_deleted = '0';
        $pricing->save();

        $selected_items = json_decode($request->selected_items, true);

        $selected_items = array_filter($selected_items, function ($item) {
            return !empty($item) && isset($item['id'], $item['type'], $item['title'], $item['price']);
        });

        foreach ($selected_items as $item) {
            $priceItem = new PriceCalculatorList();
            $priceItem->pricing_calculator_id = $pricing->id;
            $priceItem->type_id = $item['id'];
            $priceItem->type = $item['type'];
            $priceItem->title = $item['title'];
            $priceItem->price = $item['price'];
            $priceItem->save();
        }


        return redirect()->route('admin.pricinglist')
            ->with('success', 'Pricing created successfully.');
    }

    public function pricing_details(Request $request)
    {
        $destination = $request->destination;
        $district = $request->district;

        // Get stay pricing data
        $stays = StayPricing::where('destination_id', $destination)
            ->where('district_id', $district)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title_price') // Select both id and title_price
            ->get()
            ->map(function ($item) {
                $decoded = json_decode($item->title_price, true) ?? [];
                // Include the ID in each item
                return array_map(function ($stay) use ($item) {
                    return array_merge($stay, ['id' => $item->id]);
                }, $decoded);
            })
            ->filter() // Remove empty arrays
            ->toArray();

        // Get activity pricing data
        $activities = ActivityP::where('destination_id', $destination)
            ->where('district_id', $district)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title_price') // Select both id and title_price
            ->get()
            ->map(function ($item) {
                $decoded = json_decode($item->title_price, true) ?? [];
                // Include the ID in each activity item
                return array_map(function ($activity) use ($item) {
                    return array_merge($activity, ['id' => $item->id]);
                }, $decoded);
            })
            ->filter() // Remove empty arrays
            ->toArray();

        // Get cabs pricing data
        $cabs = Cab::where('destination_id', $destination)
            ->where('district_id', $district)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title_price') // Select both id and title_price
            ->get()
            ->map(function ($item) {
                $decoded = json_decode($item->title_price, true) ?? [];
                // Include the ID in each cab item
                return array_map(function ($cab) use ($item) {
                    return array_merge($cab, ['id' => $item->id]);
                }, $decoded);
            })
            ->filter() // Remove empty arrays
            ->toArray();

        return response()->json([
            'stays' => $stays,
            'activities' => $activities,
            'cabs' => $cabs,
        ]);
    }


    public function edit_form(Request $request, $id)
    {
        $destination_details = PricingCalculator::with('priceLists')
            ->findOrFail($id);

        // dd($destination_details);
        $cities = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        $title = 'Edit Pricing';


        $price_lists = $destination_details->priceLists ?? [];

        // dd($price_lists);
        return view('admin.pricingcalculator.pricingedit', compact('destination_details', 'title', 'cities', 'price_lists'));
    }

    public function update(Request $request, $id)
    {


        // Check for duplicates EXCLUDING the current record
        $existingPricing = PricingCalculator::where('destination_id', $request->input('cities_name'))
            ->where('district_id', $request->input('district_name'))
            ->where('is_deleted', '0')
            ->where('id', '!=', $id)  // Exclude current record
            ->first();

        if ($existingPricing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['duplicate' => 'This destination and district combination already exists. Duplicate entries are not allowed.']);
        }

        // dd($request->all());
        $pricing = PricingCalculator::findOrFail($id);
        $pricing->destination_id = $request->input('cities_name');
        $pricing->district_id = $request->input('district_name');
        $pricing->total_pricing = $request->input('total_amount');

        $pricing->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $pricing->is_deleted = '0';
        $pricing->save();

        // Delete all existing price items for this pricing calculator
        PriceCalculatorList::where('pricing_calculator_id', $id)->delete();

        $selected_items = json_decode($request->selected_items, true);

        $selected_items = array_filter($selected_items, function ($item) {
            return !empty($item) && isset($item['id'], $item['type'], $item['title'], $item['price']);
        });

        foreach ($selected_items as $item) {
            $priceItem = new PriceCalculatorList();
            $priceItem->pricing_calculator_id = $pricing->id;
            $priceItem->type_id = $item['id'];
            $priceItem->type = $item['type'];
            $priceItem->title = $item['title'];
            $priceItem->price = $item['price'];
            $priceItem->save();
        }

        return redirect()->route('admin.pricinglist')
            ->with('success', 'Pricing updated successfully.');
    }

    public function change_status(Request $request)
    {
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        $City = PricingCalculator::find($record_id);

        if ($City) {
            if ($mode == 0) {
                $City->status = "0";
            } else {
                $City->status = "1";
            }

            $City->save();

            $response = [
                'status' => '1',
                'response' => 'Pricing status changed successfully.'
            ];
        } else {
            $response = [
                'status' => '0',
                'response' => 'Record not found.'
            ];
        }

        return response()->json($response);
    }

    public function delete(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');

        // Find the admin record by ID
        $City = PricingCalculator::find($record_id);
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
