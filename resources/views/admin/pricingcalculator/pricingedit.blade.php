@extends('layouts.app')
@section('content')
<style>
    a:hover {
        color: red;
    }

    a {
        color: rgb(37, 150, 190);
    }

    .add {
        color: blue;
    }

    .container-wrapper {
        padding-left: 30px;
        padding-right: 30px;
    }

    .mb-1 {
        margin-bottom: .5rem !important;
    }

    .btn-add {
        background-color: #2164c0 !important;
        border-radius: 15px !important;
        color: #FFF !important;
        padding: 10px 28px !important;
        font-size: 12px !important;
        border: none;
    }

    .form-input img {
        width: 80%;
    }

    .form-check-input {
        margin-top: 1% !important;
    }

    .plan-item .form-label {
        font-weight: bold;
    }

    .plan-item input {
        margin-bottom: 10px;
    }

    .btn-add {
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }

    #summernote3 {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 10px;
        background-color: #fff;
    }

    .form-switch {
        padding-left: 3.5em;
    }

    .form-input label {
        width: 150% !important;
        border: 0px !important;
    }

    .forms {
        margin-left: 38px;
    }

    @media (min-width: 768px) {
        .col-md-1 {
            flex: 0 0 auto;
            width: 10.33333333%;
        }
    }

    .custom-checkbox {
        width: 18px;
        height: 18px;
    }

    @media (max-width: 768px) {
        .custom-checkbox {
            width: 18px;
            height: 18px;
        }
    }

    .summary-card {
        /* border: 2px solid #e9ecef; */
        border-radius: 10px;
    }

    .subtotal-row {
        /* border-top: 1px dashed #dee2e6; */
        padding-top: 10px;
        margin-top: 10px;
    }

    .grand-total-row {
        border-top: 2px solid #28a745;
        padding-top: 15px;
        margin-top: 15px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }
</style>

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">Pricing Calculator</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a href="/pricingcalculator">Pricing</a> > <a class="add">Edit</a></b>
    </div>
</div>

