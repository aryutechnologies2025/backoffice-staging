<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\PricingCalculator;
use App\Models\ActivityP;
use App\Models\Cab;
use App\Models\StayPricing;

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
        $activity = ActivityP::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        $cabs = Cab::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        $stays = StayPricing::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');

        $title = 'Add Pricing Calculator';

        return view('admin.pricingcalculator.pricingadd', compact('title', 'cities'));
    }

    public function insert(Request $request)
    {

        $pricing = new PricingCalculator();
        $pricing->destination_id = $request->input('cities_name');
        $pricing->district_id = $request->input('district_name');

        // Convert array to JSON before storing
        $pricing->title_price = json_encode($request->input('camp_rules'));

        $pricing->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $pricing->is_deleted = '0';
        $pricing->save();

        return redirect()->route('admin.pricinglist')
            ->with('success', 'Pricing created successfully.');
    }
}
