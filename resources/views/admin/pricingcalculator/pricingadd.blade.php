@extends('layouts.app')
@section('content')
    <style>
        .multiselect-container>li>a>label {
            padding: 6px 10px;
        }

        .multiselect.dropdown-toggle {
            border-radius: 8px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
        }



        a:hover {
            color: red;
        }

        a {
            color: rgb(37, 150, 190);
        }

        .add {
            color: blue;
        }

        /* Align the form with the title */
        .container-wrapper {
            padding-left: 30px;
            /* Adjust as per your layout */
            padding-right: 30px;
            /* Consistent padding for both sides */
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

        /* Ensure responsiveness on all screen sizes */
        @media (max-width: 768px) {
            .custom-checkbox {
                width: 18px;
                height: 18px;
            }
        }
    </style>
    <div class="row body-sec py-3 px-5 justify-content-around">
        <div class="text-start col-lg-6 ">
            <h3 class="admin-title fw-bold">Pricing Calculator</h3>
        </div>
        <div class="text-end col-lg-6 ">
            <b><a href="/dashboard">Dashboard</a> > <a href="/pricingcalculator">Pricing</a> </b>
        </div>

    </div>

    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">

                <form class="" id="form_valid" action="{{ route('admin.pricing_insert') }}" method="POST"
                    autocomplete="off" enctype="multipart/form-data">
                    @csrf

                    <h4 class="fw-bold mb-4">Information</h4>


                    <div class="mb-3 ">

                        <div class="add_form col-md-4">
                            <label class="mb-2">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2 rounded-3 shadow-sm" id="title"
                                name="title" required>
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
                                            @if (old('cities_name') == $id) selected @endif>
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
                                            Select Destination
                                        </button>
                                        <ul class="dropdown-menu p-2" aria-labelledby="dropdownMenuButton"
                                            style="max-height: 300px; overflow-y: auto;">
                                            @foreach ($cities as $id => $name)
                                                <li>
                                                    <div class="form-check">
                                                        <input class="form-check-input city-checkbox" type="checkbox"
                                                            value="{{ $id }}" id="city-{{ $id }}"
                                                            name="cities_name[]">
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
                                    <span id="stayDropdownText">Select stay</span>
                                </button>
                                <ul id="stayDropdownMenu" class="dropdown-menu w-100 p-2" aria-labelledby="stayDropdown"
                                    style="max-height: 200px; overflow-y: auto;">
                                    <!-- You may populate simple stay options here if needed -->
                                </ul>
                            </div>

                            <input type="hidden" name="selected_stay_titles" id="selectedStayTitles" value="">
                            <input type="hidden" name="stay_id" id="stayHiddenInput" value="">
                            <script>
                                // Ensure stayHiddenInput is updated when stay checkboxes change
                                $(document).on('change', '.stay-checkbox', function() {
                                    const selectedStays = [];
                                    $('.stay-checkbox:checked').each(function() {
                                        selectedStays.push($(this).val());
                                    });
                                    $('#stayHiddenInput').val(selectedStays.join(','));
                                });
                            </script>

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
                                        <span id="activityDropdownText">Select activity</span>
                                    </button>
                                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="activityDropdown"
                                        style="max-height: 200px; overflow-y: auto;">
                                    </ul>
                                </div>
                                <input type="hidden" name="selected_activity_titles" id="selectedActivityTitles">
                                <input type="hidden" name="activity_ids" id="activityHiddenInput" value="">
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
                                    <input type="hidden" name="cab_types" id="cabHiddenInput">

                                      <script>
                                        // Ensure stayHiddenInput is updated when stay checkboxes change
                                        $(document).on('change', '.cab-checkbox', function() {
                                            const selectedCabs = [];
                                            $('.cab-checkbox:checked').each(function() {
                                                selectedCabs.push($(this).val());
                                            });
                                            $('#cabHiddenInput').val(selectedCabs.join(','));
                                        });
                                    </script>
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

                const districtList = $('#district-checkbox-list');
                districtList.empty().append('<li class="text-muted px-2">Loading...</li>');

                if (selectedCities.length === 0) {
                    districtList.empty().append('<li class="text-muted px-2">Select Destination first</li>');
                    return;
                }

                $.ajax({
                    url: '/get-multi-districts',
                    type: 'GET',
                    data: { destination: selectedCities.join(',') }, // e.g. "13,15"
                    success: function(data) {
                        districtList.empty();

                        if (data && data.length > 0) {
                            $.each(data, function(index, district) {

                                districtList.append(`
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input district-checkbox" type="checkbox"
                                                name="district_name[]" id="district-${district.id}" value="${district.id}">
                                            <label class="form-check-label" for="district-${district.id}">
                                                ${district.name}
                                            </label>
                                        </div>
                                    </li>
                                `);
                            });
                        } else {
                            districtList.append('<li class="text-muted px-2">No districts found</li>');
                        }
                    },
                    error: function() {
                        districtList.empty().append('<li class="text-danger px-2">Error loading districts</li>');
                    }
                });
            });

            // ==============================
            // District change
            // ==============================
            $(document).on('change', '.district-checkbox', function() {
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
                        district: selectedDistricts, // Now sending multiple districts
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

                            $.each(data.stays, function(id, title) {
                                staysDropdown.append(`
                                    <li>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input stay-checkbox" id="stay-${id}" value="${id}">
                                            <label class="form-check-label" for="stay-${id}">${title}</label>
                                        </div>
                                    </li>
                                `);
                            });
                        }

                        // ===============================
                        // ACTIVITIES
                        // ===============================
                        if (data.activities && Object.keys(data.activities).length > 0) {
                            const activitiesSection = $('#activities-section');
                            const activitiesDropdown = activitiesSection.find('.dropdown-menu');
                            activitiesDropdown.empty();
                            activitiesSection.show();

                            $.each(data.activities, function(id, title) {
                                activitiesDropdown.append(`
                                    <li>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input activity-checkbox" id="activity-${id}" value="${id}">
                                            <label class="form-check-label" for="activity-${id}">${title}</label>
                                        </div>
                                    </li>
                                `);
                            });
                        }

                        // ===============================
                        // CABS
                        // ===============================
                        if (data.cabs && Object.keys(data.cabs).length > 0) {
                            const cabsSection = $('#cabs-section');
                            const cabsDropdown = cabsSection.find('.dropdown-menu');
                            cabsDropdown.empty();
                            cabsSection.show();

                            $.each(data.cabs, function(key, value) {
                                cabsDropdown.append(`
                                    <li>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input cab-checkbox" id="cab-${key}" value="${key}" data-text="${value}">
                                            <label class="form-check-label" for="cab-${key}">${value}</label>
                                        </div>
                                    </li>
                                `);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            });

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

                const selectedStays = [];
                $('.stay-checkbox:checked').each(function() {
                    selectedStays.push($(this).val());
                });

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
                                container.append(`
                                    <div class="row stay-price-row mb-3 align-items-center" data-stay-id="${stay.stay_id}">
                                        <div class="col-md-1 text-center">
                                            <input type="checkbox" class="form-check-input stay-title-checkbox"
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
                                container.append(`
                                    <div class="row activity-price-row mb-3 align-items-center" data-activity-id="${activity.activity_id}">
                                        <div class="col-md-1 text-center">
                                            <input type="checkbox" class="form-check-input activity-title-checkbox"
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
            // Cab details AJAX
            // ==============================
            $(document).on('change', '.cab-checkbox', function() {
                // const destination = $('#cities_name').val();
                const destination = $('.city-checkbox:checked').map(function() {
                    return $(this).val();
                }).get().join(',');
                const district = $('.district-checkbox:checked').map(function() {
                    return $(this).val();
                }).get().join(',');
                const selectedCabIds = [];
                $('.cab-checkbox:checked').each(function() {
                    selectedCabIds.push($(this).val());
                });

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
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        buildCabMultiSelector(data.cabs);
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

                $.each(cabsData, function(id, title) {
                    dropdownMenu.append(`
                        <li>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input cab-details-checkbox"
                                    id="cab-detail-${id}" value="${id}" data-text="${title}">
                                <label class="form-check-label" for="cab-detail-${id}">${title}</label>
                            </div>
                        </li>
                    `);
                });

                dropdownMenu.on('click', '.form-check', function(e) {
                    e.stopPropagation();
                });

                $(document)
                    .off('change', '.cab-details-checkbox')
                    .on('change', '.cab-details-checkbox', function() {
                        const selected = [];
                        $('.cab-details-checkbox:checked').each(function() {
                            selected.push({
                                id: $(this).val(),
                                text: $(this).data('text')
                            });
                        });

                        dropdownText.text(
                            selected.length > 0 ? selected.map(i => i.text).join(', ') : 'Select options'
                        );
                        hiddenInput.val(selected.map(i => i.id).join(','));

                        if (selected.length > 0) {
                            // const destination = $('#cities_name').val();
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

                            $.ajax({
                                url: "{{ route('admin.cabs_details') }}",
                                type: 'POST',
                                data: {
                                    destination: destination,
                                    district: district,
                                    cabdetails: selected.map(i => i.id),
                                    travelmodes: travelmodes,
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(data) {
                                    displayCabDetailsForSelected(data.activity_details);
                                }
                            });
                        } else {
                            $('#cabsdetails-container').empty().hide();
                            $('#selectedCabTitles').val('');
                            $('#total-cab-amount').text('0.00');
                            updateGrandTotal();
                        }
                    });

                container.show();
            }

            function displayCabDetailsForSelected(detailsData) {
                const container = $('#cabsdetails-container');
                container.empty();

                if (!detailsData || detailsData.length === 0) {
                    container.hide();
                    return;
                }

                let html = `
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="50px">
                                    <input type="checkbox" id="selectAllCabs" checked> All
                                </th>
                                <th>Cab Service</th>
                                <th>Pricing Option</th>
                                <th>Price (₹)</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                detailsData.forEach((group, groupIndex) => {
                    group.forEach((item, itemIndex) => {
                        const rawPrice = item.price || '0';
                        html += `
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input cab-title-checkbox"
                                        data-price="${rawPrice}"
                                        data-cab-id="${item.cab_id}"
                                        data-price-title="${item.price_title}"
                                        data-title="${item.title}"
                                        id="cab-price-${item.cab_id}-${itemIndex}"
                                        checked>
                                </td>
                                <td><strong>${item.title}</strong></td>
                                <td>${item.price_title}</td>
                                <td>
                                    <input type="number" class="form-control cab-price-input"
                                        value="${rawPrice}" step="0.01" min="0"
                                        data-cab-id="${item.cab_id}" data-price-title="${item.price_title}">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-secondary btn-sm reset-cab-price-btn"
                                        data-original-price="${rawPrice}">Reset</button>
                                </td>
                            </tr>
                        `;
                    });
                });

                html += `</tbody></table>`;
                container.append(html).show();

                $('#selectAllCabs').on('change', function() {
                    const isChecked = $(this).is(':checked');
                    $('.cab-title-checkbox').prop('checked', isChecked).trigger('change');
                });

                $('.cab-title-checkbox').on('change', function() {
                    updateSelectedCabTitles();
                    updateCabTotal();
                    updateGrandTotal();
                });

                $('.cab-price-input').on('input', function() {
                    // Update the data-price attribute of the corresponding checkbox
                    const cabId = $(this).data('cab-id');
                    const priceTitle = $(this).data('price-title');
                    const price = $(this).val();
                    $(`.cab-title-checkbox[data-cab-id="${cabId}"][data-price-title="${priceTitle}"]`).attr(
                        'data-price', price);

                    updateSelectedCabTitles();
                    updateCabTotal();
                    updateGrandTotal();
                });

                $('.reset-cab-price-btn').click(function() {
                    const originalPrice = $(this).data('original-price');
                    const $priceInput = $(this).closest('tr').find('.cab-price-input');
                    $priceInput.val(originalPrice).trigger('input');
                });

                updateSelectedCabTitles();
                updateCabTotal();
                updateGrandTotal();
            }

            function updateSelectedCabTitles() {
                const selectedTitles = [];
                $('.cab-title-checkbox:checked').each(function() {
                    const cabId = $(this).data('cab-id');
                    const title = $(this).data('title');
                    const priceTitle = $(this).data('price-title');
                    // Get price from input field
                    const priceInput = $(this).closest('tr').find('.cab-price-input');
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
                    const priceInput = $(this).closest('tr').find('.cab-price-input');
                    const price = parseFloat(priceInput.val()) || 0;
                    total += price;
                });
                $('#total-cab-amount').text(total.toFixed(2));
            }

            // ==============================
            // Grand Total Calculation
            // ==============================
            // function updateGrandTotal() {
            //     var stayTotal = 0;
            //     $('.stay-title-checkbox:checked').each(function() {
            //         const priceInput = $(this).closest('.stay-price-row').find('.price-input');
            //         stayTotal += parseFloat(priceInput.val()) || 0;
            //     });

            //     var activityTotal = 0;
            //     $('.activity-title-checkbox:checked').each(function() {
            //         const priceInput = $(this).closest('.activity-price-row').find('.activity-price-input');
            //         activityTotal += parseFloat(priceInput.val()) || 0;
            //     });

            //     var cabTotal = 0;
            //     $('.cab-title-checkbox:checked').each(function() {
            //         const priceInput = $(this).closest('tr').find('.cab-price-input');
            //         cabTotal += parseFloat(priceInput.val()) || 0;
            //     });

            //     var serviceFee = parseFloat($('#service_fee').val().replace(/[^\d.]/g, '')) || 0;

            //     var subtotal = stayTotal + activityTotal + cabTotal;
            //     var gst = subtotal * 0.05;
            //     var beforeGst = subtotal + serviceFee;
            //     var grandTotal = subtotal + gst + serviceFee;
            //     $('#beforeGst-display').text('₹' + beforeGst.toFixed(2));
            //     $('#beforeGst').val(beforeGst.toFixed(2));
            //     $('#grand-total-display').text('₹' + grandTotal.toFixed(2));
            //     $('#grand-total').val(grandTotal.toFixed(2));
            // }
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
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.dropdown-menu .form-check-input');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
@endsection