<div class="row mb-5">
    <div class="col-lg-12">
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">

            <form class="" id="form_valid" action="{{ route('admin.pricing_update', $destination_details->id) }}" method="POST" autocomplete="off"
                enctype="multipart/form-data">
                @csrf

                <h4 class="fw-bold mb-4">Information</h4>


                <div class="mb-3 ">

                    <div class="add_form col-md-4">
                        <label class="mb-2">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control py-2 rounded-3 shadow-sm" id="title"
                            name="title" value="{{ $destination_details->title }}">
                    </div>
                    <div class="row gap-2 mt-3">
                        <!-- Theme and Destination -->
                        {{-- <div class="add_form col-md-4 ">
                                <label class="mb-2">Destination</label>
                                <select id="cities_name" name="cities_name" class="form-select py-2 rounded-3 shadow-sm" multiple
                                    required>
                                    <option value="" disabled selected>Select Destination</option>
                                    @foreach ($cities as $id => $name)
                                        <option value="{{ $id }}"
                        @if (old('cities_name', $destination_details->destination_id ?? '') == $id) selected @endif>
                        {{ $name }}
                        </option>
                        @endforeach
                        </select>
                    </div> --}}
                    <div class="add_form col-md-4">
                        {{-- <label class="mb-2 fw-semibold">Destination</label> --}}
                        {{-- <select id="cities_name" name="cities_name[]" class="form-select" multiple required>
                                    @foreach ($cities as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                        </select> --}}
                        <div class="add_form col-md-4">
                            <label class="mb-2 fw-semibold">Destination</label>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle w-100" type="button"
                                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    @php
                                    $selectedDestinations = explode(',', $destination_details->destination_id ?? '');

                                    $selectedDestinations = array_filter($selectedDestinations); // Remove empty values
                                    $selectedCount = count($selectedDestinations);
                                    @endphp
                                    {{ $selectedCount > 0 ? $selectedCount . ' destination(s) selected' : 'Select Destination' }}
                                </button>
                                <ul class="dropdown-menu p-2" aria-labelledby="dropdownMenuButton"
                                    style="max-height: 300px; overflow-y: auto;">
                                    @foreach ($cities as $id => $name)
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input city-checkbox" type="checkbox"
                                                value="{{ $id }}" id="city-{{ $id }}"
                                                name="cities_name[]"
                                                {{ in_array($id, $selectedDestinations) ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="city-{{ $id }}">{{ $name }}</label>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>


                    <div class="add_form col-md-4">
                        <label class="mb-2">Location</label>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle w-100" type="button"
                                id="districtDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Select Location
                            </button>
                            <ul class="dropdown-menu p-2" id="district-checkbox-list"
                                style="max-height: 300px; overflow-y: auto;">
                                <li class="text-muted px-2">Select Destination first</li>
                            </ul>
                        </div>
                    </div>


                </div>

                <br>

                <!-- Stays Section -->
                <div id="stays-section" class="row d-flex mt-3">
                    <!-- If your checkboxes are inside the dropdown menu and you need to select many at once, add data-bs-auto-close="false" -->
                    <div class="dropdown" data-bs-auto-close="false">
                        <label class="mb-2">Stay Details</label>
                        <button
                            class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                            type="button" id="stayDropdown" data-bs-toggle="dropdown" aria-expanded="false">

                            <span id="stayDropdownText"> @if(!empty($stay_id))
                                @if(count($stay_id) === 1)
                                {{ count($stay_id) }} stay selected
                                @else
                                {{ count($stay_id) }} stay selected
                                @endif
                                @else
                                Select stay
                                @endif</span>
                        </button>
                        <ul id="stayDropdownMenu" class="dropdown-menu w-100 p-2" aria-labelledby="stayDropdown"
                            style="max-height: 200px; overflow-y: auto;">
                            <!-- You may populate simple stay options here if needed -->
                        </ul>
                    </div>

                    <input type="hidden" name="selected_stay_titles" id="selectedStayTitles" value="">


                    <div id="stays-details-container" class="mt-3"></div>

                    <!-- visible total -->
                    <div class="mt-3">
                        <h5>Total Selected Amount: <span id="total-stay-amount">0</span></h5>
                    </div>



                </div>

                <!-- Activities Section -->
                <div id="activities-section" class="row d-flex mt-3">
                    <div class="add_form col-md-4">
                        <label class="mb-2">Activity</label>
                        <div class="dropdown">
                            <button
                                class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                type="button" id="activityDropdown" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <span id="activityDropdownText">
                                    @if(!empty($activity_id))
                                    @if(count($activity_id) === 1)
                                    {{ count($activity_id) }} stay selected
                                    @else
                                    {{ count($activity_id) }} stay selected
                                    @endif
                                    @else
                                    Select activity
                                    @endif
                                </span>
                            </button>
                            <ul class="dropdown-menu w-100 p-2" aria-labelledby="activityDropdown"
                                style="max-height: 200px; overflow-y: auto;">
                            </ul>
                        </div>
                        <input type="hidden" name="selected_activity_titles" id="selectedActivityTitles">
                    </div>
                    <div id="activity-details-container" class="mt-3"></div>
                    <div class="mt-3">
                        <h5>Total Activity Cost: <span id="total-amount">0</span></h5>
                    </div>

                </div>

                <div id="cabs-section" class="row d-flex mt-3">
                    <div class="add_form col-md-4">
                        <label class="mb-2">Travel Mode</label>
                        <div class="dropdown">
                            <button
                                class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                type="button" id="cabDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="cabDropdownText">Select option</span>
                            </button>
                            <ul class="dropdown-menu w-100 p-2" aria-labelledby="cabDropdown"
                                style="max-height: 200px; overflow-y: auto;">
                                <!-- Cabs will be populated here via JavaScript -->
                            </ul>
                        </div>
                        <!-- <input type="hidden" name="cab_types" id="cabHiddenInput"> -->

                    </div>

                    <!-- Cab details selection -->
                    <div id="cabs-details-container" class="mt-3" style="display: none;">
                        <div class="col-md-4">
                            <label class="mb-2">Travel Details</label>
                            <div class="dropdown">
                                <button
                                    class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
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

                    <!-- Total Cab Cost Display -->
                    <div class="mt-3">
                        <h5>Total Cab Cost: <span id="total-cab-amount">0.00</span></h5>
                    </div>
                </div>

                <!-- KEEP ONLY THESE - they have the correct initial values -->
                <input type="hidden" name="stay_id" id="stayHiddenInput" value="{{ implode(',', $stay_id ?? []) }}">
                <input type="hidden" name="activity_ids" id="activityHiddenInput" value="{{ implode(',', $activity_id ?? []) }}">
                <input type="hidden" name="cab_types_val" id="cabTypeHiddenInput">

                {{-- <div class="add_form col-md-4 mb-5 mt-3">
                                <label class="mb-2">Service Fee <span class="text-danger">*</span></label>
                                <input type="text" id="service_fee" name="service_fee" value="0" required>
                            </div> --}}

                {{-- <div class="add_form col-md-4">
                                <label class="mb-2">GST (%) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control py-2 rounded-3 shadow-sm" id="gst_rate"
                                    name="gst_rate" value="0" required>
                            </div> --}}

                <!-- grand total -->

                <div class="mt-5">
                    <h5>Subtotal (Stay + Activity + Cab): <span id="beforeGst-display">₹0.00</span>
                    </h5>
                    {{-- <h5>GST: <span id="gst-total-display">0 %</span></h5> --}}
                    <h4>Grand Total: <span id="grand-total-display">₹0.00</span></h4>
                </div>

                <!-- Hidden input for form submission - MAKE SURE THIS IS IN YOUR FORM -->
                <input type="hidden" name="grand_total" id="grand-total" value="0">

                <!-- Hidden field for cab titles -->
                <input type="hidden" name="selected_cab_titles" id="selectedCabTitles" value="">
                {{-- <h5>Total Selected Amount: <span id="total-stay-amount">0</span></h5>
                            <h5>Total Activity Cost: <span id="total-amount">0</span></h5>
                            <h5>Total Cab Cost: <span id="total-cab-amount">0</span></h5>
                            <h4>Grand Total (with 5% GST): <span id="grand-total">₹0.00</span></h4> --}}



                <div class="col-lg-12 text-center mt-5">
                    <a href="{{ route('admin.pricinglist') }}">
                        <button type="button" class="cancel-btn"> Cancel </button>
                    </a>
                    <button class="submit-btn sbmtBtn ms-4 mb-5"> Submit </button>
                </div>
        </div>
    </div>



    </form>

</div>

</div>

<script>
    $(document).ready(function() {

        const initialDestination = $('.city-checkbox:checked').map(function() {
            return $(this).val();
        }).get().join(',');

        const initialvalId = "{{ $destination_details->id ?? '' }}";

        const initialDistrict = "{{ $destination_details->district_id ?? '' }}";

        // Convert PHP arrays to JSON strings for JavaScript
        const initstayIds = @json($stay_id ?? []);
        const initactivityIds = @json($activity_id ?? []);
        const initcabDetailIds = @json($cabs_id ?? []);
        //cab
        const initcabIds = "{{ $destination_details->cab_type ?? '' }}";
        const initialSelectedCab = initcabIds ? initcabIds.split(',') : [];

        // Global arrays to track selections
        let selectedStays = [...initstayIds];
        let selectedActivities = [...initactivityIds];
        let selectedCabs = Array.isArray(initcabDetailIds) ? [...initcabDetailIds] : [];


        // If we have initial destinations, trigger district loading
        if (initialDestination) {
            loadDistricts(initialDestination, initialDistrict);
        }

        updateGrandTotal();
        // ==============================
        // Hide sections initially
        // ==============================
        $('#stays-section, #activities-section, #cabs-section').hide();

        // ==============================
        // Destination change
        // ==============================
        $(document).ready(function() {
            $('#cities_name').multiselect({
                includeSelectAllOption: true, // Adds "Select All" option
                enableFiltering: true, // Adds a search box
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '100%', // Makes it full width
                nonSelectedText: 'Select Destination',
                selectAllText: 'Select All',
                allSelectedText: 'All selected',
                maxHeight: 300,
                numberDisplayed: 2
            });
        });


        // Add checkboxes in the dropdown
        function formatCheckbox(option) {
            if (!option.id) return option.text;
            return $('<span><input type="checkbox" style="margin-right:8px;">' + option.text + '</span>');
        }

        // Keep selected text clean
        function formatSelection(option) {
            return option.text;
        }

        $(document).on('change', '.city-checkbox', function() {
            const selectedCities = $('.city-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            loadDistricts(selectedCities.join(','));
        });

        function loadDistricts(destination, selectedDistricts = null) {
            const districtList = $('#district-checkbox-list');

            if (!destination) {
                districtList.empty().append('<li class="text-muted px-2">Select Destination first</li>');
                return;
            }

            // Show loading state
            districtList.empty().append('<li class="text-muted px-2">Loading...</li>');

            $.ajax({
                url: '/get-multi-districts',
                type: 'GET',
                data: {
                    destination: destination
                },
                success: function(data) {
                    districtList.empty();

                    if (data && data.length > 0) {
                        // Convert selectedDistricts string to array if needed
                        let districtsToSelect = [];
                        if (selectedDistricts) {
                            districtsToSelect = typeof selectedDistricts === 'string' ?
                                selectedDistricts.split(',') :
                                selectedDistricts;
                        }

                        $.each(data, function(index, district) {
                            const isChecked = districtsToSelect.includes(district.id.toString());

                            districtList.append(`
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input district-checkbox" type="checkbox"
                                        name="district_name[]" id="district-${district.id}" 
                                        value="${district.id}" ${isChecked ? 'checked' : ''}>
                                    <label class="form-check-label" for="district-${district.id}">
                                        ${district.name}
                                    </label>
                                </div>
                            </li>
                        `);
                        });

                        // Update district dropdown button text
                        updateDistrictButtonText();

                        // ✅ TRIGGER DISTRICT CHANGE AFTER LOADING DISTRICTS
                        triggerDistrictChange();
                    } else {
                        districtList.append('<li class="text-muted px-2">No districts found</li>');
                    }
                },
                error: function() {
                    districtList.empty().append('<li class="text-danger px-2">Error loading districts</li>');
                }
            });
        }

        // Function to update district dropdown button text
        function updateDistrictButtonText() {
            const selectedCount = $('.district-checkbox:checked').length;
            const buttonText = selectedCount > 0 ?
                `${selectedCount} location(s) selected` :
                'Select Location';

            $('#districtDropdownButton').text(buttonText);
        }

        // Update district button text when checkboxes change
        $(document).on('change', '.district-checkbox', function() {
            updateDistrictButtonText();
        });

        // Initialize district button text on page load
        updateDistrictButtonText();

        // ==============================
        // District change
        // ==============================

        let currentDistrict = initialDistrict;

        // ✅ EXTRACT THE DISTRICT CHANGE LOGIC INTO A SEPARATE FUNCTION
        function triggerDistrictChange() {
            const selectedDestinations = $('.city-checkbox:checked').map(function() {
                return $(this).val();
            }).get().join(',');

            const selectedDistricts = $('.district-checkbox:checked').map(function() {
                return $(this).val();
            }).get().join(',');

            $('#stays-section, #activities-section, #cabs-section').hide();

            if (!selectedDestinations || !selectedDistricts) {
                return;
            }

            $.ajax({
                url: "{{ route('admin.pricing_details') }}",
                type: 'POST',
                data: {
                    destination: selectedDestinations,
                    district: selectedDistricts,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    // ===============================
                    // STAYS
                    // ===============================
                    if (data.stays && Object.keys(data.stays).length > 0) {
                        const staysSection = $('#stays-section');
                        const staysDropdown = staysSection.find('.dropdown-menu');
                        staysDropdown.empty();
                        staysSection.show();

                        // Get current selected stays from hidden input
                        const currentSelectedStays = $('#stayHiddenInput').val() ? $('#stayHiddenInput').val().split(',') : [];

                        $.each(data.stays, function(id, title) {
                            const isChecked = currentSelectedStays.includes(id.toString());
                            staysDropdown.append(`
                            <li>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input stay-checkbox" id="stay-${id}" value="${id}" ${isChecked ? 'checked' : ''}>
                                    <label class="form-check-label" for="stay-${id}">${title}</label>
                                </div>
                            </li>
                        `);
                        });

                        // Update dropdown text immediately
                        updateDropdownTexts();

                        $('.stay-checkbox:checked').trigger('change');
                    }

                    // ===============================
                    // ACTIVITIES
                    // ===============================
                    if (data.activities && Object.keys(data.activities).length > 0) {
                        const activitiesSection = $('#activities-section');
                        const activitiesDropdown = activitiesSection.find('.dropdown-menu');
                        activitiesDropdown.empty();
                        activitiesSection.show();

                        // Get current selected activities from hidden input
                        const currentSelectedActivities = $('#activityHiddenInput').val() ? $('#activityHiddenInput').val().split(',') : [];

                        $.each(data.activities, function(id, title) {

                            const isChecked = currentSelectedActivities.includes(id.toString());
                            activitiesDropdown.append(`
                            <li>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input activity-checkbox" id="activity-${id}" value="${id}" ${isChecked ? 'checked' : ''}>
                                    <label class="form-check-label" for="activity-${id}">${title}</label>
                                </div>
                            </li>
                        `);
                        });

                        // Update dropdown text immediately
                        updateDropdownTexts();


                        $('.activity-checkbox:checked').trigger('change');
                    }

                    // In your triggerDistrictChange function, update the CAB section:
                    // ===============================
                    // CABS - ONLY SHOW BUS AND CAB
                    // ===============================
                    if (data.cabs && Object.keys(data.cabs).length > 0) {
                        const cabsSection = $('#cabs-section');
                        const cabsDropdown = cabsSection.find('.dropdown-menu');
                        cabsDropdown.empty();
                        cabsSection.show();

                        // Filter to only show Bus and Cab options
                        const filteredCabs = {};
                        $.each(data.cabs, function(key, value) {
                            // Only include options that contain "Bus" or "Cab" (case insensitive)
                            if (value.toLowerCase().includes('bus') || value.toLowerCase().includes('cab')) {
                                filteredCabs[key] = value;
                            }
                        });

                        // If no Bus/Cab found, show all options as fallback
                        const cabsToShow = Object.keys(filteredCabs).length > 0 ? filteredCabs : data.cabs;

                        const selectedCabs = initialSelectedCab
                            .filter(key => cabsToShow[key]) // Only keep keys that exist in current cabs
                            .map(key => ({
                                key: key,
                                value: cabsToShow[key]
                            }));

                        $.each(cabsToShow, function(key, value) {
                            const isChecked = initialSelectedCab.includes(key);
                            cabsDropdown.append(`
                                <li>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input cab-checkbox" id="cab-${key}" value="${key}" data-text="${value}" ${isChecked ? 'checked' : ''}>
                                        <label class="form-check-label" for="cab-${key}">${value}</label>
                                    </div>
                                </li>
                            `);
                        });

                        if (selectedCabs.length > 0) {
                            $('.cab-checkbox:checked').trigger('change');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        }

        // ✅ USE THE SAME FUNCTION FOR BOTH CHANGE EVENT AND MANUAL TRIGGER
        $(document).on('change', '.district-checkbox', triggerDistrictChange);

        // ==============================
        // Stay details AJAX
        // ==============================
        $(document).on('change', '.stay-checkbox', function() {
            // const destination = $('#cities_name').val();
            // const destination = $('#cities_name option:selected').text();
            const destination = $('.city-checkbox:checked').map(function() {
                return $(this).val();
            }).get().join(',');
            const district = $('.district-checkbox:checked').map(function() {
                return $(this).val();
            }).get().join(',');

            const pricingCalculatorId = initialvalId;

            const selectedStays = [];
            $('.stay-checkbox:checked').each(function() {
                selectedStays.push($(this).val());
            });

            $('#stayHiddenInput').val(selectedStays.join(','));

            if (selectedStays.length === 0) {
                $('#stays-details-container').empty();
                $('#selectedStayTitles').val('');
                $('#total-stay-amount').text('0');
                updateGrandTotal();
                return;
            }

            $.ajax({
                url: "{{ route('admin.stay_details') }}",
                type: 'POST',
                data: {
                    destination: destination,
                    district: district,
                    staydetails: selectedStays,
                    pricing_calculator_id: pricingCalculatorId,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    const container = $('#stays-details-container');
                    container.empty();

                    if (!Array.isArray(data.stays_details) || data.stays_details.length ===
                        0) {
                        container.append('<div>No stay details found.</div>');
                        return;
                    }

                    data.stays_details.forEach((stayGroup) => {
                        const groupTitle = stayGroup[0]?.title || '';
                        container.append(
                            `<div class="row stay-group-header mb-2"><div class="col-md-12"><h5>${groupTitle}</h5></div></div>`
                        );
                        stayGroup.forEach((stay) => {
                            const rawPrice = stay.price !== undefined &&
                                stay.price !== null ? String(stay.price)
                                .trim() : '0';

                            // Determine if checkbox should be checked
                            const isChecked = stay.is_checked ? 'checked' : '';
                            container.append(`
                                    <div class="row stay-price-row mb-3 align-items-center" data-stay-id="${stay.stay_id}">
                                        <div class="col-md-1 text-center">
                                            <input type="checkbox" class="form-check-input stay-title-checkbox"
                                               ${isChecked}
                                                data-price="${rawPrice}"
                                                data-stay-id="${stay.stay_id}"
                                                data-price-title="${stay.price_title}"
                                                data-title="${stay.title}">
                                        </div>
                                        <div class="col-md-4"><span>${stay.price_title}</span></div>
                                        <div class="col-md-4">
                                            <input type="number" class="form-control price-input" value="${rawPrice}" step="0.01" min="0" placeholder="Enter price">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-outline-secondary btn-sm reset-price-btn" data-original-price="${rawPrice}">Reset</button>
                                        </div>
                                    </div>
                                `);
                        });
                    });

                    $('.price-input').on('input', function() {
                        const $checkbox = $(this).closest('.stay-price-row').find(
                            '.stay-title-checkbox');
                        if ($checkbox.is(':checked')) {
                            updateSelectedStayTitles();
                            updateStayTotal();
                            updateGrandTotal();
                        }
                    });

                    $('.reset-price-btn').click(function() {
                        const originalPrice = $(this).data('original-price');
                        const $priceInput = $(this).closest('.stay-price-row').find(
                            '.price-input');
                        $priceInput.val(originalPrice).trigger('input');
                    });

                    $('.stay-title-checkbox').change(function() {
                        updateSelectedStayTitles();
                        updateStayTotal();
                        updateGrandTotal();
                    });

                    updateSelectedStayTitles();
                    updateStayTotal();
                    updateGrandTotal();
                }
            });
        });

        function updateSelectedStayTitles() {
            const selectedTitles = [];
            $('.stay-title-checkbox:checked').each(function() {
                const stayId = $(this).data('stay-id');
                const priceTitle = $(this).data('price-title');
                const title = $(this).data('title');
                const priceInput = $(this).closest('.stay-price-row').find('.price-input');
                const price = parseFloat(priceInput.val()) || 0;
                selectedTitles.push({
                    stay_id: stayId,
                    title: title,
                    price_title: priceTitle,
                    price: price
                });
            });
            $('#selectedStayTitles').val(JSON.stringify(selectedTitles));
        }

        function updateStayTotal() {
            let total = 0;
            $('.stay-title-checkbox:checked').each(function() {
                const priceInput = $(this).closest('.stay-price-row').find('.price-input');
                const price = parseFloat(priceInput.val()) || 0;
                total += price;
            });
            $('#total-stay-amount').text(total.toFixed(2));
        }

        // ==============================
        // Activity details AJAX
        // ==============================
        $(document).on('change', '.activity-checkbox', function() {
            // const destination = $('#cities_name').val();
            const destination = $('.city-checkbox:checked').map(function() {
                return $(this).val();
            }).get().join(',');
            const district = $('.district-checkbox:checked').map(function() {
                return $(this).val();
            }).get().join(',');
            const pricingCalculatorId = initialvalId;

            const selectedActivities = [];
            $('.activity-checkbox:checked').each(function() {
                selectedActivities.push($(this).val());
            });

            if (selectedActivities.length === 0) {
                $('#activity-details-container').empty();
                $('#selectedActivityTitles').val('');
                $('#total-amount').text('0');
                updateGrandTotal();
                return;
            }

            $.ajax({
                url: "{{ route('admin.activity_details') }}",
                type: 'POST',
                data: {
                    destination: destination,
                    district: district,
                    staydetails: selectedActivities,
                    pricing_calculator_id: pricingCalculatorId,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    const container = $('#activity-details-container');
                    container.empty();

                    if (!Array.isArray(data.activity_details) || data.activity_details
                        .length === 0) {
                        container.append('<div>No activity details found.</div>');
                        return;
                    }

                    data.activity_details.forEach((activityGroup) => {
                        const groupTitle = activityGroup[0]?.title || '';
                        container.append(
                            `<div class="row activity-group-header mb-2"><div class="col-md-12"><h5>${groupTitle}</h5></div></div>`
                        );
                        activityGroup.forEach((activity) => {
                            const rawPrice = activity.price !== undefined &&
                                activity.price !== null ? String(activity
                                    .price).trim() : '0';

                            const isChecked = activity.is_checked ? 'checked' : '';
                            container.append(`
                                    <div class="row activity-price-row mb-3 align-items-center" data-activity-id="${activity.activity_id}">
                                        <div class="col-md-1 text-center">
                                            <input type="checkbox" class="form-check-input activity-title-checkbox"
                                                ${isChecked}
                                                data-original-price="${rawPrice}"
                                                data-activity-id="${activity.activity_id}"
                                                data-price-title="${activity.price_title}"
                                                data-title="${activity.title}">
                                        </div>
                                        <div class="col-md-4"><span>${activity.price_title}</span></div>
                                        <div class="col-md-4">
                                            <input type="number" class="form-control activity-price-input" value="${rawPrice}" step="0.01" min="0" placeholder="Enter price">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-outline-secondary btn-sm reset-activity-price-btn" data-original-price="${rawPrice}">Reset</button>
                                        </div>
                                    </div>
                                `);
                        });
                    });

                    $('.activity-price-input').on('input', function() {
                        const $checkbox = $(this).closest('.activity-price-row')
                            .find('.activity-title-checkbox');
                        if ($checkbox.is(':checked')) {
                            updateSelectedActivityTitles();
                            updateActivityTotal();
                            updateGrandTotal();
                        }
                    });

                    $('.reset-activity-price-btn').click(function() {
                        const originalPrice = $(this).data('original-price');
                        const $priceInput = $(this).closest('.activity-price-row')
                            .find('.activity-price-input');
                        $priceInput.val(originalPrice).trigger('input');
                    });

                    $('.activity-title-checkbox').change(function() {
                        updateSelectedActivityTitles();
                        updateActivityTotal();
                        updateGrandTotal();
                    });

                    updateSelectedActivityTitles();
                    updateActivityTotal();
                    updateGrandTotal();
                }
            });
        });

        function updateSelectedActivityTitles() {
            const selectedTitles = [];
            $('.activity-title-checkbox:checked').each(function() {
                const activityId = $(this).data('activity-id');
                const priceTitle = $(this).data('price-title');
                const title = $(this).data('title');
                const priceInput = $(this).closest('.activity-price-row').find('.activity-price-input');
                const price = parseFloat(priceInput.val()) || 0;
                selectedTitles.push({
                    activity_id: activityId,
                    title: title,
                    price_title: priceTitle,
                    price: price
                });
            });
            $('#selectedActivityTitles').val(JSON.stringify(selectedTitles));
        }

        function updateActivityTotal() {
            let total = 0;
            $('.activity-title-checkbox:checked').each(function() {
                const priceInput = $(this).closest('.activity-price-row').find('.activity-price-input');
                const price = parseFloat(priceInput.val()) || 0;
                total += price;
            });
            $('#total-amount').text(total.toFixed(2));
        }


        // ==============================
        // Cab details AJAX - FIXED VERSION
        // ==============================
        $(document).on('change', '.cab-checkbox', function() {
            const destination = $('.city-checkbox:checked').map(function() {
                return $(this).val();
            }).get().join(',');
            const district = $('.district-checkbox:checked').map(function() {
                return $(this).val();
            }).get().join(',');

            const pricingCalculatorId = initialvalId;

            const selectedCabIds = [];
            const selectedCabTexts = [];

            $('.cab-checkbox:checked').each(function() {
                selectedCabIds.push($(this).val());
                selectedCabTexts.push($(this).data('text'));
            });

            // ✅ FIX: Update selectedCabs array first
            selectedCabs = [...selectedCabIds];

            // ✅ FIX: Then update the hidden input with the correct variable
            $('#cabHiddenInput').val(selectedCabIds.join(','));
            $('#cabTypeHiddenInput').val(selectedCabTexts.join(', ')); // For display texts

            // Update UI to show selected cab types
            const cabDropdownText = $('#cabDropdownText');
            if (selectedCabTexts.length > 0) {
                // Remove duplicates from displayed text too
                const uniqueTexts = [...new Set(selectedCabTexts)];
                const displayText = uniqueTexts.join(', ');
                cabDropdownText.text(displayText);

                // ✅ FIX: Also update the type hidden input with the same display text
                $('#cabTypeHiddenInput').val(displayText);
            } else {
                cabDropdownText.text('Select Travel Mode');
                $('#cabTypeHiddenInput').val(''); // Clear when nothing selected
            }

            // ✅ REMOVE THIS DUPLICATE LINE - it's already set above
            // $('#cabHiddenInput').val(selectedCabIds.join(','));

            if (selectedCabIds.length === 0) {
                $('#cabs-details-container').hide();
                $('#cabsdetails-container').empty();
                $('#selectedCabTitles').val('');
                $('#total-cab-amount').text('0.00');
                updateGrandTotal();
                return;
            }

            $.ajax({
                url: "{{ route('admin.travel_details') }}",
                type: 'POST',
                data: {
                    destination: destination,
                    district: district,
                    travelmodes: selectedCabIds,
                    pricing_calculator_id: pricingCalculatorId,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    buildCabMultiSelector(data.cabs);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $('#cabs-details-container').hide();
                }
            });
        });

        function buildCabMultiSelector(cabsData) {
            const container = $('#cabs-details-container');
            const dropdownMenu = container.find('.dropdown-menu');
            const dropdownText = $('#cabDetailsDropdownText');
            const hiddenInput = $('#cabDetailsHiddenInput');

            dropdownMenu.empty();
            dropdownText.text('Select options');
            hiddenInput.val('');

            if (!cabsData || Object.keys(cabsData).length === 0) {
                container.hide();
                return;
            }

            // Get initial cab detail selections if available
            const initialCabDetails = @json($cabs_id ?? []);

            // Remove duplicates by value/text to avoid duplicate entries
            const uniqueCabs = {};
            const seenTitles = new Set();

            $.each(cabsData, function(id, title) {
                if (!seenTitles.has(title)) {
                    uniqueCabs[id] = title;
                    seenTitles.add(title);
                }
            });

            $.each(uniqueCabs, function(id, title) {
                const isChecked = initialCabDetails.includes(id.toString());
                dropdownMenu.append(`
                    <li>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input cab-details-checkbox"
                                id="cab-detail-${id}" value="${id}" data-text="${title}" 
                                ${isChecked ? 'checked' : ''}>
                            <label class="form-check-label" for="cab-detail-${id}">${title}</label>
                        </div>
                    </li>
                `);
            });

            // Prevent dropdown close when clicking checkboxes
            dropdownMenu.on('click', '.form-check', function(e) {
                e.stopPropagation();
            });

            // Remove any existing event handlers and attach new ones
            $(document)
                .off('change', '.cab-details-checkbox')
                .on('change', '.cab-details-checkbox', function() {
                    const selected = [];
                    const selectedIds = [];

                    $('.cab-details-checkbox:checked').each(function() {
                        selected.push({
                            id: $(this).val(),
                            text: $(this).data('text')
                        });
                        selectedIds.push($(this).val());
                    });

                    // Update UI text
                    dropdownText.text(
                        selected.length > 0 ? selected.map(i => i.text).join(', ') : 'Select options'
                    );
                    hiddenInput.val(selectedIds.join(','));

                    if (selected.length > 0) {
                        const destination = $('.city-checkbox:checked').map(function() {
                            return $(this).val();
                        }).get().join(',');
                        const district = $('.district-checkbox:checked').map(function() {
                            return $(this).val();
                        }).get().join(',');
                        const travelmodes = $('.cab-checkbox:checked')
                            .map(function() {
                                return $(this).val();
                            })
                            .get();
                        const pricingCalculatorId = initialvalId;
                        // Show loading state
                        $('#cabsdetails-container').html('<div class="text-center p-3">Loading cab details...</div>').show();

                        $.ajax({
                            url: "{{ route('admin.cabs_details') }}",
                            type: 'POST',
                            data: {
                                destination: destination,
                                district: district,
                                cabdetails: selectedIds,
                                travelmodes: travelmodes,
                                pricing_calculator_id: pricingCalculatorId,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(data) {
                                displayCabDetailsForSelected(data.activity_details || data.cabs_details || data);
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', error);
                                $('#cabsdetails-container').html('<div class="text-danger p-3">Error loading cab details</div>').show();
                            }
                        });
                    } else {
                        $('#cabsdetails-container').empty().hide();
                        $('#selectedCabTitles').val('');
                        $('#total-cab-amount').text('0.00');
                        updateGrandTotal();
                    }
                });

            // Trigger change for pre-checked boxes after a short delay to ensure DOM is ready
            setTimeout(function() {
                const checkedBoxes = $('.cab-details-checkbox:checked');
                if (checkedBoxes.length > 0) {
                    checkedBoxes.trigger('change');
                }
            }, 100);

            container.show();
        }

        function displayCabDetailsForSelected(detailsData) {
            const container = $('#cabsdetails-container');
            container.empty();

            if (!detailsData || (Array.isArray(detailsData) && detailsData.length === 0) || (typeof detailsData === 'object' && Object.keys(detailsData).length === 0)) {
                container.html('<div class="text-muted p-3">No cab details available for selected options</div>').hide();
                $('#selectedCabTitles').val('');
                $('#total-cab-amount').text('0.00');
                updateGrandTotal();
                return;
            }

            let html = `
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th width="50px">
                                <input type="checkbox" id="selectAllCabs"> All
                            </th>
                            <th>Cab Service</th>
                            <th>Pricing Option</th>
                            <th>Price (₹)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            // Handle both array and object formats
            if (Array.isArray(detailsData)) {
                detailsData.forEach((group, groupIndex) => {
                    if (Array.isArray(group)) {
                        group.forEach((item, itemIndex) => {
                            html += createCabRow(item, groupIndex, itemIndex);
                        });
                    } else {
                        // Handle flat array
                        html += createCabRow(group, groupIndex, 0);
                    }
                });
            } else {
                // Handle object format
                Object.keys(detailsData).forEach((key, index) => {
                    const item = detailsData[key];
                    html += createCabRow(item, index, 0);
                });
            }

            html += `</tbody></table>`;
            container.append(html).show();

            // Initialize event handlers
            initializeCabEventHandlers();

            // Update totals immediately
            updateSelectedCabTitles();
            updateCabTotal();
            updateGrandTotal();
        }

        function createCabRow(item, groupIndex, itemIndex) {
            const rawPrice = item.price !== undefined && item.price !== null ? String(item.price).trim() : '0';
            const cabId = item.cab_id || item.id || `${groupIndex}-${itemIndex}`;
            const title = item.title || 'Unknown Service';
            const priceTitle = item.price_title || 'Standard';

            // Determine if checkbox should be checked - LIKE STAYS
            const isChecked = item.is_checked ? 'checked' : '';

            return `
                <tr>
                    <td>
                        <input type="checkbox" class="form-check-input cab-title-checkbox"
                            ${isChecked}
                            data-price="${rawPrice}"
                            data-cab-id="${cabId}"
                            data-price-title="${priceTitle}"
                            data-title="${title}"
                            id="cab-price-${cabId}-${itemIndex}">
                    </td>
                    <td><strong>${title}</strong></td>
                    <td>${priceTitle}</td>
                    <td>
                        <input type="number" class="form-control cab-price-input"
                            value="${rawPrice}" step="0.01" min="0"
                            data-cab-id="${cabId}" 
                            data-price-title="${priceTitle}"
                    data-original-price="${rawPrice}">
            </td>
            <td>
                <button type="button" class="btn btn-outline-secondary btn-sm reset-cab-price-btn"
                    data-original-price="${rawPrice}">Reset</button>
            </td>
                </tr>
            `;
        }

        function initializeCabEventHandlers() {
            // Remove existing event handlers to prevent duplicates
            $('#selectAllCabs').off('change');
            $('.cab-title-checkbox').off('change');
            $('.cab-price-input').off('input');
            $('.reset-cab-price-btn').off('click');

            // Select All functionality
            $('#selectAllCabs').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.cab-title-checkbox').prop('checked', isChecked).trigger('change');
            });

            // Individual checkbox change
            $('.cab-title-checkbox').on('change', function() {
                updateSelectedCabTitles();
                updateCabTotal();
                updateGrandTotal();

                // Update Select All checkbox state
                const totalCheckboxes = $('.cab-title-checkbox').length;
                const checkedCheckboxes = $('.cab-title-checkbox:checked').length;
                $('#selectAllCabs').prop('checked', totalCheckboxes === checkedCheckboxes);
            });

            // Price input change
            $('.cab-price-input').on('input', function() {
                const cabId = $(this).data('cab-id');
                const priceTitle = $(this).data('price-title');
                const price = $(this).val();

                // Update the data-price attribute of the corresponding checkbox
                $(`.cab-title-checkbox[data-cab-id="${cabId}"][data-price-title="${priceTitle}"]`)
                    .attr('data-price', price);

                // Only update if checkbox is checked
                const $checkbox = $(this).closest('tr').find('.cab-title-checkbox');
                if ($checkbox.is(':checked')) {
                    updateSelectedCabTitles();
                    updateCabTotal();
                    updateGrandTotal();
                }
            });

            // Reset price button
            $('.reset-cab-price-btn').on('click', function() {
                const originalPrice = $(this).data('original-price');
                const $priceInput = $(this).closest('tr').find('.cab-price-input');
                $priceInput.val(originalPrice).trigger('input');
            });
        }

        function updateSelectedCabTitles() {
            const selectedTitles = [];

            $('.cab-title-checkbox:checked').each(function() {
                const cabId = $(this).data('cab-id');
                const title = $(this).data('title');
                const priceTitle = $(this).data('price-title');

                // Get price from input field
                const priceInput = $(`.cab-price-input[data-cab-id="${cabId}"][data-price-title="${priceTitle}"]`);
                const price = parseFloat(priceInput.val()) || 0;

                selectedTitles.push({
                    cab_id: cabId,
                    title: title,
                    price_title: priceTitle,
                    price: price
                });
            });

            $('#selectedCabTitles').val(JSON.stringify(selectedTitles));
        }

        function updateCabTotal() {
            let total = 0;

            $('.cab-title-checkbox:checked').each(function() {
                const cabId = $(this).data('cab-id');
                const priceTitle = $(this).data('price-title');
                const priceInput = $(`.cab-price-input[data-cab-id="${cabId}"][data-price-title="${priceTitle}"]`);
                const price = parseFloat(priceInput.val()) || 0;
                total += price;
            });

            $('#total-cab-amount').text(total.toFixed(2));
        }

        function updateGrandTotal() {
            // Stay total
            var stayTotal = 0;
            $('.stay-title-checkbox:checked').each(function() {
                const priceInput = $(this).closest('.stay-price-row').find('.price-input');
                stayTotal += parseFloat(priceInput.val()) || 0;
            });

            // Activity total
            var activityTotal = 0;
            $('.activity-title-checkbox:checked').each(function() {
                const priceInput = $(this).closest('.activity-price-row').find('.activity-price-input');
                activityTotal += parseFloat(priceInput.val()) || 0;
            });

            // Cab total
            var cabTotal = 0;
            $('.cab-title-checkbox:checked').each(function() {
                const priceInput = $(this).closest('tr').find('.cab-price-input');
                cabTotal += parseFloat(priceInput.val()) || 0;
            });

            var subtotal = stayTotal + activityTotal + cabTotal;
            var grandTotal = subtotal; // No GST or service fee

            // Update UI - simplified display
            $('#beforeGst-display').text('₹' + subtotal.toFixed(2));
            $('#beforeGst').val(subtotal.toFixed(2));
            $('#gst-total-display').text('0 %'); // Show 0% GST
            $('#grand-total-display').text('₹' + grandTotal.toFixed(2));
            $('#grand-total').val(grandTotal.toFixed(2));
        }

        // Initialize all checkboxes based on hidden input values
        function initializeCheckboxes() {
            // Get initial values from hidden inputs
            const initialStayIds = $('#stayHiddenInput').val() ? $('#stayHiddenInput').val().split(',') : [];
            const initialActivityIds = $('#activityHiddenInput').val() ? $('#activityHiddenInput').val().split(',') : [];
            const initialCabIds = $('#cabHiddenInput').val() ? $('#cabHiddenInput').val().split(',') : [];

            // Check stay checkboxes
            initialStayIds.forEach(stayId => {
                const $checkbox = $(`.stay-checkbox[value="${stayId}"]`);
                if ($checkbox.length) {
                    $checkbox.prop('checked', true);
                }
            });

            // Check activity checkboxes  
            initialActivityIds.forEach(activityId => {
                const $checkbox = $(`.activity-checkbox[value="${activityId}"]`);
                if ($checkbox.length) {
                    $checkbox.prop('checked', true);
                }
            });

            // Check cab checkboxes
            initialCabIds.forEach(cabId => {
                const $checkbox = $(`.cab-checkbox[value="${cabId}"]`);
                if ($checkbox.length) {
                    $checkbox.prop('checked', true);
                }
            });

            // Update dropdown texts
            updateDropdownTexts();
        }

        // Update dropdown display texts
        function updateDropdownTexts() {
            const selectedStays = $('.stay-checkbox:checked').length;
            $('#stayDropdownText').text(selectedStays > 0 ? `${selectedStays} stay selected` : 'Select stay');

            const selectedActivities = $('.activity-checkbox:checked').length;
            $('#activityDropdownText').text(selectedActivities > 0 ? `${selectedActivities} activity selected` : 'Select activity');

            const selectedCabTexts = [];
            $('.cab-checkbox:checked').each(function() {
                selectedCabTexts.push($(this).data('text'));
            });
            const uniqueCabTexts = [...new Set(selectedCabTexts)];
            $('#cabDropdownText').text(uniqueCabTexts.length > 0 ? uniqueCabTexts.join(', ') : 'Select Travel Mode');
        }

        // Hidden input update handlers - MOVE THESE INSIDE $(document).ready()
        $(document).on('change', '.stay-checkbox', function() {
            const selectedStays = [];
            $('.stay-checkbox:checked').each(function() {
                selectedStays.push($(this).val());
            });
            $('#stayHiddenInput').val(selectedStays.join(','));
            updateDropdownTexts();
        });

        $(document).on('change', '.activity-checkbox', function() {
            const selectedActivities = [];
            $('.activity-checkbox:checked').each(function() {
                selectedActivities.push($(this).val());
            });
            $('#activityHiddenInput').val(selectedActivities.join(','));
            updateDropdownTexts();
        });

        $(document).on('change', '.cab-checkbox', function() {
            const selectedCabs = [];
            $('.cab-checkbox:checked').each(function() {
                selectedCabs.push($(this).val());
            });
            $('#cabHiddenInput').val(selectedCabs.join(','));
            updateDropdownTexts();
        });

        // ✅ Step 3: Automatically recalculate whenever values change
        $(document).on('input change',
            '.stay-title-checkbox, .activity-title-checkbox, .cab-title-checkbox, .price-input, .activity-price-input, .cab-price-input',
            updateGrandTotal);

        // Auto-update when service fee changes
        $('#service_fee').on('input change', updateGrandTotal);

        // Update all before form submit
        $('form').on('submit', function() {
            updateSelectedStayTitles();
            updateSelectedActivityTitles();
            updateSelectedCabTitles();
            updateGrandTotal();
        });
    });

    // Trigger the change event on page load
    $(document).ready(function() {
        $('.district-checkbox').trigger('change');
    });
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.dropdown-menu .form-check-input');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
@endsection