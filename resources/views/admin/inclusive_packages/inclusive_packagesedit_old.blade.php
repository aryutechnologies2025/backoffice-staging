@extends('layouts.app')
@Section('content')
<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-12">
        <h3 class="fw-bold"><span class="vr"></span>&nbsp;{{$title}}</h3>
    </div>

    <!-- FORM -->
    <form id="form_valid" action="{{ route('admin.inclusive_package_update', $package_details->id) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <!-- 1.INFORMATION -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5  rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">1.Information</h4>
                    <div class="mb-3">
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Title <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Title" id="title" name="title" class="form-control py-3 rounded-3 shadow-sm" required value="{{$package_details->title}}">
                            </div>
                            <div class="mt-5">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="form-label form-label-top form-label-auto fw-bold mb-4">Program Description <span class="text-danger">*</span></label>
                                        <textarea id="program_description" class="container__textarea p-5 textarea-feild" name="program_description" required>{{$package_details->program_description}}</textarea>
                                    </div>
                                </div>
                            </div>
                            {{--<div class="col">
                                <label class="fw-bold  mb-4">Property Type <span class="text-danger">*</span></label>
                                <select id="property_type" name="property_type" class="form-select py-3 rounded-3 shadow-sm" required>
                                    <option value="">Select Type</option>
                                    <option value="villa" {{ $package_details->property_type == 'villa' ? 'selected' : '' }}>Villa</option>
                            <option value="appartment" {{ $package_details->property_type == 'appartment' ? 'selected' : '' }}>Appartment</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="fw-bold  mb-4"> Category <span class="text-danger">*</span></label>
                            <select id="prop_cat" name="prop_cat" class="form-select py-3 rounded-3 shadow-sm" onchange="updateTypeOptions()" required>
                                <option value="">Select Category</option>
                                <option value="events" {{ $package_details->category == 'events' ? 'selected' : '' }}>Events</option>
                                <option value="packages" {{ $package_details->category == 'packages' ? 'selected' : '' }}>Packages</option>
                            </select>
                        </div> --}}
                    </div>
                </div>
                <div class="row g-2 mb-5">
                    <div class="col">
                        <label class="fw-bold  mb-4"> Category <span class="text-danger">*</span></label>
                        <select id="prop_cat" name="prop_cat" class="form-select py-3 rounded-3 shadow-sm"  required>
                            <option value="">Select Category</option>
                            <option value="popular_program" {{ $package_details->category == 'popular_program' ? 'selected' : '' }}>Popular Program</option>
                            <option value="upcoming_program" {{ $package_details->category == 'upcoming_program' ? 'selected' : '' }}>Upcoming Program</option>
                            <option value="featured" {{ $package_details->category == 'featured' ? 'selected' : '' }}>Featured</option>
                        </select>
                    </div>
                </div>
                {{--<div class="row g-2 mb-5">
                    <div id="type-container" class="col" style="display: none;">
                        <label class="fw-bold mb-4">Types <span class="text-danger">*</span></label>
                        <select id="type" name="type" class="form-select py-3 rounded-3 shadow-sm" required>
                            <!-- Options will be populated here -->
                        </select>
                    </div>
                </div>--}}
                <div class="row g-2 mb-4">
                    <div class="col">
                        <label class="fw-bold mb-4">Destination <span class="text-danger">*</span></label>
                        <select id="cities_name" name="cities_name" class="form-select py-3 rounded-3 shadow-sm" required>
                            <option value="">Select Destination</option>
                            @foreach($cities_dts as $id => $name)
                            <option value="{{ $id }}" {{ $id == $selectedCityId ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="type-container" class="col">
                        <label class="fw-bold mb-4">Destination Category</label>
                        <select id="destination_cat" name="destination_cat" class="form-select py-3 rounded-3 shadow-sm">
                            <option value="">Select Destination Category</option>
                            <!-- Options will be populated here by JavaScript -->
                        </select>
                    </div>
                </div>
                <div class="row g-2 mb-4">
                    <div class="col">
                        <label class="fw-bold mb-4">Themes</label>
                        <select id="themes_name" name="themes_name" class="form-select py-3 rounded-3 shadow-sm">
                            <option value="">Select Theme</option>
                            @foreach($themes as $id => $name)
                            <option value="{{ $id }}" {{ $id == $selectedthemeId ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="type-container" class="col">
                        <label class="fw-bold mb-4">Theme Category</label>
                        <select id="theme_cat" name="theme_cat" class="form-select py-3 rounded-3 shadow-sm">
                            <option value="">Select Category</option>
                            <!-- Options will be populated here by JavaScript -->
                        </select>
                    </div>
                </div>
                <div id="photo-upload-container" class="row">
                    @php
                    // Decode JSON if needed
                    $images = json_decode($package_details->events_package_images, true);
                    $imageCount = is_array($images) ? count($images) : 0;
                    @endphp
                    @if(is_array($images))
                    @foreach($images as $key => $image)
                    <div class="col-lg-2 photo-upload-field">
                        <div class="form-input">
                            <label for="file-ip-{{ $key }}" class="px-4 py-3 text-center">
                                <img class="text-center mt-3" id="file-ip-{{ $key }}-preview" src="{{ asset($image) }}" alt="Image Preview">
                                <p class="text-center fw-light mt-3">Edit Pic</p>
                            </label>
                            <input type="file" name="img_{{ $key }}" id="file-ip-{{ $key }}" data-number="{{ $key }}" accept="image/*">
                        </div>
                    </div>
                    @endforeach
                    @else
                    <p>No images available.</p>
                    @endif
                </div>


                <button id="add-photo-btn" type="button" class="btn btn-primary mt-3">Add More Photos</button>

            </div>
        </div>
</div>

<!-- 2.LOCATION -->
<div class="row mb-5">
    <div class="col">
        <div class="form-body px-5  rounded-4 m-auto ">
            <h4 class="fw-bold mb-5">2.Location</h4>
            <div class="mb-3">
                <div class="row g-2 mb-4 d-flex justify-content-around">
                    <div class="col">
                        <label class="fw-bold mb-4 ">State <span class="text-danger">*</span></label>
                        <input type="text" placeholder="State" id="state" name="state" class="form-control py-3 rounded-3 shadow-sm" required value="{{$package_details->state}}">
                    </div>
                    <div class="col">
                        <label class="fw-bold  mb-4">City <span class="text-danger">*</span></label>
                        <input type="text" placeholder="City" id="city" name="city" class="form-control py-3 rounded-3 shadow-sm" required value="{{$package_details->city}}">
                    </div>
                </div>
                <div class="row mb-4">
                    <label class="fw-bold mb-4 ">Address Details <span class="text-danger">*</span></label>
                    <div class="col-lg-12  ">
                        <input type="text" id="address" name="address" class="form-control py-3 rounded-3 shadow-sm" placeholder="Address" required value="{{$package_details->address}}">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-12">
                        <label class="fw-bold mb-4 ">Country <span class="text-danger">*</span></label>
                        <input type="text" id="country" name="country" class="form-control py-3 rounded-3 shadow-sm" placeholder="Country" required value="{{$package_details->country}}">
                    </div>
                    {{--<div class="col mt-3">
                                <label class="fw-bold  mb-4">Geographical Features <span class="text-danger">*</span></label>
                                <select id="geo_feature" name="geo_feature" class="form-select py-3 rounded-3 shadow-sm" required>
                                    <option value="">Select Geo</option>
                                    <option value="western_ghats" {{ $package_details->geo_feature == 'western_ghats' ? 'selected' : '' }}>Western Ghats</option>
                    <option value="himalayas" {{ $package_details->geo_feature == 'himalayas' ? 'selected' : '' }}>Himalayas</option>
                    <option value="chola_dynasty" {{ $package_details->geo_feature == 'chola_dynasty' ? 'selected' : '' }}>Chola Dynasty</option>
                    </select>
                </div> --}}


                <div class="col mt-3">
                    <label class="fw-bold  mb-4">Geographical Features <span class="text-danger">*</span></label>
                    <select id="geo_feature" name="geo_feature" class="form-select py-3 rounded-3 shadow-sm" required>
                        <option value="">Select Geo</option>
                        @foreach($geo_feature_dts as $id => $name)
                        <option value="{{ $id }}" {{ $id == $selectedgeo_featureId ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!-- 3.TOUR PLANNING  -->
<div class="row mb-5">
    <div class="col">
        <div class="form-body px-5 rounded-4 m-auto">
            <h4 class="fw-bold mb-5">3. Tour Planning <span class="text-danger">*</span></h4>
            <div id="plan-container">
                @php
                // Decode JSON if needed
                $tourPlanning = json_decode($package_details->tour_planning, true);
                @endphp
                @foreach($tourPlanning['plan_title'] as $index => $title)
                <div class="plan-item mb-3">
                    <div class="row g-2 mt-5 d-flex justify-content-around">
                        <div class="col-lg-11">
                            <input type="text" name="plan_title[]" class="form-control py-3 rounded-3 shadow-sm" placeholder="Plan Title" required value="{{ $title }}">
                        </div>
                        <div class="col-lg-1 mt-3 text-end">
                            <a href="#" class="table-link danger remove-plan">
                                <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="row">
                            <div class="col-lg-11">
                                <label class="form-label form-label-top form-label-auto fw-bold mb-4">Plan Description <span class="text-danger">*</span></label>
                                <textarea class="container__textarea p-5 textarea-feild" name="plan_description[]" required>{{ $tourPlanning['plan_description'][$index] }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-end p-5">
                <button type="button" id="add-plan-btn" class="btn-add rounded border-0 px-5 py-3 text-end text-white">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add
                </button>
            </div>
        </div>
    </div>
</div>




<!-- 4.TOUR DATE & TIME -->
<div class="row mb-5">
    <div class="col">
        <div class="form-body px-5 rounded-4 m-auto ">
            <h4 class="fw-bold mb-5">4.Tour date & Time</h4>
            <div class="mb-3">
                <div class="row mb-4">
                    <div class="row g-2 mb-4">
                        <div class="col">
                            <label class="fw-bold mb-4 ">Start date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control py-3 rounded-3 shadow-sm " name="start_date" id="start_date" value="{{$package_details->start_date}}" required>
                        </div>
                        <div class="col">
                            <label class="fw-bold mb-4 ">Return Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control py-3 rounded-3 shadow-sm " name="return_date" value="{{$package_details->return_date}}" id="return_date" required>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col">
                        <label class="fw-bold  mb-4">Tour duration </label>
                        <input type="text" class="form-control py-3 rounded-3 shadow-sm " id="total_days" name="total_days" value="{{$package_details->total_days}}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- 5.PRICING -->
<div class="row mb-5">
    <div class="col">
        <div class="form-body px-5  rounded-4 m-auto ">
            <h4 class="fw-bold mb-5">5. Pricing</h4>
            <div class="mb-3">
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4 mt-3">Total Room <span class="text-danger">*</span></label>
                        <input type="text" id="total_room" name="total_room" class="form-control py-3 rounded-3 shadow-sm" placeholder="Total Room" required value="{{$package_details->total_room}}">
                    </div>
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4 mt-3">BathRoom <span class="text-danger">*</span></label>
                        <input type="text" id="bath_room" name="bath_room" class="form-control py-3 rounded-3 shadow-sm" placeholder="Total Bath" required value="{{$package_details->bath_room}}">
                    </div>
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4 mt-3">BedRoom <span class="text-danger">*</span></label>
                        <input type="text" id="bed_room" name="bed_room" class="form-control py-3 rounded-3 shadow-sm" placeholder="Total Bed" required value="{{$package_details->bed_room}}">
                    </div>
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4 mt-3">Hall <span class="text-danger">*</span></label>
                        <input type="text" id="hall" name="hall" class="form-control py-3 rounded-3 shadow-sm" placeholder="Hall" required value="{{$package_details->hall}}">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4 ">Member Capacity <span class="text-danger">*</span></label>
                        <input type="text" id="member_capacity" name="member_capacity" class="form-control py-3 rounded-3 shadow-sm" placeholder="Member Capacity" required value="{{$package_details->member_capacity}}">
                    </div>
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4 mt-3">Coupon Code <span class="text-danger">*</span></label>
                        <input type="text" id="coupon_code" name="coupon_code" class="form-control py-3 rounded-3 shadow-sm" value="{{$package_details->coupon_code}}" placeholder="Code" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4 ">Price <span class="text-danger">*</span></label>
                        <input type="text" id="price" name="price" class="form-control py-3 rounded-3 shadow-sm" placeholder="Per day" value="{{$package_details->price}}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4 ">Actual Price <span class="text-danger">*</span></label>
                        <input type="text" id="actual_price" name="actual_price" class="form-control py-3 rounded-3 shadow-sm" placeholder="Actual Price" value="{{$package_details->actual_price}}" required>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 6.Rule And Regulations -->
<div class="row mb-5">
    <div class="col">
        <div class="form-body px-5 rounded-4 m-auto">
            <h4 class="fw-bold mb-5">6. Rule And Regulations <span class="text-danger">*</span></h4>
            <div class="mb-3">
                <div id="camp-rule-container">
                    @php
                    $campRuleArray = json_decode($package_details->camp_rule, true);
                    if (is_array($campRuleArray)) {
                    $package_details->camp_rule = $campRuleArray;
                    } else {
                    // Handle the error or initialize it to an empty array if it's not a valid JSON string
                    $package_details->camp_rule = [];
                    }
                    @endphp
                    @if(is_array($package_details->camp_rule))
                    @foreach($package_details->camp_rule as $rule)
                    <div class="row g-2 mb-4 camp-rule-field">
                        <div class="col">
                            <input type="text" name="camp_rule[]" class="form-control py-3 rounded-3 shadow-sm" placeholder="Rule And Regulations" value="{{ $rule }}" required>
                        </div>
                        <div class="col-lg-1 mt-5 text-end">
                            <a class="table-link danger remove-plan" onclick="removeField(this)">
                                <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <p>No Rule And Regulations available.</p>
                    @endif
                </div>
                <div class="text-end">
                    <button type="button" class="btn-add rounded border-0 px-5 py-3 text-white" onclick="addCampRuleField()">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>





<!-- 7.Important info -->
<div class="row mb-5">
    <div class="col">
        <div class="form-body px-5  rounded-4 m-auto ">
            <h4 class="fw-bold mb-5">7.Important info <span class="text-danger">*</span></h4>
            <div class="mb-3">
                <div class="row g-2 mb-4">
                    <div class="col">
                        <textarea id="important_info" class="container__textarea p-5 textarea-feild" name="important_info" value="" required>{{$package_details->important_info}}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 8.AMENITIES -->
<div class="row mb-5">
    <div class="col">
        <div class="form-body px-5 rounded-4 m-auto">
            <h4 class="fw-bold mb-5">8. Amenities </h4>
            <div class="row mb-4">
                @foreach($amenities_dts->chunk(4) as $chunk)
                <div class="row mb-3">
                    @foreach($chunk as $amenity)
                    <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="amenity-{{ $amenity->id }}" name="amenity_services[]" value="{{ $amenity->id }}" @if(in_array((string) $amenity->id, $selectedAmenities)) checked @endif>
                            <label class="form-check-label" for="amenity-{{ $amenity->id }}">{{ $amenity->amenity_name }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>




<!-- 9.FOOD & BEVERAGES -->
<div class="row mb-5">
    <div class="col">
        <div class="form-body px-5 rounded-4 m-auto">
            <h4 class="fw-bold mb-5">9. Food and Beverages</h4>
            <div class="row mb-4">
                @foreach($foodBeverages_dts->chunk(6) as $chunk)
                <div class="row mb-3">
                    @foreach($chunk as $item)
                    <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="food-beverage-{{ $item->id }}" name="food_beverages[]" value="{{ $item->id }}" @if(in_array((string) $item->id, $selectedfood_beverages)) checked @endif>

                            <label class="form-check-label" for="food-beverage-{{ $item->id }}">{{ $item->food_beverage }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!--10. ACTIVITIES -->
<div class="row mb-5">
    <div class="col">
        <div class="form-body px-5  rounded-4 m-auto ">
            <h4 class="fw-bold mb-5">10.Activities</h4>
            <div class="row mb-4">
                @foreach($activities_dts->chunk(6) as $chunk)
                <div class="row mb-3">
                    @foreach($chunk as $item)
                    <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="activities-{{ $item->id }}" name="activities[]" value="{{ $item->id }}" @if(in_array((string) $item->id, $selectedactivities)) checked @endif>
                            <label class="form-check-label" for="activities-{{ $item->id }}">{{ $item->activities }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


<!-- 11.SAFETY FEATURES  -->
<div class="row mb-5">
    <div class="col">
        <div class="form-body px-5  rounded-4 m-auto ">
            <h4 class="fw-bold mb-5">11.Safety Features</h4>
            <div class="row mb-4">
                @foreach($safety_features_dts->chunk(6) as $chunk)
                <div class="row mb-3">
                    @foreach($chunk as $item)
                    <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="safety_features-{{ $item->id }}" name="safety_features[]" value="{{ $item->id }}" @if(in_array((string) $item->id, $selectedsafety_features)) checked @endif>
                            <label class="form-check-label" for="safety_features-{{ $item->id }}">{{ $item->safety_features }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
            <div class="row g-2">
                <div class="col">
                    <label class="fw-bold ">IS Featured</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input check_bx" name="is_featured" type="checkbox" id="is_featured" {{ $package_details->is_featured === 'yes' ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
            <div class="row g-2 mt-3">
                <div class="col">
                    <label class="fw-bold ">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input check_bx" name="status" type="checkbox" id="status" {{ $package_details->status ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 text-end mt-5">
                <a href="{{ route('admin.inclusive_package_list') }}">
                    <button type="button" class="cancel-btn"> Cancel </button>
                </a>
                <button class="submit-btn sbmtBtn ms-4"> Submit </button>
            </div>
        </div>
    </div>

</div>

</form>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // updateTypeOptions(); // Call function to prepopulate the "Types" dropdown on page load
    });

    function updateTypeOptions() {
        // Get the selected value from the first dropdown
        var propCatValue = document.getElementById('prop_cat').value;

        // Get the container and the second dropdown
        var typeContainer = document.getElementById('type-container');
        var typeSelect = document.getElementById('type');

        // Define options based on the selected value
        var options = [];
        if (propCatValue === 'events') {
            options = [{
                    value: '',
                    text: 'Select Types'
                },
                {
                    value: 'upcoming_events',
                    text: 'Upcoming Events'
                },
                {
                    value: 'popular_events',
                    text: 'Popular Events'
                }
            ];
        } else if (propCatValue === 'packages') {
            options = [{
                    value: '',
                    text: 'Select Types'
                },
                {
                    value: 'upcoming_packages',
                    text: 'Upcoming Packages'
                },
                {
                    value: 'popular_packages',
                    text: 'Popular Packages'
                }
            ];
        }

        // Populate the options in the second dropdown
        typeSelect.innerHTML = ''; // Clear previous options
        options.forEach(function(option) {
            var opt = document.createElement('option');
            opt.value = option.value;
            opt.text = option.text;
            typeSelect.add(opt);
        });

        // Set the selected type if exists
        var selectedType = "{{ old('type', $package_details->type) }}";
        if (selectedType) {
            typeSelect.value = selectedType;
        }

        // Show or hide the second dropdown based on selection
        typeContainer.style.display = propCatValue ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        let planCount = @json(count($tourPlanning['plan_title']));

        // Function to clone the existing plan item
        function createPlanFields() {
            planCount++; // Increment plan count
            const container = document.getElementById('plan-container');
            const template = container.querySelector('.plan-item');

            // Clone the template
            const newPlan = template.cloneNode(true);

            // Clear values in the cloned fields
            newPlan.querySelectorAll('input, textarea').forEach((field) => {
                field.value = ''; // Clear values
            });

            // Append the new plan item
            container.appendChild(newPlan);
        }

        // Event listener for the "Add" button
        document.getElementById('add-plan-btn').addEventListener('click', function() {
            createPlanFields();
        });

        // Event delegation to handle removal of plan items
        document.getElementById('plan-container').addEventListener('click', function(event) {
            if (event.target.closest('.remove-plan')) {
                event.preventDefault();
                const planItem = event.target.closest('.plan-item');
                planItem.remove();
            }
        });
    });


    $(document).ready(function() {
        let photoCount = @json($imageCount); // Use the correct count variable

        // Function to generate new photo upload field HTML
        function createPhotoUploadField(count) {
            return `
            <div class="col-lg-2 photo-upload-field">
                <div class="form-input">
                    <label for="file-ip-${count}" class="px-4 py-3 text-center">
                        <img class="text-center mt-3" id="file-ip-${count}-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg" alt="Image Preview">
                        <p class="text-center fw-light mt-3">Add Pic</p>
                    </label>
                    <input type="file" name="img_${count}" id="file-ip-${count}" data-number="${count}" accept="image/*">
                </div>
            </div>
        `;
        }

        // Event listener for the "Add More Photos" button
        $('#add-photo-btn').on('click', function() {
            photoCount++;
            const newFieldHtml = createPhotoUploadField(photoCount);
            $('#photo-upload-container').append(newFieldHtml);
        });

        // Function to show preview of selected image
        function showPreview(event) {
            var file = event.target.files[0];
            var number = $(event.target).data('number'); // Use data attribute to get the number
            var previewId = "#file-ip-" + number + "-preview";

            var reader = new FileReader();

            reader.onload = function(e) {
                $(previewId).attr('src', e.target.result);
            };

            if (file) {
                if (file.size <= 2 * 1024 * 1024) { // 2 MB limit
                    if (file.type === 'image/png' || file.type === 'image/jpeg') {
                        reader.readAsDataURL(file);
                    } else {
                        alert('Please upload a valid PNG or JPEG image.');
                    }
                } else {
                    alert('File size exceeds 2 MB limit.');
                }
            }
        }

        // Delegate event binding for dynamically added file inputs
        $('#photo-upload-container').on('change', 'input[type="file"]', showPreview);
    });



    function addCampRuleField() {
        // Find the container where new fields will be added
        var container = document.getElementById('camp-rule-container');

        // Create a new div for the new field
        var newField = document.createElement('div');
        newField.className = 'row g-2 mb-4 camp-rule-field';
        newField.innerHTML = `
        <div class="col">
            <input type="text" name="camp_rule[]" class="form-control py-3 rounded-3 shadow-sm" placeholder="Rule And Regulations" required>
        </div>
        <div class="col-lg-1 mt-5 text-end">
            <a class="table-link danger remove-plan" onclick="removeField(this)">
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                </span>
            </a>
        </div>`;

        // Append the new field to the container
        container.appendChild(newField);
    }

    function removeField(element) {
        // Find the parent element (field container) and remove it
        var field = element.closest('.camp-rule-field');
        if (field) {
            field.remove();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const themeSelect = document.getElementById('themes_name');
        const categorySelect = document.getElementById('theme_cat');

        function populateCategories(themeId) {
            fetch(`/all-inclusive-package/theme-categories/${themeId}`)
                .then(response => response.json())
                .then(data => {
                    categorySelect.innerHTML = '<option value="">Select Category</option>'; // Clear previous options
                    Object.keys(data).forEach(id => {
                        const option = document.createElement('option');
                        option.value = id;
                        option.textContent = data[id];
                        categorySelect.appendChild(option);
                    });

                    // Pre-select the category
                    @if($selectedCategoryId)
                    categorySelect.value = '{{ $selectedCategoryId }}';
                    @endif
                })
                .catch(error => console.error('Error fetching theme categories:', error));
        }

        // Initial population of categories if a theme is pre-selected
        if (themeSelect.value) {
            populateCategories(themeSelect.value);
        }

        // Add event listener for theme change
        themeSelect.addEventListener('change', function() {
            const themeId = this.value;
            if (themeId) {
                populateCategories(themeId);
            } else {
                categorySelect.innerHTML = '<option value="">Select Category</option>'; // Clear categories if no theme selected
            }
        });
    });

    $(document).ready(function() {
        const $citiesSelect = $('#cities_name');
        const $destinationCatSelect = $('#destination_cat');
        const selectedDestinationCat = @json($selecteddesCategoryId); // Pass the initially selected destination category ID

        function populateDestinationCategories(cityId) {
            $destinationCatSelect.empty().append('<option value="">Select Destination Category</option>');

            if (cityId) {
                $.ajax({
                    url: '{{ route("admin.destination_categories") }}', // Use the route defined in web.php
                    type: 'GET',
                    data: {
                        city_id: cityId
                    },
                    success: function(data) {
                        $.each(data, function(id, name) {
                            $destinationCatSelect.append(new Option(name, id));
                        });

                        // Set the previously selected destination category
                        if (selectedDestinationCat) {
                            $destinationCatSelect.val(selectedDestinationCat);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching categories:', error);
                    }
                });
            }
        }

        // Populate destination categories on city change
        $citiesSelect.on('change', function() {
            const cityId = $(this).val();
            populateDestinationCategories(cityId);
        });

        // Populate destination categories on page load if a city is selected
        const initialCityId = $citiesSelect.val();
        if (initialCityId) {
            populateDestinationCategories(initialCityId);
        }
    });
</script>
@endsection