 public function edit_stay_details(Request $request)
    {
        $pricingval = $request->pricingval;
        $staydetails = $request->staydetails;
        $customerid = $request->customerid;

        $customar_priceid = CustomerPricingCalculator::where('pricing_calculator_id', $pricingval)->where('customer_package_id', $customerid)->first();

        // dd($customar_priceid);
        // Initialize exsting prices as empty collection
        $existingPrices = collect();

        $existingPrices = CustomerPriceCalculatorList::where('customer_pricing_id', $customar_priceid->id)
            ->where('type', 'stay')
            ->where('is_deleted', '0')
            ->get()
            ->keyBy(function ($item) {
                return $item->type_id . '|' . $item->price_title;
            });

        $pricing_stay = CustomerPriceCalculatorList::where('type', 'stay')->where('customer_pricing_id', $customar_priceid->id)->distinct('type_id')->where('is_deleted', '0')->pluck('type_id')->toArray();

        $pricing_stay_id = CustomerPriceCalculatorList::where('type', 'stay')->where('customer_pricing_id', $customar_priceid->id)->distinct('id')->where('is_deleted', '0')->pluck('id')->toArray();

        // dd($pricing_stay_id);

        $stays = CustomerPriceCalculatorList::where('type', 'stay')
            ->whereIN('id', $pricing_stay_id)
            ->where('is_deleted', '0')
            ->select('id', 'type_id', 'price_title', 'title', 'price')
            ->get()
            ->map(function ($stay) use ($existingPrices) {
                // $priceData = json_decode($stay->title_price);
                // $formattedPrices = [];

                // foreach ($priceData as $price) {
                //     $key = $stay->id . '|' . $price->title;
                //     $existingPrice = $existingPrices->get($key);

                $formattedPrices[] = [
                    'existingPricesid' => $stay->id,
                    'stay_id' => $stay->id,
                    'title' => $stay->title,
                    'price_title' => $stay->price_title,
                    'price' => $stay->price
                ];
                // }

                return $formattedPrices;
            })
            ->toArray();

        return response()->json([
            'stays_details' => $stays
        ]);
    }
    public function edit_activity_details(Request $request)
    {
        $pricingval = $request->pricingval;
        $staydetails = $request->staydetails;
        $customerid = $request->customerid;

        $customar_priceid = CustomerPricingCalculator::where('pricing_calculator_id', $pricingval)->where('customer_package_id', $customerid)->first();

        // dd($customar_priceid);
        // Initialize exsting prices as empty collection
        $existingPrices = collect();

        $existingPrices = CustomerPriceCalculatorList::where('customer_pricing_id', $customar_priceid->id)
            ->where('type', 'activity')
            ->where('is_deleted', '0')
            ->get()
            ->keyBy(function ($item) {
                return $item->type_id . '|' . $item->price_title;
            });

        $pricing_stay = CustomerPriceCalculatorList::where('type', 'activity')->where('customer_pricing_id', $customar_priceid->id)->distinct('type_id')->where('is_deleted', '0')->pluck('type_id')->toArray();

        $pricing_stay_id = CustomerPriceCalculatorList::where('type', 'activity')->where('customer_pricing_id', $customar_priceid->id)->distinct('id')->where('is_deleted', '0')->pluck('id')->toArray();

        // dd($pricing_stay_id);

        $stays = CustomerPriceCalculatorList::where('type', 'activity')
            ->whereIN('id', $pricing_stay_id)
            ->where('is_deleted', '0')
            ->select('id', 'type_id', 'price_title', 'title', 'price')
            ->get()
            ->map(function ($stay) use ($existingPrices) {
                // $priceData = json_decode($stay->title_price);
                // $formattedPrices = [];

                // foreach ($priceData as $price) {
                //     $key = $stay->id . '|' . $price->title;
                //     $existingPrice = $existingPrices->get($key);

                $formattedPrices[] = [
                    'existingPricesid' => $stay->id,
                    'activity_id' => $stay->id,
                    'title' => $stay->title,
                    'price_title' => $stay->price_title,
                    'price' => $stay->price
                ];
                // }

                return $formattedPrices;
            })
            ->toArray();

        return response()->json([
            'activity_details' => $stays
        ]);
    }

    public function edit_cabs_details(Request $request)
    {
        // dd($request->all());
        $pricingval = $request->pricingval;
        $travelmodes = $request->travelmodes;
        $cabdetails = $request->cabdetails;
        $customerid = $request->customerid;

        $customar_priceid = CustomerPricingCalculator::where('pricing_calculator_id', $pricingval)->where('customer_package_id', $customerid)->first();

        $existingPrices = collect();

        // Only check existing prices if calculator ID exists (update case)

        $existingPrices = CustomerPriceCalculatorList::where('customer_pricing_id', $customar_priceid->id)
            ->where('type', 'cabs')
            ->get()
            ->keyBy(function ($item) {
                return $item->type_id . '|' . $item->price_title;
            });

        $pricing_stay = CustomerPriceCalculatorList::where('type', 'cabs')->where('customer_pricing_id', $customar_priceid->id)->distinct('type_id')->where('is_deleted', '0')->pluck('type_id')->toArray();

        $pricing_stay_id = CustomerPriceCalculatorList::where('type', 'cabs')->where('customer_pricing_id', $customar_priceid->id)->distinct('id')->where('is_deleted', '0')->pluck('id')->toArray();


        $pricing_cab = CustomerPriceCalculatorList::where('type', 'cabs')->where('customer_pricing_id', $customar_priceid->id)->distinct('type_id')->pluck('type_id')->toArray();

        $activitys = CustomerPriceCalculatorList::where('type', 'cabs')
            ->whereIN('id', $pricing_stay_id)
            ->where('is_deleted', '0')
            ->select('id', 'type_id', 'price_title', 'title', 'price')
            ->get()
            ->map(function ($stay) use ($existingPrices) {
                $formattedPrices[] = [
                    'existingPricesid' => $stay->id,
                    'stay_id' => $stay->id,
                    'title' => $stay->title,
                    'price_title' => $stay->price_title,
                    'price' => $stay->price
                ];
                return $formattedPrices;
            })
            ->toArray();

        return response()->json([
            'activity_details' => $activitys
        ]);
    }



     public function edit_cabs_details(Request $request)
    {
        // dd($request->all());
        $pricingval = $request->pricingval;
        $travelmodes = $request->travelmodes;
        $cabdetails = $request->cabdetails;

        $existingPrices = collect();

        // Only check existing prices if calculator ID exists (update case)
        if ($request->has('pricingval')) {
            $calculatorId = $request->pricingval;
            $existingPrices = PriceCalculatorList::where('pricing_calculator_id', $calculatorId)
                ->where('type', 'cabs')
                ->get()
                ->keyBy(function ($item) {
                    return $item->type_id . '|' . $item->price_title;
                });
        }

        $pricing_cab = PriceCalculatorList::where('type', 'cabs')->where('pricing_calculator_id', $pricingval)->distinct('type_id')->pluck('type_id')->toArray();

        $activitys = Cab::whereIn('id', $pricing_cab)
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
