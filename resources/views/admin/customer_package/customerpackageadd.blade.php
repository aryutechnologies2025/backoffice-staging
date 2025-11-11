@extends('layouts.app')
@section('content')
<!-- Bootstrap CSS --><!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    .multiselect-container>li>a>label {
        padding: 6px 15px;
        font-size: 14px;
    }

    .multiselect.dropdown-toggle {
        background-color: #f8f9fa;
        border: 1px solid #ccc;
        color: #333;
        border-radius: 6px;
    }

    .multiselect-container {
        max-height: 250px;
        overflow-y: auto;
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

    .add_head {
        color: #3B71CA;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }

    .btn-add {
        background-color: #3B71CA;
        transition: all 0.3s;
    }

    .btn-add:hover {
        background-color: #2b5da3;
        transform: translateY(-2px);
    }

    .remove-day {
        height: fit-content;
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

    .editor-toolbar {
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        border-bottom: none;
        border-radius: 8px 8px 0 0;
        padding: 5px;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .editor-content {
        min-height: 120px;
        border: 1px solid #ced4da;
        border-radius: 0 0 8px 8px;
        padding: 10px;
        margin-bottom: 15px;
    }

    .editor-content:focus {
        outline: none;
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .toolbar-btn {
        background: none;
        border: none;
        padding: 5px 8px;
        border-radius: 4px;
        cursor: pointer;
    }

    .toolbar-btn:hover {
        background-color: #e9ecef;
    }

    .day-block {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        border-left: 4px solid #3B71CA;
    }

    .rte-container {
        margin-top: 10px;
    }

    .editor-content img {
        max-width: 100%;
        height: auto;
    }

    .editor-content ul {
        list-style-type: disc !important;
        /* make sure it's bullet */
        padding-left: 20px;
        /* add spacing so bullets are visible */
        color: #000;
        /* bullet + text color */
    }

    /* Ordered list style */
    .editor-content ol {
        list-style-type: decimal !important;
        padding-left: 20px;
        color: #000;
    }
</style>
<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{ $title }}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a class="" href="/customer-package">Customer Package</a></b> > <a class="enquiry"
            href="">Add Customer</a>
    </div>

</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="row mb-5">
    <div class="col-lg-12">
        <!-- INFORMATION -->
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form class="bg-white p-1 rounded-3" action="{{ route('admin.CustomerPackage_insert') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="row d-flex gap-4">
                    <div class="add_form col-md-5 mb-1">
                        <label for="name" class="form-label">Full Name</label>
                        <input id="name" type="text" class="form-control" name="name" required>
                    </div>

                    <div class="add_form col-md-5 mb-1">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input id="phone" type="number" class="form-control" name="phone_number" required>
                    </div>

                    <div class="add_form col-md-5 mb-1">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" class="form-control" name="email" required>
                    </div>

                    <div class="add_form col-md-5 mb-1">
                        <label for="city_select" class="form-label">Select Destination</label>
                        <select id="city_select" class="form-control" name="city_select[]" multiple required>
                            @foreach ($cities as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="add_form">
                        <div class="add_form col-md-5 mb-1">
                            <label for="package_select" class="form-label">Select Package</label>
                            <select id="package_select" class="form-control">
                                <option disabled selected>Select Package</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-1">
                            <label for="package_select_stay" class="form-label">Select Stay</label>
                            <select id="package_select_stay" name="package_select_stay" class="form-control">
                                <option value="" selected disabled>Select Stay</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="package_id" id="hidden_package_id">


                    <!-- old -->





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
                                    <button
                                        class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
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
                                    <button
                                        class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
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
                                    <button
                                        class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
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
                        </div>



                        <!-- PRICE SUMMARY SECTION -->
                        <div class="price-summary mt-4">
                            <h5 class="mb-3">Price Breakdown</h5>

                            <div class="price-row">
                                <span>Package Pricing:</span>
                                <span id="selected-amount">₹ 0</span>
                                <input type="hidden" id="selected-price-id" name="selected_price_id">
                            </div>
                            <div class="price-row">
                                <span>Selected Value:</span>
                                <span id="selected-value">₹ 0</span>
                            </div>
                            {{-- <div class="price-row">
                                    <span>Service Fee:</span>
                                    <span id="service-fee">₹ 0</span>
                                </div> --}}
                            <div class="price-row">
                                <span>Service Fee:</span>
                                <input type="number" id="service-fee-input" value="0" min="0"
                                    step="0.01" />

                            </div>
                            <div class="price-row">
                                <span>Tax (GST %):</span>
                                <input type="number" name="gst_number" id="gst-input" value="0" min="0" step="0.01">
                                %
                            </div>
                            <div class="price-row">
                                <span>Tax Amount (₹):</span>
                                <span id="tax-amount">₹ 0</span>
                            </div>
                            <input type="hidden" name="tax_amount" id="hidden-tax-amount" value="0">

                            <div class="price-row price-total">
                                <span>Total Amount:</span>
                                <span id="total-amount">₹ 0</span>
                            </div>
                            <div class="grand-total text-center">
                                <span>Grand Total: </span>
                                <span id="grand-total">₹ 0</span>
                            </div>
                        </div>



                        <!-- Hidden inputs for form submission -->
                        <input type="hidden" name="package_pricing_value" id="package_pricing_value" value="0">
                        <input type="hidden" name="selected_value" id="hidden-selected-value" value="0">
                        <input type="hidden" name="service_fee" id="hidden-service-fee" value="0">
                        <input type="hidden" name="tax_amount" id="hidden-tax-amount" value="0">
                        <input type="hidden" name="total_amount" id="hidden-total-amount" value="0">
                        <input type="hidden" name="grand_total" id="hidden-grand-total" value="0">
                    </div>



                    <div class="test">
                        <h2 class="add_head mt-3">Package Details</h2>
                        <!-- 1.INFORMATION -->
                        <div class="row mb-3">
                            <div class="add_form col-lg-5">
                                <label class="fw-bold mb-2">Title </label>
                                <input type="text" placeholder="Title" id="title" name="title"
                                    class="form-control py-2 rounded-3 shadow-sm">
                            </div>



                            <!-- 2. LOCATION -->

                            <div class="row mb-1">
                                <div class="col-lg-12">
                                    <!-- INFORMATION -->
                                    <div class="form-body mb-2 mt-4 rounded-4">
                                        <h4 class="add_head fw-bold mb-3">01. Location</h4>
                                        <div class="mb-1">
                                            <div class="row g-2 mb-1">
                                                <div class="col">
                                                    <input type="hidden" id="location" name="location">
                                                    <div class=" mt-1">
                                                        <div class="row">
                                                            <div class="col-lg-12 ">
                                                                <div id="summernote10" style="height: 200px;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="row mb-3">
                                <div class="col">
                                    <div class="form-body rounded-4">
                                        <h4 class="add_head fw-bold mb-2">
                                            02. Tour Planning <span class="text-danger">*</span>
                                        </h4>
                                        <div id="day-wrapper"></div>

                                    </div>
                                </div>
                            </div>



                            <!-- 5.PRICING -->
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="form-body rounded-4">
                                        <h4 class="add_head fw-bold mb-2">03. Pricing</h4>
                                        <div id="price-fields-container" class="mb-2">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 6. Payment Policy -->
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="form-body rounded-4">
                                        <h4 class="add_head fw-bold mb-2">04. Payment Policy</h4>
                                        <div id="camp-rule-container">

                                        </div>
                                        <div class="text-end">
                                            <button type="button"
                                                class="btn-add rounded border-0 px-5 py-2 text-white"
                                                onclick="addCampRuleField()">
                                                <i class="fa fa-plus" aria-hidden="true"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- 7.Important info -->
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="form-body rounded-4">
                                        <h4 class="add_head fw-bold mb-2">05. Notes <span class="text-danger">*</span>
                                        </h4>
                                        <div class="mb-1">
                                            <div class="row g-2 mb-1">
                                                <div class="col">
                                                    <input type="hidden" id="important_info" name="important_info">
                                                    <div class=" mt-1">
                                                        <div class="row">
                                                            <div class="col-lg-12 ">
                                                                <div id="summernote4" style="height: 200px;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col">
                                    <div class="form-body rounded-4">
                                        <h4 class="add_head fw-bold mb-2">06. Package Inclusion </h4>
                                        <div class="mb-2">
                                            <div class="row g-2 mb-2">
                                                <div class="col">
                                                    <input type="hidden" id="program_inclusion"
                                                        name="program_inclusion">

                                                    <div class=" mt-2">
                                                        <div class="row">
                                                            <div class="col-lg-12 ">
                                                                <div id="summernote5" style="height: 200px;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col">
                                    <div class="form-body rounded-4">
                                        <h4 class="add_head fw-bold mb-2">07. Package Exclusion </h4>
                                        <div class="mb-2">
                                            <div class="row g-2 mb-2">
                                                <div class="col">
                                                    <input type="hidden" id="program_exclusion"
                                                        name="program_exclusion">
                                                    <div class=" mt-2">
                                                        <div class="row">
                                                            <div class="col-lg-12 ">
                                                                <div id="summernote9" style="height: 200px;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <script>
                                document.getElementById('google_map').addEventListener('input', function() {
                                    const inputValue = this.value;
                                    const iframeSrcMatch = inputValue.match(/src=["']([^"']+)["']/); // Extract the src attribute value
                                    const mapPreviewIframe = document.getElementById('map_preview');

                                    if (iframeSrcMatch && iframeSrcMatch[1]) {
                                        mapPreviewIframe.src = iframeSrcMatch[1]; // Set the extracted src to the iframe
                                    } else {
                                        mapPreviewIframe.removeAttribute('src'); // Clear the iframe if input is invalid
                                    }
                                });
                            </script>

                            <!-- 8. AMENITIES -->
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="form-body rounded-4">
                                        <h4 class="add_head fw-bold mb-2">08. Amenities</h4>
                                        <div class="d-flex flex-wrap">
                                            @foreach ($amenities as $index => $amenity)
                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                                                <div class="form-check d-flex align-items-center">
                                                    <input type="checkbox" class="me-2 custom-checkbox"
                                                        id="amenity-{{ $amenity->id }}" name="amenity_services[]"
                                                        value="{{ $amenity->id }}">
                                                    <label for="amenity-{{ $amenity->id }}"
                                                        class="mb-0">{{ $amenity->amenity_name }}</label>
                                                </div>
                                            </div>
                                            @if (($index + 1) % 4 == 0)
                                            <div class="w-100"></div>
                                            <!-- Forces a line break after every 4 items -->
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 9. FOOD & BEVERAGES -->
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="form-body rounded-4">
                                        <h4 class="add_head fw-bold mb-2">09. Food and Beverages</h4>
                                        <div class="d-flex flex-wrap">
                                            @foreach ($foodBeverages as $index => $item)
                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                                                <div class="form-check d-flex align-items-center">
                                                    <input type="checkbox" class="me-2 custom-checkbox"
                                                        id="food-beverage-{{ $item->id }}"
                                                        name="food_beverages[]" value="{{ $item->id }}">
                                                    <label for="food-beverage-{{ $item->id }}"
                                                        class="mb-0">{{ $item->food_beverage }}</label>
                                                </div>
                                            </div>
                                            @if (($index + 1) % 4 == 0)
                                            <div class="w-100"></div>
                                            <!-- Forces a line break after every 4 items -->
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 10. ACTIVITIES -->
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="form-body rounded-4">
                                        <h4 class="add_head fw-bold mb-3">10. Activities</h4>
                                        <div class="d-flex flex-wrap">
                                            @foreach ($activities as $index => $item)
                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                                                <div class="form-check d-flex align-items-center ">
                                                    <input type="checkbox" class="me-2 custom-checkbox"
                                                        id="activities-{{ $item->id }}" name="activities[]"
                                                        value="{{ $item->id }}">
                                                    <label for="activities-{{ $item->id }}"
                                                        class="mb-0">{{ $item->activities }}</label>
                                                </div>
                                            </div>
                                            @if (($index + 1) % 4 == 0)
                                            <div class="w-100"></div>
                                            <!-- Forces a line break after every 4 items -->
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 11. SAFETY FEATURES -->
                            <div class="row mb-1">
                                <div class="col">
                                    <div class="form-body rounded-4">
                                        <h4 class="add_head fw-bold mb-3">11. Safety Features</h4>
                                        <div class="d-flex flex-wrap">
                                            @foreach ($safety_features as $index => $item)
                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                                                <div class="form-check d-flex align-items-center mb-1">
                                                    <input type="checkbox" class="me-2 custom-checkbox"
                                                        id="safety_features-{{ $item->id }}"
                                                        name="safety_features[]" value="{{ $item->id }}">
                                                    <label for="safety_features-{{ $item->id }}"
                                                        class="mb-0">{{ $item->safety_features }}</label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add custom CSS -->
                            <style>
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

                            <br>

                            <div class="row g-2">
                                <div class="add_form col">
                                    <h4> <label class="fw-bold">Status</label></h4>
                                    <div class="form-check form-switch d-flex align-items-center">
                                        <input class="form-check-input check_bx" type="checkbox" id="status"
                                            name="status">
                                    </div>
                                </div>
                            </div>


                            <div class="row g-3">
                                <div class="add_form col-lg-4">
                                    <label class="fw-bold mb-3 ">Order</label>
                                    <input type="number" placeholder="Order" id="list_order" name="list_order"
                                        value="{{ old('order') }}" class="form-control py-2 rounded-3 shadow-sm">
                                </div>
                            </div>

                            <div class="col-lg-12 text-center mt-5">
                                <a href="{{ route('admin.inclusive_package_list') }}">
                                    <button type="button" class="cancel-btn"> Cancel </button>
                                </a>
                                <button class="submit-btn sbmtBtn ms-4 mb-5"> Submit </button>
                            </div>
                        </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#summernote1,#summernote2,#summernote3,#summernote4,#summernote5,#summernote6,#summernote7,#summernote8,#summernote9,#summernote10')
            .summernote({
                height: 200 // Set the height of the editor
            });
        $('#summernote1').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $('#summernote2').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernote3').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernote4').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernote5').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernote9').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernote6').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernote7').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernote8').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernote10').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });


        let photoCount = 1; // Start with existing photo field count

        // Function to generate new photo upload field HTML
        function createPhotoUploadField(count) {
            return `
                <div class="col-lg-2 photo-upload-field">
                    <div class="form-input">
                        <label for="file-ip-${count}" class="px-4 py-3 text-center">
                            <img class="text-center mt-3" id="file-ip-${count}-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg">
                            <p class="text-center fw-light mt-3"> Add Pic</p>
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
        function showPreview(event, number) {
            var file = event.target.files[0];
            var reader = new FileReader();
            var previewId = "#file-ip-" + number + "-preview";
            var errorMessageId = "#file-ip-" + number + "-error";

            reader.onload = function(e) {
                $(previewId).attr('src', e.target.result);
                $(errorMessageId).text(''); // Clear any previous error message
            };

            if (file) {
                if (file.size <= 2 * 1024 * 1024) { // 2 MB limit
                    if (file.type === 'image/png' || file.type === 'image/jpeg') {
                        reader.readAsDataURL(file);
                    } else {
                        $(errorMessageId).text('Please upload a valid PNG or JPEG image.');
                    }
                } else {
                    $(errorMessageId).text('File size exceeds 2 MB limit.');
                }
            }
        }

        // Delegate event binding for dynamically added file inputs
        $('#photo-upload-container').on('change', 'input[type="file"]', function(event) {
            var number = $(this).data('number'); // Use data attribute to get the number
            showPreview(event, number);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        let planCount = 1; // Initialize with existing plan count

        // Function to clone the existing plan item
        function createPlanFields() {
            planCount++; // Increment plan count
            const container = document.getElementById('plan-container');
            const template = container.querySelector('.plan-item');

            // Clone the template
            const newPlan = template.cloneNode(true);

            // Update IDs and Names for new plan items
            newPlan.querySelectorAll('input, textarea, div').forEach((field) => {
                if (field.id) {
                    field.id = field.id + '-' + planCount;
                }
                if (field.name) {
                    field.name = field.name.replace(/\[\]$/, '[]');
                }
            });

            // Remove previous Summernote instance (if any)
            const summernoteDiv = newPlan.querySelector('[id^="summernote3"]');
            const hiddenInput = newPlan.querySelector('[id^="plan_description"]');

            // Give unique IDs
            const uniqueEditorId = `summernote3-${planCount}`;
            const uniqueInputId = `plan_description-${planCount}`;

            summernoteDiv.id = uniqueEditorId;
            hiddenInput.id = uniqueInputId;

            // Append the cloned item
            container.appendChild(newPlan);

            // Initialize Summernote for the new div
            $('#' + uniqueEditorId).summernote({
                height: 200,
                callbacks: {
                    onChange: function(contents) {
                        // Sync to hidden input on change
                        document.getElementById(uniqueInputId).value = contents;
                    }
                }
            });
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

    function addCampRuleField() {
        // Find the container where new fields will be added
        var container = document.getElementById('camp-rule-container');

        // Create a new div for the new field
        var newField = document.createElement('div');
        newField.className = 'row g-2 mb-4 camp-rule-field';
        newField.innerHTML = `<div class="col">
                <input type="text" name="camp_rule[]" class="form-control py-3 rounded-3 shadow-sm" placeholder="Payment Policy" >
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

    // total duration
    document.addEventListener('DOMContentLoaded', () => {
        const startDateInput = document.getElementById('start_date');
        const returnDateInput = document.getElementById('return_date');
        const totalDaysInput = document.getElementById('total_days');

        function calculateDays() {
            const startDate = new Date(startDateInput.value);
            const returnDate = new Date(returnDateInput.value);
            if (startDate && returnDate && returnDate >= startDate) {
                const diffTime = Math.abs(returnDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                totalDaysInput.value = diffDays;
            } else {
                totalDaysInput.value = '';
            }
        }

        startDateInput.addEventListener('change', calculateDays);
        returnDateInput.addEventListener('change', calculateDays);
    });
    $('#city_select').select2({
        placeholder: "Select Destination"
    });

    $('#package_select').select2({
        placeholder: "Select Package"
    });

    // Initialize multiselect
    $(document).ready(function() {
        $('#city_select').select2({
            placeholder: "Select a city",
            allowClear: true
        });
    });
    $('#package_select').select2({
        placeholder: "Select Package",
        allowClear: true,
        width: '100%'
    });
    // 🔹 When city changes → fetch packages
    $('#city_select').on('change', function() {
        const city_ids = $(this).val();

        if (city_ids && city_ids.length > 0) {
            // Fetch packages (optional)
            fetchPackages(city_ids);

            // Fetch stays (directly after selecting city)
            fetchStays(city_ids);
        } else {
            $('#package_select').empty().append('<option disabled selected>No package selected</option>');
            $('#package_select_stay').empty().append('<option disabled selected>No stay selected</option>');
        }
    });

    // ✅ Fetch packages (you already have this)
    function fetchPackages(city_ids) {
        $('#package_select').empty().append('<option disabled selected>Loading packages...</option>');

        $.ajax({
            url: '/customer-package/get-packages-by-city',
            type: 'POST',
            data: {
                city_id: city_ids
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                const packageSelect = $('#package_select');
                packageSelect.empty().append('<option disabled selected>Select Package</option>');

                if (response.length > 0) {
                    response.forEach(pkg => {
                        packageSelect.append(
                            $('<option></option>').val(pkg.id).text(pkg.title)
                        );
                    });
                } else {
                    packageSelect.append('<option disabled>No packages found</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching packages:', error);
                console.error(xhr.responseText);
            }
        });
    }

    // Initialize select2 for package_select_stay with textarea-like appearance
    $('#package_select_stay').select2({
        placeholder: "Select Stay",
        allowClear: true,
        width: '100%',
        // Make it look more like a textarea
        minimumResultsForSearch: Infinity, // Hide search for cleaner look
        dropdownCssClass: 'textarea-like-dropdown'
    });


    // ✅ New function to fetch stays based on city selection
    function fetchStays(city_ids) {
        $('#package_select_stay').empty().append('<option disabled selected>Loading stays...</option>');

        $.ajax({
            url: '/customer-package/packageStay-details',
            type: 'POST',
            data: {
                city_ids: city_ids
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                try {
                    response = typeof response === 'string' ? JSON.parse(response) : response;

                    const staySelect = $('#package_select_stay');

                    // Clear all old options first
                    staySelect.find('option').remove();

                    // Add placeholder only once
                    staySelect.append(
                        $('<option disabled selected></option>').text('Select Stay')
                    );

                    // Then add your dynamic options
                    if (response.cities_details && Array.isArray(response.cities_details)) {
                        response.cities_details.forEach(stay => {
                            if (stay && stay.id && stay.stay_title) {
                                staySelect.append(
                                    $('<option></option>')
                                    .val(stay.id)
                                    .text(stay.stay_title)
                                );
                            }
                        });
                    } else {
                        staySelect.append('<option disabled>No stay details found</option>');
                    }
                } catch (e) {
                    console.error('Error parsing stay response:', e);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching stays:', error);
                console.error(xhr.responseText);
            }
        });
    }
    // When a package is selected, fetch package details (stays)
    $('#package_select').change(function() {
        const city_ids = $('#city_select').val();
        var package_id = $(this).val();
        $('#hidden_package_id').val(package_id);
        $.ajax({
            url: '/customer-package/package-details',
            type: 'POST',
            data: {
                id: package_id,
                city_ids: city_ids
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                try {
                    response = typeof response === 'string' ? JSON.parse(response) : response;
                    const packageStaySelect = $('#package_stay');

                    packageStaySelect.empty().append(
                        $('<option></option>')
                        .val('')
                        .text('Select Stay')
                        .prop('disabled', true)
                        .prop('selected', true)
                    );

                    if (response && response.cities_details && Array.isArray(response
                            .cities_details)) {
                        const validStays = response.cities_details.filter(stay =>
                            stay && stay.id !== undefined && stay.stay_title
                        );

                        if (validStays.length > 0) {
                            validStays.forEach(stay => {
                                packageStaySelect.append(
                                    $('<option></option>').val(stay.id).text(stay
                                        .stay_title)
                                );
                            });
                            packageStaySelect.prop('disabled', false);
                        } else {
                            packageStaySelect.append('<option>No available stays</option>');
                        }
                    } else {
                        // Invalid response structure
                        packageStaySelect.append(
                            $('<option></option>')
                            .val('')
                            .text('Invalid data format')
                        );
                        packageStaySelect.prop('disabled', true);
                        console.error('Invalid response structure:', response);
                    }

                    // Handle tour planning and location
                    if (response.package_details) {
                        var tourVal = typeof response.package_details.tour_planning === 'string' ?
                            JSON.parse(response.package_details.tour_planning) :
                            response.package_details.tour_planning;

                        // Clear wrapper first
                        // Clear wrapper first
                        $('#day-wrapper').html('');

                        // Render existing tour plans
                        tourVal.forEach(function(day, i) {
                            addTourDayBlock(day, i);
                        });

                        // Add More button below the wrapper (if not already there)
                        if (!$('#add-more-day').length) {
                            $('#day-wrapper').after(`
        <div class="text-end mt-3">
            <button type="button" id="add-more-day" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm">
                <i class="fa fa-plus"></i> Add More
            </button>
        </div>
    `);
                        }

                        // Handle Add More click
                        $(document).on('click', '#add-more-day', function() {
                            const index = $('.day-block').length;
                            addTourDayBlock({
                                title: '',
                                subtitle: '',
                                description: ''
                            }, index);
                        });

                        // Function to create one day block
                        function addTourDayBlock(day, i) {
                            const wrapper = document.getElementById('day-wrapper');
                            const div = document.createElement('div');
                            div.classList.add('row', 'g-2', 'mb-2', 'day-block');

                            div.innerHTML = `
        <div class="col-md-5 mb-2">
            <input type="text" name="tour_planning[${i}][title]" 
                class="form-control py-2 rounded-3 shadow-sm" 
                value="${day.title || ''}" 
                placeholder="Day Title (e.g., Day ${i + 1})">
        </div>
        <div class="col-md-5 mb-2">
            <input type="text" name="tour_planning[${i}][subtitle]" 
                class="form-control py-2 rounded-3 shadow-sm" 
                value="${day.subtitle || ''}" 
                placeholder="Activity Subtitle">
        </div>
        <div class="col-md-10 mb-2">
            <label class="form-label fw-bold">Activity Description</label>
            <div class="rte-container">
                <div class="editor-toolbar">
                    <button type="button" class="toolbar-btn" data-command="bold"><i class="fas fa-bold"></i></button>
                    <button type="button" class="toolbar-btn" data-command="italic"><i class="fas fa-italic"></i></button>
                    <button type="button" class="toolbar-btn" data-command="underline"><i class="fas fa-underline"></i></button>
                    <button type="button" class="toolbar-btn" data-command="insertUnorderedList"><i class="fas fa-list-ul"></i></button>
                    <button type="button" class="toolbar-btn" data-command="insertOrderedList"><i class="fas fa-list-ol"></i></button>
                    <button type="button" class="toolbar-btn" data-command="createLink"><i class="fas fa-link"></i></button>
                </div>
                <div class="editor-content" contenteditable="true" id="editor-${i}"></div>
                <input type="hidden" name="tour_planning[${i}][description]" 
                    class="tour-description-hidden">
            </div>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            ${i > 0 ? `
                <button type="button" class="btn btn-danger remove-day" onclick="removeDay(this)">
                    <i class="fa fa-trash"></i>
                </button>` : ''
            }
        </div>
    `;

                            wrapper.appendChild(div);

                            // Initialize custom RTE
                            const container = div.querySelector(".rte-container");
                            const editor = container.querySelector(".editor-content");
                            const hiddenInput = container.querySelector(".tour-description-hidden");
                            const toolbar = container.querySelector(".editor-toolbar");

                            // Set existing description
                            editor.innerHTML = day.description || "";
                            hiddenInput.value = day.description || "";

                            // Sync hidden input when typing
                            editor.addEventListener("input", () => {
                                hiddenInput.value = editor.innerHTML;
                            });

                            // Toolbar buttons
                            toolbar.addEventListener("click", e => {
                                const btn = e.target.closest("button");
                                if (!btn) return;
                                const command = btn.dataset.command;

                                if (command === "createLink") {
                                    const url = prompt("Enter the link URL:");
                                    if (url) {
                                        document.execCommand(command, false, url);
                                    }
                                } else {
                                    document.execCommand(command, false, null);
                                }

                                editor.focus();
                                hiddenInput.value = editor.innerHTML;
                            });
                        }

                        // Remove day function
                        function removeDay(button) {
                            $(button).closest('.day-block').remove();

                            // Reindex all day fields after removal
                            $('.day-block').each(function(index, block) {
                                $(block).find('input, textarea, [contenteditable]').each(function() {
                                    const name = $(this).attr('name');
                                    if (name) {
                                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                                        $(this).attr('name', newName);
                                    }
                                });
                            });
                        }



                        if (response.package_details.location) {
                            $('#title').val(response.package_details.title)
                            $('#summernote10').summernote('code', response.package_details
                                .location);

                            $('#summernote3').summernote('code', tourVal);
                        }

                        // Handle price fields
                        var priceAmount = response.package_details.price_amount ?
                            JSON.parse(response.package_details.price_amount) : [];

                        var priceTitle = response.package_details.price_tilte ?
                            JSON.parse(response.package_details.price_tilte) : [];


                        var count = Math.max(4, priceAmount.length, priceTitle.length);
                        var priceContainer = $('#price-fields-container');
                        priceContainer.empty();

                        // Generate fields
                        for (var i = 0; i < count; i++) {
                            var fieldHtml = `
                                    <div class="row mb-2 align-items-center price-row" data-index="${i}">
                                        <div class="col-lg-1 text-center">
                                            <!-- Selector radio button -->
                                            <input type="radio" name="selected_price" class="form-check-input mt-0" value="${i}">
                                        </div>
                                        <div class="col-lg-5">
                                            <label class="form-label fw-bold mb-2">Title</label>
                                            <input type="text" name="price_title[]" class="form-control py-2 rounded-3 shadow-sm"
                                                placeholder="Title"
                                                value="${priceTitle[i] || ''}">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="fw-bold mb-2">Amount <span class="text-danger">*</span></label>
                                            <div class="position-relative">
                                                <span class="position-absolute top-50 start-0 translate-middle-y ps-3">₹</span>
                                                <input type="number" name="price_amount[]" class="form-control py-2 ps-5 rounded-3 shadow-sm price-amount"
                                                    placeholder="Actual Amount"
                                                    value="${priceAmount[i] || ''}">
                                            </div>
                                        </div>
                                    </div>`;
                            priceContainer.append(fieldHtml);
                        }

                        // When a radio button is selected
                        // let selectedPriceAmount = 0;

                        // $('#price-fields-container').on('change', 'input[name="selected_price"]', function() {
                        //     var selectedRow = $(this).closest('.row');
                        //     var title = selectedRow.find('input[name="price_title[]"]').val();
                        //     selectedPriceAmount = parseFloat(selectedRow.find('.price-amount').val()) || 0;

                        //     $('#selected-amount').text(selectedPriceAmount.toLocaleString() || '0');

                        //     console.log('Selected Title:', title);
                        //     console.log('Corresponding Amount:', selectedPriceAmount);


                        // });



                        //camp rule
                        var campRuleContainer = $('#camp-rule-container');
                        var camp_rule = JSON.parse(response.package_details.camp_rule) ?? [''];

                        campRuleContainer.empty();
                        for (var i = 0; i <= camp_rule.length - 1; i++) {
                            var camp_ruleHTML = ` <div class="row g-2 mb-1 align-items-center camp-rule-field">
                                        <label class="mb-1">Payment Policy <span
                                                class="text-danger">*</span></label>

                                        <div class="col-md-11">
                                            <input type="text" name="camp_rule[]" id="camp_rule"
                                                class="form-control py-2 rounded-3 shadow-sm"
                                                placeholder="Payment Policy" value="${camp_rule[i] || ''}">
                                        </div>
                                         <div class="col-lg-1 mt-2 text-end">
                                                <a class="table-link danger remove-plan"
                                                    onclick="removeField(this)">
                                                    <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
                                                        <i
                                                            class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                </a>
                                            </div>
                                    </div>`

                            campRuleContainer.append(camp_ruleHTML);

                        }
                        //notes

                        // var notes_val = typeof response.package_details.important_info === 'string' ?
                        //     JSON.parse(response.package_details.important_info) :
                        //     response.package_details.important_info;
                        $('#summernote4').summernote('code', response.package_details
                            .important_info); // notes
                        $('#summernote5').summernote('code', response.package_details
                            .program_inclusion); // program Inclusion
                        $('#summernote9').summernote('code', response.package_details
                            .program_exclusion); // program Exclusion


                        //aminites
                        if (response.selectedAmenities) {
                            // Convert array to object for faster lookup
                            const selectedAmenities = {};
                            $.each(response.selectedAmenities, function(index, id) {
                                selectedAmenities[id] = true;
                            });

                            // Loop through all checkboxes just once
                            $('input[name="amenity_services[]"]').each(function() {
                                $(this).prop('checked', selectedAmenities[$(this).val()] ||
                                    false);
                            });
                        }
                        //food beverages
                        if (response.selectedfood_beverages) {
                            const selectedFoodBeverages = {};
                            $.each(response.selectedfood_beverages, function(index, id) {
                                selectedFoodBeverages[id] = true;
                            });
                            $('input[name="food_beverages[]"]').each(function() {
                                $(this).prop('checked', selectedFoodBeverages[$(this)
                                    .val()] || false);
                            });
                        }
                        //Activities
                        if (response.selectedactivities) {
                            const selectedactivities = {};
                            $.each(response.selectedactivities, function(index, id) {
                                selectedactivities[id] = true;
                            });
                            $('input[name="activities[]"]').each(function() {
                                $(this).prop('checked', selectedactivities[$(this).val()] ||
                                    false);
                            });
                        }
                        //Safety Features
                        if (response.selectedsafety_features) {
                            const selectedsafety_features = {};
                            $.each(response.selectedsafety_features, function(index, id) {
                                selectedsafety_features[id] = true;
                            });
                            $('input[name="safety_features[]"]').each(function() {
                                $(this).prop('checked', selectedsafety_features[$(this)
                                    .val()] || false);
                            });
                        }


                        //pricing caluculator lsit 
                        if (response.pricingcalculator && response.pricingcalculator.length > 0) {
                            const pricingSelect = $('#pricing_calculator');
                            pricingSelect.empty().append(
                                '<option value="" disabled selected>Select Price Calculator</option>'
                            );

                            // Add each pricing calculator option
                            response.pricingcalculator.forEach(item => {
                                pricingSelect.append(
                                    $('<option></option>')
                                    .val(item.id)
                                    .text(item.title)
                                );
                            });


                            // // Hide all sections
                            // $('#stays-section, #activities-section, #cabs-section').hide();
                            // $('#stays-details-container, #activity-details-container, #cabsdetails-container').empty();
                        }

                        //status
                        if (response.package_details.status !== undefined) {
                            $('input[name="status"]').prop('checked', true);
                        }
                        //order
                        if (response.package_details.list_order !== null && response.package_details
                            .list_order !== undefined) {
                            $('input[name="list_order"]').val(response.package_details.list_order);
                        }

                        // Update hidden input on form submission
                        $('form').on('submit', function() {

                            $('#plan_description').val($('#summernote3').summernote(
                                'code'));
                            $('#location').val($('#summernote10').summernote('code'));
                            $('#important_info').val($('#summernote4').summernote('code'));
                            $('#program_inclusion').val($('#summernote5').summernote(
                                'code'));
                            $('#program_exclusion').val($('#summernote9').summernote(
                                'code'));
                        });


                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
            },
            error: function(xhr, status, error) {
                alert('Error fetching package details: ' + error);
            }
        });
    });

    // });

    // console.log('check init', initialDistrict);

    $(document).on('click', '.remove-day', function() {
        $(this).closest('.day-block').remove();
    });


    // Global variables to store settings
    let gstRate = 0.0; // Default 0%
    let serviceFeeAmount = 0; // Service fee as fixed amount

    // Function to calculate and update price summary
    // 1️⃣ Global variable
    let selectedPriceAmount = 0;

    // 2️⃣ Event for radio button selection
    $('#price-fields-container').on('change', 'input[name="selected_price"]', function() {
        const selectedRow = $(this).closest('.row');

        const index = selectedRow.data('index');
        selectedPriceAmount = parseFloat(selectedRow.find('.price-amount').val()) || 0;

        $('#selected-amount').text('₹ ' + selectedPriceAmount.toLocaleString('en-IN'));

        $('#selected-price-id').val(index);

        // 3️⃣ Update grand total immediately
        updatePriceSummary();
    });


    function updatePriceSummary() {
        let selectedValue = 0;

        // Sum up all other price inputs if needed
        $('.price-input').each(function() {
            const price = parseFloat($(this).val()) || 0;
            selectedValue += price;
        });

        // Use the global selectedPriceAmount (Package Pricing)
        let packagePricing = selectedPriceAmount || 0;

        // Get GST from input instead of backend setting
        const userGstRate = parseFloat($('#gst-input').val()) || 0; // percentage
        const serviceFeeAmount = parseFloat($('#service-fee-input').val()) || 0;

        // ✅ CORRECTED: Calculate GST on (Package Pricing + Selected Value + Service Fee)
        const taxableAmount = packagePricing + selectedValue + serviceFeeAmount;
        const gstAmount = Math.round(taxableAmount * (userGstRate / 100));

        const totalBeforeTax = packagePricing + selectedValue + serviceFeeAmount;
        const grandTotal = Math.round(totalBeforeTax + gstAmount);

        // Update the DOM
        $('#selected-amount').text('₹ ' + packagePricing.toLocaleString('en-IN'));
        $('#selected-value').text('₹ ' + selectedValue.toLocaleString('en-IN'));
        $('#service-fee').text('₹ ' + serviceFeeAmount.toLocaleString('en-IN'));
        $('#tax-amount').text('₹ ' + gstAmount.toLocaleString('en-IN'));
        $('#total-amount').text('₹ ' + totalBeforeTax.toLocaleString('en-IN'));
        $('#grand-total').text('₹ ' + grandTotal.toLocaleString('en-IN'));

        // Update hidden inputs
        $('#hidden-selected-value').val(selectedValue);
        $('#hidden-service-fee').val(serviceFeeAmount);
        $('#hidden-tax-amount').val(gstAmount);
        $('#hidden-total-amount').val(totalBeforeTax);
        $('#hidden-grand-total').val(grandTotal);
        $('#package_pricing_value').val(packagePricing);
    }

    $('#gst-input').on('input', function() {
        updatePriceSummary();
    });
    $('#service-fee-input').on('input', function() {
        updatePriceSummary();
    });



    // Function to handle price input changes with validation
    function setupPriceInputListeners() {
        $(document).off('input', '.price-input').on('input', '.price-input', function() {
            // Validate input to allow only numbers
            const value = $(this).val();
            if (value && !/^\d*\.?\d*$/.test(value)) {
                $(this).val(value.replace(/[^\d.]/g, ''));
            }

            // Update price summary
            updatePriceSummary();
        });
    }

    // Function to update settings from backend
    function updateSettingsFromBackend(settings) {
        if (settings && settings.length > 0) {
            const setting = settings[0];

            // GST as percentage (convert to decimal)
            gstRate = (parseFloat(setting.gst) || 0) / 100;

            // Service fee as fixed amount
            serviceFeeAmount = parseFloat(setting.service_fee) || 0;
        }
    }


    $(document).ready(function() {
        $('#stays-section, #activities-section, #cabs-section').hide();

        // Initialize price calculation
        setupPriceInputListeners();
        updatePriceSummary();

        $('#pricing_calculator').change(function() {
            const pricingval = $(this).val();

            // Hide all sections initially
            $('#stays-section, #activities-section, #cabs-section').hide();
            $('#stayDropdownText').text('Select stays');
            $('#activityDropdownText').text('Select activities');
            $('#cabDropdownText').text('Select options');
            $('#stayHiddenInput, #activityHiddenInput, #cabHiddenInput').val('');

            // Clear all details containers
            $('#stays-details-container, #activity-details-container, #cabsdetails-container').empty();
            $('#cabs-details-container').hide();

            // Remove existing event handlers to prevent duplicates
            $('.stay-checkbox').off('change');
            $('.activity-checkbox').off('change');
            $('.cab-details-checkbox').off('change');

            $.ajax({
                url: "{{ route('admin.c_pricing_details') }}",
                type: 'POST',
                data: {
                    pricingval: pricingval,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    // Update settings from backend
                    if (data.settings) {
                        updateSettingsFromBackend(data.settings);
                    }
                    // Process Stays
                    if (data.stays && Object.keys(data.stays).length > 0) {
                        const staysSection = $('#stays-section');
                        const staysDropdown = staysSection.find('.dropdown-menu');
                        // const selectedStays = [];
                        const selectedStays = data.pricing_stay_id;

                        staysDropdown.empty();

                        staysSection.css('display', 'block');

                        $.each(data.stays, function(id, title) {
                            staysDropdown.append(`
                                    <li>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input stay-checkbox" 
                                                id="stay-${id}" value="${id}" checked>
                                            <label class="form-check-label" for="stay-${id}">${title}</label>
                                        </div>
                                    </li>
                                `);
                        });

                        staysSection.show();


                        // Update the dropdown text and hidden input initially
                        $('#stayDropdownText').text(selectedStays.length > 0 ?
                            `${selectedStays.length} selected` : 'Select stays');
                        $('#stayHiddenInput').val(selectedStays.join(','));

                        // Trigger change event for pre-checked boxes on initial load
                        if (selectedStays.length > 0) {
                            $('.stay-checkbox:checked').trigger('change');
                        }

                        $('.stay-checkbox').change(function() {
                            const stayId = $(this).val();
                            if ($(this).is(':checked')) {
                                if (!selectedStays.includes(stayId)) selectedStays
                                    .push(stayId);
                            } else {
                                const index = selectedStays.indexOf(stayId);
                                if (index > -1) selectedStays.splice(index, 1);
                            }
                            $('#stayDropdownText').text(selectedStays.length > 0 ?
                                `${selectedStays.length} selected` :
                                'Select stays');
                            $('#stayHiddenInput').val(selectedStays.join(','));


                            // Trigger AJAX call to update stays details
                            if (selectedStays.length === 0) {
                                $('#stays-details-container').empty();
                                updatePriceSummary
                                    (); // Update immediately when no stays selected
                            } else {
                                // This will trigger the stay-checkbox change event below
                                $(this).trigger('stay-checkbox-change');
                            }
                        });

                        // Trigger change event for pre-checked boxes on initial load
                        if (selectedStays.length > 0) {
                            $('.stay-checkbox:checked').trigger('change');
                        }

                        staysDropdown.on('click', '.form-check', function(e) {
                            e.stopPropagation();
                        });
                    }

                    // Process Activities
                    if (data.activities && Object.keys(data.activities).length > 0) {
                        const activitiesSection = $('#activities-section');
                        const activitiesDropdown = activitiesSection.find('.dropdown-menu');
                        // const selectedActivities = [];
                        const selectedActivities = data.pricing_activity_id;

                        activitiesDropdown.empty();

                        $.each(data.activities, function(id, title) {
                            activitiesDropdown.append(`
                                    <li>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input activity-checkbox" 
                                                id="activity-${id}" value="${id}" checked>
                                            <label class="form-check-label" for="activity-${id}">${title}</label>
                                        </div>
                                    </li>
                                `);
                        });

                        activitiesSection.show();

                        // Update UI immediately
                        $('#activityDropdownText').text(
                            selectedActivities.length > 0 ?
                            `${selectedActivities.length} selected` :
                            'Select activities'
                        );
                        $('#activityHiddenInput').val(selectedActivities.join(','));

                        // Trigger change event for pre-checked boxes on initial load
                        if (selectedActivities.length > 0) {
                            $('.activity-checkbox:checked').trigger('change');
                        }

                        $('.activity-checkbox').change(function() {
                            const activityId = $(this).val();
                            if ($(this).is(':checked')) {
                                if (!selectedActivities.includes(activityId))
                                    selectedActivities.push(activityId);
                            } else {
                                const index = selectedActivities.indexOf(
                                    activityId);
                                if (index > -1) selectedActivities.splice(index, 1);
                            }
                            $('#activityDropdownText').text(selectedActivities
                                .length > 0 ?
                                `${selectedActivities.length} selected` :
                                'Select activities');
                            $('#activityHiddenInput').val(selectedActivities.join(
                                ','));


                            // Trigger AJAX call to update activities details
                            if (selectedActivities.length === 0) {
                                $('#activity-details-container').empty();
                                updatePriceSummary
                                    (); // Update immediately when no activities selected
                            } else {
                                // This will trigger the activity-checkbox change event below
                                $(this).trigger('activity-checkbox-change');
                            }
                        });

                        // Trigger change event for pre-checked boxes on initial load
                        if (selectedActivities.length > 0) {
                            $('.activity-checkbox:checked').trigger('change');
                        }

                        activitiesDropdown.on('click', '.form-check', function(e) {
                            e.stopPropagation();
                        });
                    }

                    // Process Cabs
                    if (data.cabs && Object.keys(data.cabs).length > 0) {
                        const cabsSection = $('#cabs-section');
                        const cabsDropdown = cabsSection.find('.dropdown-menu');

                        console.log('check cabs id', data.pricing_cabs_id);
                        const selectedCabs = data.pricing_cabs_id ?
                            data.pricing_cabs_id
                            .filter(cabId => data.cabs[cabId])
                            .map(cabId => ({
                                key: cabId,
                                value: data.cabs[cabId]
                            })) : [];

                        console.log('check cabs', selectedCabs);
                        cabsDropdown.empty();

                        // Using Object.keys to iterate through the key-value pairs
                        Object.keys(data.cabs).forEach(function(key) {
                            const value = data.cabs[key];
                            cabsDropdown.append(`
                                <li>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input cab-details-checkbox" 
                                            id="cab-${key}" name="${key}" value="${key}" data-text="${value}" checked>
                                        <label class="form-check-label" for="cab-${key}">${value}</label>
                                    </div>
                                </li>
                            `);
                        });

                        cabsSection.show();

                        // Update UI with initial selections
                        $('#cabDropdownText').text(
                            selectedCabs.length > 0 ?
                            selectedCabs.map(item => item.value).join(', ') :
                            'Select options'
                        );
                        $('#cabHiddenInput').val(selectedCabs.map(item => item.key).join(','));

                        // Load cab details immediately for pre-selected cabs
                        if (selectedCabs.length > 0) {
                            console.log('Loading initial cab details');
                            loadCabDetailsDirectly(selectedCabs.map(item => item.key));
                        }

                        // Handle cab details checkbox changes directly
                        $(document).on('change', '.cab-details-checkbox', function() {
                            const selectedCabDetails = $('.cab-details-checkbox:checked').map(function() {
                                return {
                                    id: $(this).val(),
                                    text: $(this).data('text')
                                };
                            }).get();

                            // Update dropdown text
                            $('#cabDropdownText').text(
                                selectedCabDetails.length > 0 ?
                                selectedCabDetails.map(item => item.text).join(', ') :
                                'Select options'
                            );
                            $('#cabHiddenInput').val(selectedCabDetails.map(item => item.id).join(','));

                            // Load cab details
                            if (selectedCabDetails.length === 0) {
                                $('#cabsdetails-container').empty().hide();
                                updatePriceSummary();
                            } else {
                                loadCabDetailsDirectly(selectedCabDetails.map(item => item.id));
                            }
                        });

                        cabsDropdown.on('click', '.form-check', function(e) {
                            e.stopPropagation();
                        });
                    }
                    // Setup price listeners after all data is loaded
                    setupPriceInputListeners();
                    updatePriceSummary();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $('#stays-section, #activities-section, #cabs-section').html(
                            '<div class="alert alert-danger">Error loading data</div>')
                        .show();
                }
            });
        });

        //stay details - stay-checkbox
        $(document).on('change', '.stay-checkbox', function() {
            const pricingval = $('#pricing_calculator').val();
            const selectedStays = [];

            $('.stay-checkbox:checked').each(function() {
                selectedStays.push($(this).val());
            });

            if (selectedStays.length === 0) {
                $('#stays-details-container').empty();
                updatePriceSummary(); // Update summary when no stays selected
                return;
            }

            $.ajax({
                url: "{{ route('admin.c_stay_details') }}",
                type: 'POST',
                data: {
                    pricingval: pricingval,
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
                                            value="${stay.price}" readonly> 
                                    </div>
                                </div>
                            `;
                            container.append(stayHtml);
                        });
                    });

                    // Setup listeners and update price after stays are loaded
                    setupPriceInputListeners();
                    updatePriceSummary();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        //activity details
        $(document).on('change', '.activity-checkbox', function() {
            const pricingval = $('#pricing_calculator').val();
            const selectedStays = [];

            $('.activity-checkbox:checked').each(function() {
                selectedStays.push($(this).val());
            });

            if (selectedStays.length === 0) {
                $('#activity-details-container').empty();
                updatePriceSummary(); // Update summary when no activities selected
                return;
            }

            $.ajax({
                url: "{{ route('admin.c_activity_details') }}",
                type: 'POST',
                data: {
                    pricingval: pricingval,
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
                                            value="${activity.price}" readonly>
                                    </div>
                                </div>
                            `;
                            container.append(activityHtml);
                        });
                    });

                    // Setup listeners and update price after activities are loaded
                    setupPriceInputListeners();
                    updatePriceSummary();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });


        // New function to load cab details directly
        function loadCabDetailsDirectly(selectedCabIds) {
            const pricingval = $('#pricing_calculator').val();

            console.log('Loading cab details for:', selectedCabIds);

            if (selectedCabIds.length === 0) {
                $('#cabsdetails-container').empty().hide();
                updatePriceSummary();
                return;
            }

            $.ajax({
                url: "{{ route('admin.c_cabs_details') }}",
                type: 'POST',
                data: {
                    pricingval: pricingval,
                    cabdetails: selectedCabIds,
                    travelmodes: selectedCabIds, // Pass same IDs for consistency
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    displayCabDetails(data.activity_details || data.cab_details || data.details || []);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading cab details:', error);
                    $('#cabsdetails-container').empty().hide();
                }
            });
        }

        // Handle main cab type selection - UPDATED
        // $(document).on('change', '.cab-checkbox', function() {
        //     const pricingval = $('#pricing_calculator').val();
        //     const selectedCabIds = $('.cab-checkbox:checked').map(function() {
        //         return $(this).val();
        //     }).get();
        //     if (selectedCabIds.length === 0) {
        //         $('#cabs-details-container, #cabsdetails-container').hide().empty();
        //         updatePriceSummary();
        //         return;
        //     }

        //     $.ajax({
        //         url: "{{ route('admin.c_travel_details') }}",
        //         type: 'POST',
        //         data: {
        //             pricingval: pricingval,
        //             travelmodes: selectedCabIds,
        //             _token: "{{ csrf_token() }}"
        //         },
        //         success: function(data) {
        //             updateCabDetailsDropdown(data.cabs, data.pricing_cab || []);
        //         },
        //         error: function(xhr, status, error) {
        //             $('#cabs-details-container, #cabsdetails-container').hide();
        //         }
        //     });
        // });

        // Helper: Update cab details dropdown - UPDATED
        // function updateCabDetailsDropdown(cabsData, selectedCabIds) {
        //     const container = $('#cabs-details-container');
        //     const dropdownMenu = container.find('.dropdown-menu');
        //     const dropdownText = container.find('#cabDetailsDropdownText');
        //     const hiddenInput = container.find('#cabDetailsHiddenInput');

        //     dropdownMenu.empty();

        //     // Ensure selectedCabIds is an array
        //     const cabIdsArray = Array.isArray(selectedCabIds) ? selectedCabIds :
        //         (selectedCabIds ? [selectedCabIds.toString()] : []);

        //     hiddenInput.val(cabIdsArray.join(','));
        //     dropdownText.text(cabIdsArray.length > 0 ? `${cabIdsArray.length} selected` : 'Select options');

        //     $('#cabsdetails-container').empty().hide();

        //     if (cabsData && Object.keys(cabsData).length > 0) {
        //         $.each(cabsData, function(id, title) {
        //             const isChecked = cabIdsArray.includes(id.toString());
        //             const checkboxId = `cab-detail-${id}`;

        //             dropdownMenu.append(`
        //         <li>
        //             <div class="form-check">
        //                 <input type="checkbox" class="form-check-input cab-details-checkbox" 
        //                     id="${checkboxId}" value="${id}" 
        //                     ${isChecked ? 'checked' : ''} 
        //                     data-text="${title}">
        //                 <label class="form-check-label" for="${checkboxId}">${title}</label>
        //             </div>
        //         </li>
        //     `);
        //         });

        //         // Show the container
        //         container.show();

        //         // Prevent dropdown close when clicking checkboxes
        //         dropdownMenu.off('click', '.form-check').on('click', '.form-check', function(e) {
        //             e.stopPropagation();
        //         });

        //         // Update the dropdown text immediately
        //         updateCabDetailsDropdownText();

        //         // Trigger change for pre-checked boxes
        //         if (cabIdsArray.length > 0) {
        //             setTimeout(() => {
        //                 $('.cab-details-checkbox:checked').trigger('change');
        //             }, 100);
        //         }
        //     } else {
        //         container.hide();
        //     }
        // }

        // Helper: Update cab details dropdown text
        // function updateCabDetailsDropdownText() {
        //     const selectedCabDetails = $('.cab-details-checkbox:checked').map(function() {
        //         return $(this).data('text');
        //     }).get();

        //     const dropdownText = $('#cabDetailsDropdownText');
        //     const hiddenInput = $('#cabDetailsHiddenInput');

        //     const selectedIds = $('.cab-details-checkbox:checked').map(function() {
        //         return $(this).val();
        //     }).get();

        //     dropdownText.text(selectedCabDetails.length > 0 ?
        //         selectedCabDetails.join(', ') : 'Select options');
        //     hiddenInput.val(selectedIds.join(','));
        // }

        // // Handle cab details selection - UPDATED
        // $(document).on('change', '.cab-details-checkbox', function() {
        //     updateCabDetailsDropdownText(); // Update dropdown text immediately
        //     handleCabDetailsChange();
        // });

        // Helper: Handle cab details selection logic - UPDATED
        // function handleCabDetailsChange() {
        //     const pricingval = $('#pricing_calculator').val();
        //     const selectedCabDetails = $('.cab-details-checkbox:checked').map(function() {
        //         return {
        //             id: $(this).val(),
        //             text: $(this).data('text')
        //         };
        //     }).get();

        //     const selectedCabIds = $('.cab-checkbox:checked').map(function() {
        //         return $(this).val();
        //     }).get();

        //     if (selectedCabDetails.length === 0) {
        //         $('#cabsdetails-container').empty().hide();
        //         updatePriceSummary();
        //         return;
        //     }

        //     $.ajax({
        //         url: "{{ route('admin.c_cabs_details') }}",
        //         type: 'POST',
        //         data: {
        //             pricingval: pricingval,
        //             cabdetails: selectedCabDetails.map(d => d.id),
        //             travelmodes: selectedCabIds,
        //             _token: "{{ csrf_token() }}"
        //         },
        //         success: function(data) {
        //             displayCabDetails(data.activity_details || data.cab_details || data.details || []);
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error loading cab details:', error);
        //             $('#cabsdetails-container').empty().hide();
        //         }
        //     });
        // }

        // Helper: Display cab details - UPDATED
        function displayCabDetails(detailsData) {
            const container = $('#cabsdetails-container');
            container.empty();
            if (detailsData && detailsData.length > 0) {
                detailsData.forEach((cabGroup, groupIndex) => {
                    if (cabGroup && cabGroup.length > 0 && cabGroup[0]) {
                        const groupTitle = cabGroup[0].title || cabGroup[0].name || 'Cab Details';
                        container.append(`
                    <div class="row cab-group-header mb-2">
                        <div class="col-md-12">
                            <h5>${groupTitle}</h5>
                        </div>
                    </div>
                `);

                        cabGroup.forEach((cab, itemIndex) => {
                            if (cab) {
                                container.append(`
                            <div class="row cab-detail-row mb-3">
                                <div class="col-md-4">
                                    <input type="hidden" name="cabs[${groupIndex}][${itemIndex}][cab_id]" value="${cab.cab_id || cab.id || ''}">
                                    <input type="hidden" name="cabs[${groupIndex}][${itemIndex}][title]" value="${cab.title || cab.name || ''}">
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" name="cabs[${groupIndex}][${itemIndex}][price_title]" value="${cab.price_title || cab.title || ''}">
                                    <input type="text" class="form-control" value="${cab.price_title || cab.title || ''}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control price-input" 
                                        name="cabs[${groupIndex}][${itemIndex}][price]" 
                                        value="${cab.price || '0'}" readonly>
                                </div>
                            </div>
                        `);
                            }
                        });
                    }
                });

                setupPriceInputListeners();
                updatePriceSummary();
                container.show();
            } else {
                container.hide();
            }
        }
        // // Add event listeners for checkbox changes to recalculate prices
        // $(document).on('change', '.stay-checkbox, .activity-checkbox, .cab-checkbox, .cab-details-checkbox', function() {
        //     // Small delay to allow DOM to update
        //     setTimeout(() => {
        //         updatePriceSummary();
        //     }, 100);
        // });

    });
</script>
@endsection