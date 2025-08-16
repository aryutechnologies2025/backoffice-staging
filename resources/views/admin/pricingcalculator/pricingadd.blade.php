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

    .form-body {

        padding-top: 1% !important;
        padding-bottom: 1% !important;
        width: 90% !important;
    }

    .mb-1 {
        margin-bottom: .5rem !important;
    }


    .form-control {
        width: 80%;
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

    .form-check-input {
        transform: scale(1.5);
        /* Increase the size of the checkbox */
    }
</style>
<div class="container-wrapper py-5">
    <div class="row">
        <div class="col-lg-12">
            <b><a href="/dashboard">Dashboard</a> > <a href="/pricingcalculator">Pricing</a> > <a
                    class="add">Add</a></b>
            <br>
            <br>
            <h3 class="fw-bold pb-2">Pricing Calculator</h3>
        </div>

        <!-- FORM -->
        <form class="" id="form_valid" action="{{ route('admin.pricing_insert') }}" method="POST" autocomplete="off"
            enctype="multipart/form-data">
            @csrf
            <!-- 1.INFORMATION -->
            <div class="row mb-3">
                <div class="col">
                    <div class="form-body p-4 rounded-4">
                        <h4 class="fw-bold mb-5 px-5 pt-5">Information</h4>


                        <div class="mb-3 px-5">

                            <div class="row gap-2">
                                <!-- Theme and Destination -->
                                <div class="col-md-4 ">
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
                                <div class="col-md-4">
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
                                <div class="col-md-4">
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
                                <div class="col-md-4">
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
                                <div class="col-md-4">
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
                                    <div class="col-md-4">
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
                        </div>
                    </div>



        </form>

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
                        stayGroup.forEach((stay, subIndex) => {
                            const stayHtml = `
                                    <div class="row stay-price-row mb-3" data-stay-id="${selectedStays[index]}">
                                        <div class="col-md-4">
                                         <input type="hidden" name="stays[${index}][${subIndex}][stay_id]" value="${stay.stay_id}">
                                            <input type="hidden" name="stays[${index}][${subIndex}][title]" value="${stay.title}">
                                            <input type="text" class="form-control" value="${stay.title}" readonly>
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

                    data.activity_details.forEach((stayGroup, index) => {
                        stayGroup.forEach((stay, subIndex) => {
                            const stayHtml = `
                        <div class="row stay-price-row mb-3" data-stay-id="${selectedStays[index]}">
                            <div class="col-md-4">
                                <input type="hidden" name="activity[${index}][${subIndex}][activity_id]" value="${stay.activity_id}">
                                <input type="hidden" name="activity[${index}][${subIndex}][title]" value="${stay.title}">
                                <input type="text" class="form-control" value="${stay.title}" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" name="activity[${index}][${subIndex}][price_title]" value="${stay.price_title}">
                                <input type="text" class="form-control" value="${stay.price_title}" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control price-input" 
                                       name="activity[${index}][${subIndex}][price]" 
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
                detailsData.forEach((detailGroup, index) => {
                    detailGroup.forEach((detail, subIndex) => {
                        container.append(`
                            <div class="row cab-detail-row mb-3">
                                <div class="col-md-4">
                                    <input type="hidden" name="cabs[${index}][${subIndex}][cab_id]" value="${detail.cab_id}">
                                    <input type="hidden" name="cabs[${index}][${subIndex}][title]" value="${detail.title}">
                                    <input type="text" class="form-control" value="${detail.title}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" name="cabs[${index}][${subIndex}][price_title]" value="${detail.price_title}">
                                    <input type="text" class="form-control" value="${detail.price_title}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control price-input" 
                                        name="cabs[${index}][${subIndex}][price]" 
                                        value="${detail.price}">
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