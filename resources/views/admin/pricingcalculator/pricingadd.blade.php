@extends('layouts.app')
@Section('content')

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

    .form-check-input {
        transform: scale(1.5);
        /* Increase the size of the checkbox */
    }
</style>
<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">Pricing Calculator</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a href="/pricingcalculator">Pricing</a> > <a
                class="add">Add</a></b>
    </div>

</div>
<div class="row mb-5">
    <div class="col-lg-12">
        <!-- INFORMATION -->
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <!-- FORM -->
            <form class="" id="form_valid" action="{{ route('admin.pricing_insert') }}" method="POST" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <!-- 1.INFORMATION -->
                <h4 class="fw-bold mb-5 px-2">Information</h4>


                <div class="mb-3 px-2">

                    <div class="row gap-2">
                        <!-- Theme and Destination -->
                        <div class="add_form col-md-4 ">
                            <label class="mb-2">Destination</label>
                            <select id="cities_name" name="cities_name"
                                class="form-select py-2 rounded-3 shadow-sm" required>
                                <option value="" disabled selected>Select Destination</option>
                                @foreach($cities as $id => $name)
                                <option value="{{ $name }}" @if(old('cities_name')=='{{ $id }}' ) selected @endif>
                                    {{ $name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="add_form col-md-4">
                            <label class="mb-2">Location</label>
                            <select id="district_name" name="district_name"
                                class="form-select py-2 rounded-3 shadow-sm" required>
                                <option value="" disabled selected>Select Location</option>
                                <!-- Districts will be populated dynamically -->
                            </select>
                        </div>

                    </div>

                    <br>

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
                            <div class="add_formcol-md-4">
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


                    

                    <div class="col-lg-12 text-end mt-5">
                        <a href="{{ route('admin.pricinglist') }}">
                            <button type="button" class="cancel-btn"> Cancel </button>
                        </a>
                        <button class="submit-btn sbmtBtn ms-4 mb-5"> Submit </button>
                    </div>


            </form>

        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize - just hide the sections, don't empty them
        $('#stays-section, #activities-section, #cabs-section').hide();

        $('#cities_name').change(function() {
            const destination = $(this).val();
            const districtSelect = $('#district_name');

            if (!destination) {
                districtSelect.empty().append(
                    '<option value="" disabled selected>Select District</option>'
                ).prop('disabled', true);
                // Hide sections when no destination is selected
                $('#stays-section, #activities-section, #cabs-section').hide();
                return;
            }

            // Show loading state
            districtSelect.empty().append(
                '<option value="" disabled>Loading districts...</option>'
            ).prop('disabled', true);

            $.ajax({
                url: '/get-districts/' + encodeURIComponent(destination),
                type: 'GET',
                success: function(data) {
                    districtSelect.empty().append(
                        '<option value="" disabled selected>Select District</option>'
                    );

                    if (data && data.length > 0) {
                        $.each(data, function(index, district) {
                            districtSelect.append(
                                $('<option>', {
                                    value: district,
                                    text: district
                                })
                            );
                        });
                        districtSelect.prop('disabled', false);
                    } else {
                        districtSelect.append(
                            '<option value="" disabled>No districts found</option>'
                        );
                        // Hide sections when no districts
                        $('#stays-section, #activities-section, #cabs-section').hide();
                    }
                },
                error: function(xhr, status, error) {
                    districtSelect.empty().append(
                        '<option value="" disabled>Error loading districts</option>'
                    );
                    $('#stays-section, #activities-section, #cabs-section').hide();
                }
            });
        });

        $('#district_name').change(function() {
            const destination = $('#cities_name').val();
            const district = $(this).val();

            // Hide all sections initially
            $('#stays-section, #activities-section, #cabs-section').hide();

            if (!destination || !district) {
                return;
            }

            $.ajax({
                url: "{{ route('admin.pricing_details') }}",
                type: 'POST',
                data: {
                    destination: destination,
                    district: district,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    // Process Stays
                    if (data.stays && Object.keys(data.stays).length > 0) {
                        const staysSection = $('#stays-section');
                        const staysDropdown = staysSection.find('.dropdown-menu');
                        const selectedStays = [];

                        staysDropdown.empty();

                        staysSection.css('display', 'block');

                        $.each(data.stays, function(id, title) {
                            staysDropdown.append(`
                                    <li>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input stay-checkbox" 
                                                id="stay-${id}" value="${id}">
                                            <label class="form-check-label" for="stay-${id}">${title}</label>
                                        </div>
                                    </li>
                                `);
                        });

                        staysSection.show();

                        $('.stay-checkbox').change(function() {
                            const stayId = $(this).val();
                            if ($(this).is(':checked')) {
                                if (!selectedStays.includes(stayId)) selectedStays.push(stayId);
                            } else {
                                const index = selectedStays.indexOf(stayId);
                                if (index > -1) selectedStays.splice(index, 1);
                            }
                            $('#stayDropdownText').text(selectedStays.length > 0 ? `${selectedStays.length} selected` : 'Select stays');
                            $('#stayHiddenInput').val(selectedStays.join(','));
                        });

                        staysDropdown.on('click', '.form-check', function(e) {
                            e.stopPropagation();
                        });
                    }

                    // Process Activities
                    if (data.activities && Object.keys(data.activities).length > 0) {
                        const activitiesSection = $('#activities-section');
                        const activitiesDropdown = activitiesSection.find('.dropdown-menu');
                        const selectedActivities = [];

                        activitiesDropdown.empty();

                        $.each(data.activities, function(id, title) {
                            activitiesDropdown.append(`
                                    <li>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input activity-checkbox" 
                                                id="activity-${id}" value="${id}">
                                            <label class="form-check-label" for="activity-${id}">${title}</label>
                                        </div>
                                    </li>
                                `);
                        });

                        activitiesSection.show();

                        $('.activity-checkbox').change(function() {
                            const activityId = $(this).val();
                            if ($(this).is(':checked')) {
                                if (!selectedActivities.includes(activityId)) selectedActivities.push(activityId);
                            } else {
                                const index = selectedActivities.indexOf(activityId);
                                if (index > -1) selectedActivities.splice(index, 1);
                            }
                            $('#activityDropdownText').text(selectedActivities.length > 0 ? `${selectedActivities.length} selected` : 'Select activities');
                            $('#activityHiddenInput').val(selectedActivities.join(','));
                        });

                        activitiesDropdown.on('click', '.form-check', function(e) {
                            e.stopPropagation();
                        });
                    }

                    // Process Cabs
                    if (data.cabs && Object.keys(data.cabs).length > 0) {
                        const cabsSection = $('#cabs-section');
                        const cabsDropdown = cabsSection.find('.dropdown-menu');
                        const selectedCabs = [];

                        cabsDropdown.empty();

                        // Using Object.keys to iterate through the key-value pairs
                        Object.keys(data.cabs).forEach(function(key) {
                            const value = data.cabs[key];
                            cabsDropdown.append(`
                                    <li>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input cab-checkbox cab_details" 
                                                id="cab-${key}" name="${key}" value="${key}" data-text="${value}">
                                            <label class="form-check-label" for="cab-${key}">${value}</label>
                                        </div>
                                    </li>
                                `);
                        });

                        cabsSection.show();

                        $('.cab-checkbox').change(function() {
                            const cabKey = $(this).val();
                            const cabValue = $(this).data('text');

                            if ($(this).is(':checked')) {
                                if (!selectedCabs.some(item => item.key === cabKey)) {
                                    selectedCabs.push({
                                        key: cabKey,
                                        value: cabValue
                                    });
                                }
                            } else {
                                const index = selectedCabs.findIndex(item => item.key === cabKey);
                                if (index > -1) selectedCabs.splice(index, 1);
                            }

                            // Update button text with selected values
                            $('#cabDropdownText').text(
                                selectedCabs.length > 0 ?
                                selectedCabs.map(item => item.value).join(', ') :
                                'Select options'
                            );

                            // Store keys in hidden input
                            $('#cabHiddenInput').val(selectedCabs.map(item => item.key).join(','));
                        });

                        cabsDropdown.on('click', '.form-check', function(e) {
                            e.stopPropagation();
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $('#stays-section, #activities-section, #cabs-section').html('<div class="alert alert-danger">Error loading data</div>').show();
                }
            });
        });

        //stay details - stay-checkbox
        $(document).on('change', '.stay-checkbox', function() {
            const destination = $('#cities_name').val();
            const district = $('#district_name').val();
            const selectedStays = [];

            $('.stay-checkbox:checked').each(function() {
                selectedStays.push($(this).val());
            });

            if (selectedStays.length === 0) {
                $('#stays-details-container').empty();
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

                    data.stays_details.forEach((stayGroup, index) => {
                        const groupTitle = stayGroup[0]?.title || '';
                        const groupHeaderHtml = `
                            <div class="row stay-group-header mb-2">
                                <div class="col-md-12">
                                    <h5>${groupTitle}</h5>
                                </div>
                            </div>
                        `;
                        container.append(groupHeaderHtml);

                        stayGroup.forEach((stay, subIndex) => {
                            const stayHtml = `
                                <div class="row stay-price-row mb-3" data-stay-id="${selectedStays[index]}">
                                    <div class="col-md-4">
                                        <input type="hidden" name="stays[${index}][${subIndex}][stay_id]" value="${stay.stay_id}">
                                        <input type="hidden" name="stays[${index}][${subIndex}][title]" value="${stay.title}">
                                        <!-- Title removed from display (already shown in header) -->
                                    </div>
                                    <div class="col-md-4">
                                        <input type="hidden" name="stays[${index}][${subIndex}][price_title]" value="${stay.price_title}">
                                        <input type="text" class="form-control" value="${stay.price_title}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control price-input" 
                                            name="stays[${index}][${subIndex}][price]" 
                                            value="${stay.price}">
                                    </div>
                                </div>
                            `;
                            container.append(stayHtml);
                        });
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        //activity details
        $(document).on('change', '.activity-checkbox', function() {
            const destination = $('#cities_name').val();
            const district = $('#district_name').val();
            const selectedStays = [];

            $('.activity-checkbox:checked').each(function() {
                selectedStays.push($(this).val());
            });

            if (selectedStays.length === 0) {
                $('#activity-details-container').empty();
                return;
            }

            $.ajax({
                url: "{{ route('admin.activity_details') }}",
                type: 'POST',
                data: {
                    destination: destination,
                    district: district,
                    staydetails: selectedStays,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    const container = $('#activity-details-container');
                    container.empty();

                    data.activity_details.forEach((activityGroup, groupIndex) => {
                        // Add a group header showing the title just once
                        if (activityGroup.length > 0) {
                            const groupHeaderHtml = `
                                <div class="row activity-group-header mb-2">
                                    <div class="col-md-12">
                                        <h5>${activityGroup[0].title}</h5>
                                    </div>
                                </div>
                            `;
                            container.append(groupHeaderHtml);
                        }

                        // Process each activity in the group
                        activityGroup.forEach((activity, itemIndex) => {
                            const activityHtml = `
                                <div class="row activity-price-row mb-3" data-activity-id="${selectedStays[groupIndex]}">
                                    <div class="col-md-4">
                                        <input type="hidden" name="activity[${groupIndex}][${itemIndex}][activity_id]" value="${activity.activity_id}">
                                        <input type="hidden" name="activity[${groupIndex}][${itemIndex}][title]" value="${activity.title}">
                                        <!-- Title removed from display (shown in header) -->
                                    </div>
                                    <div class="col-md-4">
                                        <input type="hidden" name="activity[${groupIndex}][${itemIndex}][price_title]" value="${activity.price_title}">
                                        <input type="text" class="form-control" value="${activity.price_title}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control price-input" 
                                            name="activity[${groupIndex}][${itemIndex}][price]" 
                                            value="${activity.price}">
                                    </div>
                                </div>
                            `;
                            container.append(activityHtml);
                        });
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        // Handle main cab type selection
        $(document).on('change', '.cab-checkbox', function() {
            const destination = $('#cities_name').val();
            const district = $('#district_name').val();
            const selectedCabIds = [];

            $('.cab-checkbox:checked').each(function() {
                selectedCabIds.push($(this).val());
            });

            if (selectedCabIds.length === 0) {
                $('#cabs-details-container, #cabsdetails-container').hide();
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
                    updateCabDetailsDropdown(data.cabs);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $('#cabs-details-container, #cabsdetails-container').hide();
                }
            });
        });

        // Handle cab details selection
        $(document).on('change', '.cab-details-checkbox', function() {
            alert('check');
            const destination = $('#cities_name').val();
            const district = $('#district_name').val();
            const selectedCabDetails = [];

            $('.cab-details-checkbox:checked').each(function() {
                selectedCabDetails.push({
                    id: $(this).val(),
                    text: $(this).data('text')
                });
            });

            if (selectedCabDetails.length === 0) {
                $('#cabsdetails-container').empty().hide();
                return;
            }

            const selectedCabIds = [];
            $('.cab-checkbox:checked').each(function() {
                selectedCabIds.push($(this).val());
            });

            $.ajax({
                url: "{{ route('admin.cabs_details') }}",
                type: 'POST',
                data: {
                    destination: destination,
                    district: district,
                    cabdetails: selectedCabDetails.map(d => d.id),
                    travelmodes: selectedCabIds,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    displayCabDetails(data.activity_details);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $('#cabsdetails-container').empty().hide();
                }
            });
        });

        // Helper function to update cab details dropdown
        function updateCabDetailsDropdown(cabsData) {
            const container = $('#cabs-details-container');
            const dropdownMenu = container.find('.dropdown-menu');
            const dropdownText = container.find('#cabDetailsDropdownText');
            const hiddenInput = container.find('#cabDetailsHiddenInput');

            // Reset previous selections
            dropdownMenu.empty();
            hiddenInput.val('');
            dropdownText.text('Select options');
            $('#cabsdetails-container').empty().hide();

            if (cabsData && Object.keys(cabsData).length > 0) {
                const selectedOptions = []; // Track checked options

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

                // Single unified change handler
                $(document).off('change', '.cab-details-checkbox').on('change', '.cab-details-checkbox', function() {
                    const destination = $('#cities_name').val();
                    const district = $('#district_name').val();
                    const selectedCabDetails = [];
                    const selectedCabIds = [];

                    // Get all checked cab details
                    $('.cab-details-checkbox:checked').each(function() {
                        selectedCabDetails.push({
                            id: $(this).val(),
                            text: $(this).data('text')
                        });
                    });

                    // Get all checked main cab types
                    $('.cab-checkbox:checked').each(function() {
                        selectedCabIds.push($(this).val());
                    });

                    // Update UI immediately
                    dropdownText.text(
                        selectedCabDetails.length > 0 ?
                        selectedCabDetails.map(opt => opt.text).join(', ') :
                        'Select options'
                    );

                    // Update hidden input
                    hiddenInput.val(selectedCabDetails.map(opt => opt.id).join(','));

                    // Only make AJAX call if we have selections
                    if (selectedCabDetails.length > 0) {
                        $.ajax({
                            url: "{{ route('admin.cabs_details') }}",
                            type: 'POST',
                            data: {
                                destination: destination,
                                district: district,
                                cabdetails: selectedCabDetails.map(d => d.id),
                                travelmodes: selectedCabIds,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(data) {
                                displayCabDetails(data.activity_details);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                                $('#cabsdetails-container').empty().hide();
                            }
                        });
                    } else {
                        $('#cabsdetails-container').empty().hide();
                    }
                });

                container.show();
            } else {
                container.hide();
            }
        }

        // Helper function to display cab details (unchanged)
        function displayCabDetails(detailsData) {
            const container = $('#cabsdetails-container');
            container.empty();

            if (detailsData && detailsData.length > 0) {
                detailsData.forEach((cabGroup, groupIndex) => {
                    // Add group header with title (shown once per group)
                    if (cabGroup.length > 0) {
                        container.append(`
                            <div class="row cab-group-header mb-2">
                                <div class="col-md-12">
                                    <h5>${cabGroup[0].title}</h5>
                                </div>
                            </div>
                        `);
                    }

                    // Add each cab detail (without repeating title)
                    cabGroup.forEach((cab, itemIndex) => {
                        container.append(`
                            <div class="row cab-detail-row mb-3">
                                <div class="col-md-4">
                                    <input type="hidden" name="cabs[${groupIndex}][${itemIndex}][cab_id]" value="${cab.cab_id}">
                                    <input type="hidden" name="cabs[${groupIndex}][${itemIndex}][title]" value="${cab.title}">
                                    <!-- Title removed from display (shown in header) -->
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" name="cabs[${groupIndex}][${itemIndex}][price_title]" value="${cab.price_title}">
                                    <input type="text" class="form-control" value="${cab.price_title}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control price-input" 
                                        name="cabs[${groupIndex}][${itemIndex}][price]" 
                                        value="${cab.price}">
                                </div>
                            </div>
                        `);
                    });
                });
                container.show();
            } else {
                container.hide();
            }
        }

    });
</script>
@endsection