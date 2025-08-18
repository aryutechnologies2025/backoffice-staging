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
        $pricingCalculators = PricingCalculator::where('is_deleted', '0')
            ->withSum('priceLists as total_pricing', 'price')
            ->get();
        return view('admin.pricingcalculator.pricinglist', compact('title', 'pricingCalculators'));
    }

    public function add_form()
    {
        $cities = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');

        $title = 'Add Pricing Calculator';

        return view('admin.pricingcalculator.pricingadd', compact('title', 'cities'));
    }

    public function insert(Request $request)
    {
        // dd($request->all());

         $existingPricing = PricingCalculator::where('destination_id', $request->input('cities_name'))
            ->where('district_id', $request->input('district_name'))
            ->where('is_deleted', '0')
            ->first();

        if ($existingPricing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['duplicate' => 'This destination and district combination already exists. Duplicate entries are not allowed.']);
        }


        $pricingcalculator_v = new PricingCalculator();

        $pricingcalculator_v->destination_id = $request->cities_name;
        $pricingcalculator_v->district_id = $request->district_name;
        $pricingcalculator_v->stays_id = $request->stay_id;
        $pricingcalculator_v->activitys_id = $request->activity_ids;
        $pricingcalculator_v->cab_details_id = $request->selected_cab_options;
        $pricingcalculator_v->cab_type = $request->cab_types;
        $pricingcalculator_v->save();


        if (isset($request->stays) && count($request->stays) > 0) {

            $stays = $request->stays;
            foreach ($stays as $val) {

                foreach ($val as $v) {
                    //  dd($v['stay_id']);
                    $pricingcalculator = new PriceCalculatorList();
                    $pricingcalculator->pricing_calculator_id = $pricingcalculator_v->id;
                    $pricingcalculator->type = 'stay';
                    $pricingcalculator->type_id = $v['stay_id'];
                    $pricingcalculator->title = $v['title'];
                    $pricingcalculator->price_title = $v['price_title'];
                    $pricingcalculator->price = $v['price'];
                    $pricingcalculator->save();
                }
            }
        }

        if (isset($request->activity) && count($request->activity) > 0) {

            $stays = $request->activity;

            foreach ($stays as $val) {
                foreach ($val as $v) {

                    // dd($v);
                    $pricingcalculator = new PriceCalculatorList();
                    $pricingcalculator->pricing_calculator_id = $pricingcalculator_v->id;
                    $pricingcalculator->type = 'activity';
                    $pricingcalculator->type_id = $v['activity_id'];
                    $pricingcalculator->title = $v['title'];
                    $pricingcalculator->price_title = $v['price_title'];
                    $pricingcalculator->price = $v['price'];
                    $pricingcalculator->save();
                }
            }
        }

        if (isset($request->cabs) && count($request->cabs) > 0) {

            $stays = $request->cabs;

            foreach ($stays as $val) {
                foreach ($val as $v) {
                    $pricingcalculator = new PriceCalculatorList();
                    $pricingcalculator->pricing_calculator_id = $pricingcalculator_v->id;
                    $pricingcalculator->type = 'cabs';
                    $pricingcalculator->type_id = $v['cab_id'];
                    $pricingcalculator->title = $v['title'];
                    $pricingcalculator->price_title = $v['price_title'];
                    $pricingcalculator->price = $v['price'];
                    $pricingcalculator->save();
                }
            }
        }

        // dd($request->all());

        return redirect()->route('admin.pricinglist')
            ->with('success', 'Pricing created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $destination_details = PricingCalculator::with('priceLists')
            ->findOrFail($id);

        $stay_details = PriceCalculatorList::where('pricing_calculator_id', $id)->where('type', 'stay')->get();

        // dd($stay_details);
        $cities = City::where('status', "1")->where('is_deleted', "0")->pluck('city_name', 'id');
        $title = 'Edit Pricing';
        return view('admin.pricingcalculator.pricingedit', compact('destination_details', 'title', 'cities'));
    }

    public function update(Request $request, $id)
    {

        // dd($request->all());
         $existingPricing = PricingCalculator::where('destination_id', $request->input('cities_name'))
            ->where('district_id', $request->input('district_name'))
            ->where('is_deleted', '0')
            ->where('id', '!=', $id)
            ->first();

        if ($existingPricing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['duplicate' => 'This destination and district combination already exists. Duplicate entries are not allowed.']);
        }

        $pricingcalculator_v = PricingCalculator::findOrFail($id);
        $pricingcalculator_v->destination_id = $request->cities_name;
        $pricingcalculator_v->district_id = $request->district_name;
        $pricingcalculator_v->stays_id = $request->stay_id;
        $pricingcalculator_v->activitys_id = $request->activity_ids;
        $pricingcalculator_v->cab_details_id = $request->selected_cab_options;
        $pricingcalculator_v->cab_type = $request->cab_types;
        $pricingcalculator_v->save();

        PriceCalculatorList::where('pricing_calculator_id', $id)->delete();

        if (isset($request->stays) && count($request->stays) > 0) {

            $stays = $request->stays;
            foreach ($stays as $val) {

                foreach ($val as $v) {
                    //  dd($v['stay_id']);
                    $pricingcalculator = new PriceCalculatorList();
                    $pricingcalculator->pricing_calculator_id = $pricingcalculator_v->id;
                    $pricingcalculator->type = 'stay';
                    $pricingcalculator->type_id = $v['stay_id'];
                    $pricingcalculator->title = $v['title'];
                    $pricingcalculator->price_title = $v['price_title'];
                    $pricingcalculator->price = $v['price'];
                    $pricingcalculator->save();
                }
            }
        }

        if (isset($request->activity) && count($request->activity) > 0) {

            $stays = $request->activity;

            foreach ($stays as $val) {
                foreach ($val as $v) {

                    // dd($v);
                    $pricingcalculator = new PriceCalculatorList();
                    $pricingcalculator->pricing_calculator_id = $pricingcalculator_v->id;
                    $pricingcalculator->type = 'activity';
                    $pricingcalculator->type_id = $v['activity_id'];
                    $pricingcalculator->title = $v['title'];
                    $pricingcalculator->price_title = $v['price_title'];
                    $pricingcalculator->price = $v['price'];
                    $pricingcalculator->save();
                }
            }
        }

        if (isset($request->cabs) && count($request->cabs) > 0) {

            $stays = $request->cabs;

            foreach ($stays as $val) {
                foreach ($val as $v) {
                    $pricingcalculator = new PriceCalculatorList();
                    $pricingcalculator->pricing_calculator_id = $pricingcalculator_v->id;
                    $pricingcalculator->type = 'cabs';
                    $pricingcalculator->type_id = $v['cab_id'];
                    $pricingcalculator->title = $v['title'];
                    $pricingcalculator->price_title = $v['price_title'];
                    $pricingcalculator->price = $v['price'];
                    $pricingcalculator->save();
                }
            }
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

    public function pricing_details_copy(Request $request)
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

    public function pricing_details(Request $request)
    {
        $destination = $request->destination;
        $district = $request->district;
        $stays = StayPricing::where('destination_id', $destination)
            ->where('district_id', '=', $district)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => ucfirst(strtolower($item->title))];
            })
            ->toArray();

        $activities = ActivityP::where('destination_id', $destination)
            ->where('district_id', $district)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => ucfirst(strtolower($item->title))];
            })
            ->toArray();

        $cabs = ['bus' => 'Bus', 'cab' => 'Cab'];

        return response()->json([
            'stays' => $stays,
            'activities' => $activities,
            'cabs' => $cabs,
        ]);
    }

    public function travel_details(Request $request)
    {
        $destination = $request->destination;
        $district = $request->district;
        $travelmodes = $request->travelmodes;

        $cabs = Cab::where('destination_id', $destination)
            ->where('district_id', $district)
            ->whereIn('travel_mode', $travelmodes)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => ucfirst(strtolower($item->title))];
            })
            ->toArray();

        return response()->json([
            'cabs' => $cabs,
        ]);
    }

    public function stay_details_copy(Request $request)
    {
        $destination = $request->destination;
        $district = $request->district;

        $staydetails = $request->staydetails;

        $stays = StayPricing::where('destination_id', $destination)
            ->where('district_id', $district)
            ->whereIn('id', $staydetails)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title_price', 'title')
            ->get()
            ->map(function ($stay) {
                $priceData = json_decode($stay->title_price);
                $formattedPrices = [];

                foreach ($priceData as $price) {
                    $formattedPrices[] = [
                        'stay_id' => $stay->id,
                        'title' => $stay->title,
                        'price_title' => $price->title,
                        'price' => $price->price
                    ];
                }

                return $formattedPrices;
            })
            // ->flatten(1) // Flatten the array of arrays
            ->toArray();

        return response()->json([
            'stays_details' => $stays
        ]);
    }

    public function stay_details(Request $request)
    {
        $destination = $request->destination;
        $district = $request->district;
        $staydetails = $request->staydetails;

        // Initialize existing prices as empty collection
        $existingPrices = collect();

        // Only check existing prices if calculator ID exists (update case)
        if ($request->has('pricing_calculator_id')) {
            $calculatorId = $request->pricing_calculator_id;
            $existingPrices = PriceCalculatorList::where('pricing_calculator_id', $calculatorId)
                ->where('type', 'stay')
                ->get()
                ->keyBy(function ($item) {
                    return $item->type_id . '|' . $item->price_title;
                });
        }

        $stays = StayPricing::where('destination_id', $destination)
            ->where('district_id', $district)
            ->whereIn('id', $staydetails)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title_price', 'title')
            ->get()
            ->map(function ($stay) use ($existingPrices) {
                $priceData = json_decode($stay->title_price);
                $formattedPrices = [];

                foreach ($priceData as $price) {
                    $key = $stay->id . '|' . $price->title;
                    $existingPrice = $existingPrices->get($key);

                    $formattedPrices[] = [
                        'stay_id' => $stay->id,
                        'title' => $stay->title,
                        'price_title' => $price->title,
                        'price' => $existingPrice ? $existingPrice->price : $price->price
                    ];
                }

                return $formattedPrices;
            })
            ->toArray();

        return response()->json([
            'stays_details' => $stays
        ]);
    }

    public function activity_details_copy(Request $request)
    {
        $destination = $request->destination;
        $district = $request->district;

        $staydetails = $request->staydetails;
        $activitys = ActivityP::where('destination_id', $destination)
            ->where('district_id', $district)
            ->whereIn('id', $staydetails)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title_price', 'title')
            ->get()
            ->map(function ($stay) {
                $priceData = json_decode($stay->title_price);
                $formattedPrices = [];

                foreach ($priceData as $price) {
                    $formattedPrices[] = [
                        'activity_id' => $stay->id,
                        'title' => $stay->title,
                        'price_title' => $price->title,
                        'price' => $price->price
                    ];
                }

                return $formattedPrices;
            })
            // ->flatten(1) // Flatten the array of arrays
            ->toArray();

        return response()->json([
            'activity_details' => $activitys
        ]);
    }

    public function activity_details(Request $request)
    {
        $destination = $request->destination;
        $district = $request->district;
        $activitydetails = $request->staydetails; // Note: Consider renaming to activitydetails for clarity

        // Initialize existing prices as empty collection
        $existingPrices = collect();

        // Only check existing prices if calculator ID exists (update case)
        if ($request->has('pricing_calculator_id')) {
            $calculatorId = $request->pricing_calculator_id;
            $existingPrices = PriceCalculatorList::where('pricing_calculator_id', $calculatorId)
                ->where('type', 'activity')
                ->get()
                ->keyBy(function ($item) {
                    return $item->type_id . '|' . $item->price_title;
                });
        }

        $activities = ActivityP::where('destination_id', $destination)
            ->where('district_id', $district)
            ->whereIn('id', $activitydetails)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title_price', 'title')
            ->get()
            ->map(function ($activity) use ($existingPrices) {
                $priceData = json_decode($activity->title_price);
                $formattedPrices = [];

                foreach ($priceData as $price) {
                    $key = $activity->id . '|' . $price->title;
                    $existingPrice = $existingPrices->get($key);

                    $formattedPrices[] = [
                        'activity_id' => $activity->id,
                        'title' => $activity->title,
                        'price_title' => $price->title,
                        'price' => $existingPrice ? $existingPrice->price : $price->price
                    ];
                }

                return $formattedPrices;
            })
            ->toArray();

        return response()->json([
            'activity_details' => $activities
        ]);
    }

    public function cabs_details_copy(Request $request)
    {
        // dd($request->all());
        $destination = $request->destination;
        $district = $request->district;

        $travelmodes = $request->travelmodes;
        $cabdetails = $request->cabdetails;
        $activitys = Cab::where('destination_id', $destination)
            ->where('district_id', $district)
            ->whereIn('travel_mode', $travelmodes)
            ->whereIn('id', $cabdetails)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title_price', 'title')
            ->get()
            ->map(function ($stay) {
                $priceData = json_decode($stay->title_price);
                $formattedPrices = [];

                foreach ($priceData as $price) {
                    $formattedPrices[] = [
                        'cab_id' => $stay->id,
                        'title' => $stay->title,
                        'price_title' => $price->title,
                        'price' => $price->price
                    ];
                }

                return $formattedPrices;
            })
            // ->flatten(1) // Flatten the array of arrays
            ->toArray();

        return response()->json([
            'activity_details' => $activitys
        ]);
    }

    public function cabs_details(Request $request)
    {
        // dd($request->all());
        $destination = $request->destination;
        $district = $request->district;

        $travelmodes = $request->travelmodes;
        $cabdetails = $request->cabdetails;

        $existingPrices = collect();

        // Only check existing prices if calculator ID exists (update case)
        if ($request->has('pricing_calculator_id')) {
            $calculatorId = $request->pricing_calculator_id;
            $existingPrices = PriceCalculatorList::where('pricing_calculator_id', $calculatorId)
                ->where('type', 'cabs')
                ->get()
                ->keyBy(function ($item) {
                    return $item->type_id . '|' . $item->price_title;
                });
        }
        $activitys = Cab::where('destination_id', $destination)
            ->where('district_id', $district)
            ->whereIn('travel_mode', $travelmodes)
            ->whereIn('id', $cabdetails)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title_price', 'title')
            ->get()
            ->map(function ($stay) use ($existingPrices) {
                $priceData = json_decode($stay->title_price);
                $formattedPrices = [];

                foreach ($priceData as $price) {
                    $key = $stay->id . '|' . $price->title;
                    $existingPrice = $existingPrices->get($key);
                    $formattedPrices[] = [
                        'cab_id' => $stay->id,
                        'title' => $stay->title,
                        'price_title' => $price->title,
                        // 'price' => $price->price
                        'price' => $existingPrice ? $existingPrice->price : $price->price
                    ];
                }

                return $formattedPrices;
            })
            // ->flatten(1) // Flatten the array of arrays
            ->toArray();

        return response()->json([
            'activity_details' => $activitys
        ]);
    }
}
