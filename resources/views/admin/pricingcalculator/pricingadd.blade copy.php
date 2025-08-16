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
                                    <label class="mb-2">Destination <span class="text-danger">*</span></label>
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
                                    <label class="mb-2">District <span class="text-danger">*</span></label>
                                    <select id="district_name" name="district_name"
                                        class="form-select py-2 rounded-3 shadow-sm" required>
                                        <option value="" disabled selected>Select District</option>
                                        <!-- Districts will be populated dynamically -->
                                    </select>
                                </div>




                                <!-- <div class=""> -->

                                <!-- </div> -->

                            </div>
                            <!-- Stays Section -->
                            <div id="stays-section" class="row d-flex mt-3" style="display: none;">
                            </div>

                            <!-- Activities Section -->
                            <div id="activities-section" class="row d-flex mt-3" style="display: none;">
                            </div>

                            <!-- Cabs Section (if needed) -->
                            <div id="cabs-section" class="row d-flex mt-3" style="display: none;">
                            </div>

                            <div class="row d-flex mt-3">
                                <div class="col-md-4">
                                    <label class="mb-2">Total Amount</label>
                                    <input id="total_amount" type="number" class="form-control" name="total_amount">
                                </div>
                            </div>

                        </div>

                        <br>

                        <div class="row g-2 px-5">
                            <div class="col">
                                <h4> <label class="fw-bold">Status</label></h4>
                                <div class="form-check form-switch d-flex align-items-center">
                                    <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                                </div>
                            </div>
                        </div>

                        <style>
                            .form-check-input {
                                transform: scale(1.5);
                                /* Increase the size of the checkbox */
                            }
                        </style>

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {


            // Initialize total amount
            let totalAmount = 0;

            // Function to calculate total amount
            function calculateTotal() {
                totalAmount = 0;

                // Calculate from stays
                $('#stays-section input[type="checkbox"]:checked').each(function() {
                    const priceText = $(this).next('label').text().split(' - ')[1];
                    const price = parseFloat(priceText) || 0;
                    totalAmount += price;
                });

                // Calculate from activities
                $('#activities-section input[type="checkbox"]:checked').each(function() {
                    const priceText = $(this).next('label').text().split(' - ')[1];
                    const price = parseFloat(priceText) || 0;
                    totalAmount += price;
                });

                // Calculate from cabs
                $('#cabs-section input[type="checkbox"]:checked').each(function() {
                    const priceText = $(this).next('label').text().split(' - ')[1];
                    const price = parseFloat(priceText) || 0;
                    totalAmount += price;
                });

                // Update the total amount field
                $('#total_amount').val(totalAmount.toFixed(2));
            }

            // District change handler
            $('#district_name').change(function() {
                const destination = $('#cities_name').val();
                const district = $(this).val();

                // Clear previous results and reset total
                $('#stays-section, #activities-section, #cabs-section').empty().hide();
                $('#total_amount').val('0.00');
                totalAmount = 0;

                $.ajax({
                    url: "{{ route('admin.pricing_details') }}",
                    type: 'POST',
                    data: {
                        destination: destination,
                        district: district,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        // Process stays
                        if (data.stays && data.stays.length > 0) {
                            const staysSection = $('#stays-section');
                            staysSection.empty().append('<div class="fw-bold">Stays</div>');

                            data.stays.forEach((stayGroup) => {
                                stayGroup.forEach((stay) => {
                                    if (stay.title && stay.price) {
                                        staysSection.append(`
                               
                                    <div class=" col-md-3 d-flex  mt-3">
                                        <input type="checkbox" class="form-check-input stay-checkbox mx-2 pt-3" 
                                            id="stay-${stay.title.replace(/\s+/g, '-')}"
                                            name="stay_items[]"
                                            data-id="${stay.id}"
                                            data-type="stay"
                                            data-title="${stay.title.trim()}"
                                            data-price="${stay.price}">
                                        <label for="stay-${stay.title.replace(/\s+/g, '-')}">
                                            ${stay.title} - ${stay.price}
                                        </label>
                                    </div>
                              
                                `);
                                    }
                                });
                            });
                            staysSection.show();
                        }

                        // Process activities
                        if (data.activities && data.activities.length > 0) {
                            const activitiesSection = $('#activities-section');
                            activitiesSection.empty().append('<p class="fw-bold">Activities</p>');

                            data.activities.forEach((activityGroup) => {
                                activityGroup.forEach((activity) => {
                                    if (activity.title && activity.price) {
                                        activitiesSection.append(`
                                        <div class=" col-md-3 d-flex  mt-3">
                                            <input type="checkbox" class="form-check-input activity-checkbox mx-2 pt-3" 
                                                id="activity-${activity.title.replace(/\s+/g, '-')}"
                                                    name="activity_items[]"
                                                    data-type="activity"
                                                    data-id="${activity.id}"
                                                    data-title="${activity.title.trim()}"
                                                    data-price="${activity.price}">
                                            <label for="activity-${activity.title.replace(/\s+/g, '-')}">
                                                ${activity.title} - ${activity.price}
                                            </label>
                                        </div>
                                `);
                                    }
                                });
                            });
                            activitiesSection.show();
                        }

                        // Process cabs
                        if (data.cabs && data.cabs.length > 0) {
                            const cabsSection = $('#cabs-section');
                            cabsSection.empty().append('<p class="fw-bold">Cabs</p>');

                            data.cabs.forEach((cabGroup) => {
                                cabGroup.forEach((cab) => {
                                    if (cab.title && cab.price) {
                                        cabsSection.append(`
                                            <div class="col-md-3 d-flex mt-3">
                                                <input type="checkbox" class="form-check-input cab-checkbox mx-2 pt-3" 
                                                    id="cab-${cab.title.replace(/\s+/g, '-')}"
                                                    name="cab_items[]"
                                                    data-type="cab"
                                                    data-id="${cab.id}"
                                                    data-title="${cab.title.trim()}"
                                                    data-price="${cab.price}">
                                                <label for="cab-${cab.title.replace(/\s+/g, '-')}">
                                                    ${cab.title} - ${cab.price}
                                                </label>

                                            </div>
                                        `);
                                    }
                                });
                            });
                            cabsSection.show();
                        }

                        // Set up event listeners for new checkboxes
                        $('.stay-checkbox, .activity-checkbox, .cab-checkbox').change(function() {
                            calculateTotal();
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            });

            // Initialize with 0.00
            $('#total_amount').val('0.00');

            $('#cities_name').change(function() {
                const destination = $(this).val();
                const districtSelect = $('#district_name');

                console.log('Destination selected:', destination); // Debugging

                if (!destination) {
                    districtSelect.empty().append(
                        '<option value="" disabled selected>Select District</option>'
                    ).prop('disabled', true);
                    return;
                }

                // Show loading state
                districtSelect.empty().append(
                    '<option value="" disabled>Loading districts...</option>'
                ).prop('disabled', true);

                // AJAX request
                $.ajax({
                    url: '/get-districts/' + encodeURIComponent(destination),
                    type: 'GET',
                    success: function(data) {
                        console.log('Received data:', data); // Debugging

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
                                '<option value="" disabled>No districts found for this destination</option>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error); // Debugging
                        districtSelect.empty().append(
                            '<option value="" disabled>Error loading districts</option>'
                        );
                    }
                });
            });

            $('#form_valid').submit(function(e) {
                e.preventDefault();

                const selectedItems = [];

                // Collect all checked items
                $('input[type="checkbox"]:checked').each(function() {
                    selectedItems.push({
                        id: $(this).data('id'),
                        type: $(this).data('type'),
                        title: $(this).data('title'),
                        price: $(this).data('price')
                    });
                });

                // Validate at least one item is selected
                if (selectedItems.length === 0) {
                    alert('Please select at least one item');
                    return false;
                }

                // Add selected items to form
                $('<input>').attr({
                    type: 'hidden',
                    name: 'selected_items',
                    value: JSON.stringify(selectedItems)
                }).appendTo('#form_valid');

                // Submit the form
                this.submit();
            });


            // if ($('#auto-dismiss-error').length) {
            //     // Hide the error after 5 seconds with fade out effect
            //     setTimeout(function() {
            //         $('#auto-dismiss-error').fadeOut(500, function() {
            //             $(this).remove();
            //         });
            //     }, 10000); // 5000ms = 5 seconds

            //     // Optional: Add close button functionality
            //     $('#auto-dismiss-error').prepend(
            //         '<button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>'
            //     );
            // }


        });
    </script>
    @endsection