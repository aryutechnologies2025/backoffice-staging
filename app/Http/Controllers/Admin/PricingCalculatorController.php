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
use App\Models\stay_district;
use Illuminate\Support\Facades\DB;


class PricingCalculatorController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Pricing Calculator List';

        try {
            $pricingCalculators = PricingCalculator::with('destinations')
                ->where('is_deleted', '0')
                ->orderBy('id', 'desc')
                ->with(['priceLists'])
                ->withSum('priceLists as total_pricing', 'price')
                ->get()
                ->map(function ($calculator) {
                    if (!empty($calculator->destination_id)) {
                        $destinationIds = explode(',', $calculator->destination_id);
                        $cities = City::whereIn('id', $destinationIds)->pluck('city_name')->toArray();

                        // Use $calculator, not $pricingCalculators
                        $calculator->destination_names = $cities;
                        $calculator->destination_names_string = implode(', ', $cities);
                    } else {
                        $calculator->destination_names = [];
                        $calculator->destination_names_string = '';
                    }
                    return $calculator; // Return the modified calculator
                });
            return view('admin.pricingcalculator.pricinglist', compact('title', 'pricingCalculators'));
        } catch (\Exception $e) {
            \Log::error('Error fetching pricing calculators: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load pricing calculator list');
        }
    }

    public function add_form()
    {
        $cities = City::where('status', '1')->where('is_deleted', '0')->pluck('city_name', 'id');

        $title = 'Add Pricing Calculator';

        return view('admin.pricingcalculator.pricingadd', compact('title', 'cities'));
    }

    public function insert(Request $request)
    {

        // dd($request->all());
        $destination_id = $request->cities_name;
        // Store as comma-separated
        $destination_id = implode(',', $destination_id);

        $district_name = $request->district_name;
        $district_id = implode(',', $district_name);

        $pricingcalculator_v = new PricingCalculator();
        $pricingcalculator_v->destination_id = $destination_id;
        $pricingcalculator_v->title = $request->title;
        $pricingcalculator_v->service_fee = $request->service_fee;
        $pricingcalculator_v->district_id = $district_id;
        $pricingcalculator_v->grand_total = $request->grand_total;
        // Store stay IDs as comma-separated string or first ID
        $stayIds = $request->stay_id;
        $pricingcalculator_v->stays_id = $stayIds;
$pricingcalculator_v->created_by = auth()->user()->email;
        $pricingcalculator_v->activitys_id = $request->activity_ids;
        $pricingcalculator_v->cab_details_id = $request->selected_cab_options;
        $pricingcalculator_v->cab_type = $request->cab_types;
        $pricingcalculator_v->save();

        // Store selected stay titles
        if ($request->has('selected_stay_titles') && !empty($request->selected_stay_titles)) {
            $selectedTitles = json_decode($request->selected_stay_titles, true);
            if (is_array($selectedTitles) && count($selectedTitles) > 0) {
                foreach ($selectedTitles as $title) {
                    $pricingcalculator = new PriceCalculatorList();
                    $pricingcalculator->pricing_calculator_id = $pricingcalculator_v->id;
                    $pricingcalculator->type = 'stay';
                    $pricingcalculator->type_id = $title['stay_id'];
                    $pricingcalculator->title = $title['title'];
                    $pricingcalculator->price_title = $title['price_title'];
                    $pricingcalculator->price = $title['price'];
                    $pricingcalculator->save();
                }
            }
        }

        // Store selected activity titles
        if ($request->has('selected_activity_titles') && !empty($request->selected_activity_titles)) {
            $selectedTitles = json_decode($request->selected_activity_titles, true);

            if (is_array($selectedTitles) && count($selectedTitles) > 0) {
                foreach ($selectedTitles as $title) {
                    $pricingcalculator = new PriceCalculatorList();
                    $pricingcalculator->pricing_calculator_id = $pricingcalculator_v->id;
                    $pricingcalculator->type = 'activity';
                    $pricingcalculator->type_id = $title['activity_id'];
                    $pricingcalculator->title = $title['title'];
                    $pricingcalculator->price_title = $title['price_title'];
                    $pricingcalculator->price = $title['price'];
                    $pricingcalculator->save();
                }
            }
        }

        // Store selected cab titles
        if ($request->has('selected_cab_titles') && !empty($request->selected_cab_titles)) {
            $selectedTitles = json_decode($request->selected_cab_titles, true);

            if (is_array($selectedTitles) && count($selectedTitles) > 0) {
                foreach ($selectedTitles as $title) {
                    $pricingcalculator = new PriceCalculatorList();
                    $pricingcalculator->pricing_calculator_id = $pricingcalculator_v->id;
                    $pricingcalculator->type = 'cabs';
                    $pricingcalculator->type_id = $title['cab_id'];
                    $pricingcalculator->title = $title['title'];
                    $pricingcalculator->price_title = $title['price_title'];
                    $pricingcalculator->price = $title['price'];
                    $pricingcalculator->save();
                }
            }
        }

        return redirect()->route('admin.pricinglist')
            ->with('success', 'Pricing created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $destination_details = PricingCalculator::with('priceLists')
            ->findOrFail($id);

        $stay_details = PriceCalculatorList::where('pricing_calculator_id', $id)->where('type', 'stay')->get();

        // dd($stay_details);
        $cities = City::where('status', '1')->where('is_deleted', '0')->pluck('city_name', 'id');
        $title = 'Edit Pricing';

        // Convert comma-separated destination_id to array
        $selectedDestinations = explode(',', $destination_details->destination_id);
        // Convert comma-separated district_id to array
        $selectedDistricts = explode(',', $destination_details->district_id);

        $stay_id = PriceCalculatorList::where('type', 'stay')->where('pricing_calculator_id', $id)->pluck('type_id')->toArray();
        $activity_id = PriceCalculatorList::where('type', 'activity')->where('pricing_calculator_id', $id)->pluck('type_id')->toArray();
        $cabs_id = PriceCalculatorList::where('type', 'cabs')->where('pricing_calculator_id', $id)->pluck('type_id')->toArray();

        return view('admin.pricingcalculator.pricingedit', compact('destination_details', 'title', 'cities', 'selectedDestinations', 'selectedDistricts',  'stay_id', 'activity_id', 'cabs_id'));
    }

    public function update(Request $request, $id)
    {

        // dd($request->all());
        // $pricingcalculator_v = PricingCalculator::findOrFail($id);
        // $pricingcalculator_v->destination_id = $request->cities_name;
        // $pricingcalculator_v->title = $request->title;
        // $pricingcalculator_v->district_id = $request->district_name;
        // $pricingcalculator_v->stays_id = $request->stay_id;
        // $pricingcalculator_v->activitys_id = $request->activity_ids;
        // $pricingcalculator_v->cab_details_id = $request->selected_cab_options;
        // $pricingcalculator_v->cab_type = $request->cab_types;
        // $pricingcalculator_v->grand_total = $request->grand_total ?? 0;
        // $pricingcalculator_v->save();

        $pricingcalculator_v = PricingCalculator::findOrFail($id);

        $destination_id = $request->cities_name;
        // Check if it's an array before imploding
        if ($destination_id) {
            if (is_array($destination_id)) {
                $destination_id = implode(',', $destination_id);
            } else {
                // If it's already a string, use it as is
                $destination_id = $destination_id;
            }

            $pricingcalculator_v->destination_id = $destination_id;
        }

        $district_name = $request->district_name;
        // Check if it's an array before imploding
        if ($district_name) {
            if (is_array($district_name)) {
                $district_id = implode(',', $district_name);
            } else {
                // If it's already a string, use it as is
                $district_id = $district_name;
            }

            $pricingcalculator_v->district_id = $district_id;
        }

        $pricingcalculator_v->title = $request->title;
        $pricingcalculator_v->service_fee = $request->service_fee;
        $pricingcalculator_v->grand_total = $request->grand_total;
        // Store stay IDs as comma-separated string or first ID
        $stayIds = $request->stay_id;
        $pricingcalculator_v->stays_id = $stayIds;

        $pricingcalculator_v->activitys_id = $request->activity_ids;
        $pricingcalculator_v->cab_details_id = $request->selected_cab_options;
        // $pricingcalculator_v->cab_type = $request->cab_types_val;
        $cabTypes = explode(',', $request->cab_types_val);
        $cleanedCabTypes = array_map(function ($type) {
            return strtolower(trim($type));
        }, $cabTypes);
        $pricingcalculator_v->cab_type = implode(',', $cleanedCabTypes);
        $pricingcalculator_v->save();

        PriceCalculatorList::where('pricing_calculator_id', $id)->delete();

        // Store selected stay titles
        if ($request->has('selected_stay_titles') && !empty($request->selected_stay_titles)) {
            $selectedTitles = json_decode($request->selected_stay_titles, true);
            if (is_array($selectedTitles) && count($selectedTitles) > 0) {
                foreach ($selectedTitles as $title) {
                    $pricingcalculator = new PriceCalculatorList();
                    $pricingcalculator->pricing_calculator_id = $pricingcalculator_v->id;
                    $pricingcalculator->type = 'stay';
                    $pricingcalculator->type_id = $title['stay_id'];
                    $pricingcalculator->title = $title['title'];
                    $pricingcalculator->price_title = $title['price_title'];
                    $pricingcalculator->price = $title['price'];
                    $pricingcalculator->save();
                }
            }
        }

        // Store selected activity titles
        if ($request->has('selected_activity_titles') && !empty($request->selected_activity_titles)) {
            $selectedTitles = json_decode($request->selected_activity_titles, true);

            if (is_array($selectedTitles) && count($selectedTitles) > 0) {
                foreach ($selectedTitles as $title) {
                    $pricingcalculator = new PriceCalculatorList();
                    $pricingcalculator->pricing_calculator_id = $pricingcalculator_v->id;
                    $pricingcalculator->type = 'activity';
                    $pricingcalculator->type_id = $title['activity_id'];
                    $pricingcalculator->title = $title['title'];
                    $pricingcalculator->price_title = $title['price_title'];
                    $pricingcalculator->price = $title['price'];
                    $pricingcalculator->save();
                }
            }
        }

        // Store selected cab titles
        if ($request->has('selected_cab_titles') && !empty($request->selected_cab_titles)) {
            $selectedTitles = json_decode($request->selected_cab_titles, true);

            if (is_array($selectedTitles) && count($selectedTitles) > 0) {
                foreach ($selectedTitles as $title) {
                    $pricingcalculator = new PriceCalculatorList();
                    $pricingcalculator->pricing_calculator_id = $pricingcalculator_v->id;
                    $pricingcalculator->type = 'cabs';
                    $pricingcalculator->type_id = $title['cab_id'];
                    $pricingcalculator->title = $title['title'];
                    $pricingcalculator->price_title = $title['price_title'];
                    $pricingcalculator->price = $title['price'];
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
    public function pricing_details(Request $request)
    {
        try {
            // Get request values
            $destination = $request->destination;
            $district = $request->district;

            // ✅ Ensure destination is an array
            if (!is_array($destination)) {
                $destination = explode(',', $destination); // Convert "13,15" → [13,15]
            }

            // ✅ Ensure district is an array
            if (!is_array($district)) {
                $district = explode(',', $district); // Convert "1,2,3,7,8,9,10" → [1,2,3,7,8,9,10]
            }

            // ✅ Fetch stays
            $stays = StayPricing::whereIn('destination_id', $destination)
                ->whereIn('district_id', $district)
                ->where('status', '1')
                ->where('is_deleted', '0')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->id => ucfirst(strtolower($item->title))];
                })
                ->toArray();

            // ✅ Fetch activities
            $activities = ActivityP::whereIn('destination_id', $destination)
                ->whereIn('district_id', $district)
                ->where('status', '1')
                ->where('is_deleted', '0')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->id => ucfirst(strtolower($item->title))];
                })
                ->toArray();

            // ✅ Static cab options
            $cabs = [
                'bus' => 'Bus',
                'cab' => 'Cab'
            ];

            return response()->json([
                'stays' => $stays,
                'activities' => $activities,
                'cabs' => $cabs,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in pricing_details: ' . $e->getMessage());
            return response()->json([
                'error' => 'Something went wrong.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function travel_details(Request $request)
    {
        $destination = $request->destination;
        $district = $request->district;
        $travelmodes = $request->travelmodes;
        // ✅ Ensure destination is an array
        if (!is_array($destination)) {
            $destination = explode(',', $destination); // Convert "13,15" → [13,15]
        }

        // ✅ Ensure district is an array
        if (!is_array($district)) {
            $district = explode(',', $district); // Convert "1,2,3,7,8,9,10" → [1,2,3,7,8,9,10]
        }
        // Initialize existing prices as empty collection
        // $existingPrices = collect();

        $staysDistrict = stay_district::whereIn('id', $destination)
            ->pluck('destination')
            ->toArray();
        $cabs = Cab::whereIn('destination_id', $destination)
            ->whereIn('district_id', $district)
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
        // dd($cabs);
    }

    public function stay_edit_details(Request $request)
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

    public function stay_details(Request $request)
    {
        $destination = $request->destination;
        $district = $request->district;
        $staydetails = $request->staydetails;

        // ✅ Ensure destination is an array
        if (!is_array($destination)) {
            $destination = explode(',', $destination);
        }

        // ✅ Ensure district is an array
        if (!is_array($district)) {
            $district = explode(',', $district);
        }

        // Initialize existing prices and checked items
        $existingPrices = collect();
        $checkedItems = collect();

        // Only check existing prices if calculator ID exists (update case)
        if ($request->has('pricing_calculator_id')) {
            $calculatorId = $request->pricing_calculator_id;
            $existingPrices = PriceCalculatorList::where('pricing_calculator_id', $calculatorId)
                ->where('type', 'stay')
                ->get();

            // Create a collection of checked items
            $checkedItems = $existingPrices->keyBy(function ($item) {
                return $item->type_id . '|' . $item->price_title;
            });
        }

        $stays = StayPricing::whereIn('destination_id', $destination)
            ->whereIn('district_id', $district)
            ->whereIn('id', $staydetails)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title_price', 'title')
            ->get()
            ->map(function ($stay) use ($existingPrices, $checkedItems) {
                $priceData = json_decode($stay->title_price);
                $formattedPrices = [];

                foreach ($priceData as $price) {
                    $key = $stay->id . '|' . $price->title;
                    $existingPrice = $existingPrices->where('type_id', $stay->id)
                        ->where('price_title', $price->title)
                        ->first();

                    $formattedPrices[] = [
                        'stay_id' => $stay->id,
                        'title' => $stay->title,
                        'price_title' => $price->title,
                        'price' => $existingPrice ? $existingPrice->price : $price->price,
                        'is_checked' => $checkedItems->has($key) // Add this field
                    ];
                }

                return $formattedPrices;
            })
            ->toArray();

        return response()->json([
            'stays_details' => $stays
        ]);
    }

    public function activity_details(Request $request)
    {
        $destination = $request->destination;
        $district = $request->district;
        $activitydetails = $request->staydetails ?? [];

        // ✅ Ensure destination is an array
        if (!is_array($destination)) {
            $destination = explode(',', $destination);
        }

        // ✅ Ensure district is an array
        if (!is_array($district)) {
            $district = explode(',', $district);
        }

        // Initialize existing prices and checked items as empty collections
        $existingPrices = collect();
        $checkedItems = collect(); // ✅ Initialize here

        // Only check existing prices if calculator ID exists (update case)
        if ($request->has('pricing_calculator_id')) {
            $calculatorId = $request->pricing_calculator_id;
            $existingPrices = PriceCalculatorList::where('pricing_calculator_id', $calculatorId)
                ->where('type', 'activity')
                ->get();

            // Create a collection of checked items
            $checkedItems = $existingPrices->keyBy(function ($item) {
                return $item->type_id . '|' . $item->price_title;
            });
        }

        $activities = ActivityP::whereIn('destination_id', $destination)
            ->whereIn('district_id', $district)
            ->whereIn('id', $activitydetails)
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->select('id', 'title_price', 'title')
            ->get()
            ->map(function ($activity) use ($existingPrices, $checkedItems) {
                $priceData = json_decode($activity->title_price);
                $formattedPrices = [];

                foreach ($priceData as $price) {
                    $key = $activity->id . '|' . $price->title;
                    $existingPrice = $existingPrices->where('type_id', $activity->id)
                        ->where('price_title', $price->title)
                        ->first();

                    $formattedPrices[] = [
                        'activity_id' => $activity->id,
                        'title' => $activity->title,
                        'price_title' => $price->title,
                        'price' => $existingPrice ? $existingPrice->price : $price->price,
                        'is_checked' => $checkedItems->has($key) // ✅ Now $checkedItems is always defined
                    ];
                }

                return $formattedPrices;
            })
            ->toArray();

        return response()->json([
            'activity_details' => $activities
        ]);
    }

    public function cabs_details(Request $request)
    {
        $destination = $request->destination;
        $district = $request->district;
        $travelmodes = $request->travelmodes;
        $cabdetails = $request->cabdetails;

        // ✅ Ensure destination is an array
        if (!is_array($destination)) {
            $destination = explode(',', $destination);
        }

        // ✅ Ensure district is an array
        if (!is_array($district)) {
            $district = explode(',', $district);
        }

        // Convert to arrays if they are strings
        if (is_string($travelmodes)) {
            $travelmodes = explode(',', $travelmodes);
        }

        if (is_string($cabdetails)) {
            $cabdetails = explode(',', $cabdetails);
        }

        // Validate required parameters
        if (empty($destination) || empty($district) || empty($travelmodes) || empty($cabdetails)) {
            return response()->json([
                'activity_details' => []
            ]);
        }

        // Initialize existing prices and checked items
        $existingPrices = collect();
        $checkedItems = collect();

        // Only check existing prices if calculator ID exists (update case)
        if ($request->has('pricing_calculator_id')) {
            $calculatorId = $request->pricing_calculator_id;
            $existingPrices = PriceCalculatorList::where('pricing_calculator_id', $calculatorId)
                ->where('type', 'cabs')
                ->get();

            // Create a collection of checked items
            $checkedItems = $existingPrices->keyBy(function ($item) {
                return $item->type_id . '|' . $item->price_title;
            });
        }

        try {
            $cabs = Cab::whereIn('destination_id', $destination)
                ->whereIn('district_id', $district)
                ->whereIn('travel_mode', $travelmodes)
                ->whereIn('id', $cabdetails)
                ->where('status', '1')
                ->where('is_deleted', '0')
                ->select('id', 'title_price', 'title')
                ->get();

            $activitys = $cabs->map(function ($cab) use ($existingPrices, $checkedItems) {
                $priceData = json_decode($cab->title_price);
                if (json_last_error() !== JSON_ERROR_NONE || !is_array($priceData)) {
                    \Log::warning("Invalid JSON for cab ID: {$cab->id}");
                    return [];
                }

                $formattedPrices = [];
                foreach ($priceData as $price) {
                    $key = $cab->id . '|' . $price->title;
                    $existingPrice = $existingPrices->where('type_id', $cab->id)
                        ->where('price_title', $price->title)
                        ->first();

                    $formattedPrices[] = [
                        'cab_id' => $cab->id,
                        'title' => $cab->title,
                        'price_title' => $price->title,
                        'price' => $existingPrice ? $existingPrice->price : $price->price,
                        'is_checked' => $checkedItems->has($key) // Add this field
                    ];
                }

                return $formattedPrices;
            })
                ->filter(function ($item) {
                    return !empty($item);
                })
                ->values()
                ->toArray();

            return response()->json([
                'activity_details' => $activitys
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in cabs_details: ' . $e->getMessage());
            return response()->json([
                'activity_details' => [],
                'error' => 'Server error occurred'
            ], 500);
        }
    }
}
