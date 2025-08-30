  <div class="test">
                        <h4>Pricing Calculator</h4>
                        <div class="row gap-2">
                            <!-- Theme and Destination -->
                            <div class="col-md-4">
                                <label class="mb-2">Price Calculator List</label>
                                <select id="pricing_calculator" name="pricing_calculator"
                                    class="form-select py-2 rounded-3 shadow-sm">
                                    <option value="" disabled selected>Select Location</option>
                                    <!-- Districts will be populated dynamically -->
                                </select>
                            </div>

                        </div>
                        <!-- Stays Section -->
                        <div id="stays-section" class="row d-flex mt-3">
                            <div class="add_form col-md-4">
                                <label class="mb-2">Stay Details</label>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                        type="button" id="stayDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <span id="stayDropdownText">Select stay</span>
                                    </button>
                                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="stayDropdown"
                                        style="max-height: 200px; overflow-y: auto;">
                                        <!-- Stays will be populated here via JavaScript -->
                                    </ul>
                                </div>
                                <input type="hidden" name="stay_id" id="stayHiddenInput">
                            </div>
                            <div id="stays-details-container" class="mt-3"></div>
                        </div>

                        <!-- Activities Section -->
                        <div id="activities-section" class="row d-flex mt-3">
                            <div class="add_form col-md-4">
                                <label class="mb-2">Activity</label>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                        type="button" id="activityDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <span id="activityDropdownText">Select activity</span>
                                    </button>
                                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="activityDropdown"
                                        style="max-height: 200px; overflow-y: auto;">
                                        <!-- Activities will be populated here via JavaScript -->
                                    </ul>
                                </div>
                                <input type="hidden" name="activity_ids" id="activityHiddenInput">
                            </div>
                            <div id="activity-details-container" class="mt-3"></div>
                        </div>

                        <!-- Cabs Section -->
                        <div id="cabs-section" class="row d-flex mt-3">
                            <div class="add_form col-md-4">
                                <label class="mb-2">Travel Mode</label>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                        type="button" id="cabDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <span id="cabDropdownText">Select option</span>
                                    </button>
                                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="cabDropdown"
                                        style="max-height: 200px; overflow-y: auto;">
                                        <!-- Cabs will be populated here via JavaScript -->
                                    </ul>
                                </div>
                                <input type="hidden" name="cab_types" id="cabHiddenInput">
                            </div>

                            <!-- Cab details selection -->
                            <div id="cabs-details-container" class="mt-3" style="display: none;">
                                <div class="add_form col-md-4">
                                    <label class="mb-2">Travel Details</label>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                            type="button" id="cabDetailsDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span id="cabDetailsDropdownText">Select options</span>
                                        </button>
                                        <ul class="dropdown-menu w-100 p-2" aria-labelledby="cabDetailsDropdown"
                                            style="max-height: 200px; overflow-y: auto;">
                                            <!-- Will be populated dynamically -->
                                        </ul>
                                    </div>
                                    <input type="hidden" name="selected_cab_options" id="cabDetailsHiddenInput">
                                </div>
                            </div>

                            <!-- Cab price details display -->
                            <div id="cabsdetails-container" class="mt-3"></div>
                        </div>
                    </div>


                     if ($request->has('pricing_calculator') && !empty($request->pricing_calculator)) {

            $pricingcalculator_v = new CustomerPricingCalculator();

            $pricingcalculator_v->pricing_calculator_id = $request->pricing_calculator;
            // $pricingcalculator_v->title = $request->title;
            $pricingcalculator_v->package_id = $packageData['id'];
            $pricingcalculator_v->customer_package_id = $customer_package->id;
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
                        $pricingcalculator = new CustomerPriceCalculatorList();
                        $pricingcalculator->customer_pricing_id = $pricingcalculator_v->id;
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
                        $pricingcalculator = new CustomerPriceCalculatorList();
                        $pricingcalculator->customer_pricing_id = $pricingcalculator_v->id;
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
                        $pricingcalculator = new CustomerPriceCalculatorList();
                        $pricingcalculator->customer_pricing_id = $pricingcalculator_v->id;
                        $pricingcalculator->type = 'cabs';
                        $pricingcalculator->type_id = $v['cab_id'];
                        $pricingcalculator->title = $v['title'];
                        $pricingcalculator->price_title = $v['price_title'];
                        $pricingcalculator->price = $v['price'];
                        $pricingcalculator->save();
                    }
                }
            }
        }