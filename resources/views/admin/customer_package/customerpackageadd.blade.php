@extends('layouts.app')
@section('content')
<style>
    a:hover {
        color: red;
    }

    a {
        color: rgb(37, 150, 190);
    }

    .enquiry {
        color: blue;
    }


    .form-check-input {
        transform: scale(1.5);
        /* Increase the size of the checkbox */
    }
</style>
<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a class="" href="/enquiry">Booking</a></b> > <a class="enquiry" href="">Add Booking</a>
    </div>

</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="row mb-5">
    <div class="col-lg-12">
        <!-- INFORMATION -->
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form class="bg-white p-1 rounded-3" action="{{ route('admin.CustomerPackage_insert') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row d-flex gap-4">
                    <div class="add_form col-md-5 mb-1">
                        <label for="name" class="form-label">Full Name</label>
                        <input id="name" type="text" class="form-control" name="name">
                    </div>

                    <div class="add_form col-md-5 mb-1">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input id="phone" type="number" class="form-control" name="phone_number">
                    </div>

                    <div class="add_form col-md-5 mb-1">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" class="form-control" name="email">
                    </div>

                    <!-- <div class="col-md-5 mb-3">
                    <label for="type"  class="form-label">Package Type</label>
                    <input id="type" type="text" class="form-control" name="package_type" required>
                    
                </div> -->
            <!-- Package Type Selector -->
            <div class="add_form col-md-5 mb-3">
                <label for="title_id" class="form-label">Select Package Type</label>
                <select name="package_type" id="package" class="form-control package">
                    <option disabled selected>Select Package Type</option>
                    @foreach($titles as $id => $name)
                    <option value="{{  json_encode(['id' => $id, 'name' => $name])  }}">{{ $name }}</option>

                    @endforeach
                </select>
            </div>

            <div class="col-md-5 mb-3">
                <label for="title_id" class="form-label">Select Stays</label>
                <select class="package" name="package_stay" id="package_stay" class="form-control">
                    <option disabled selected>Select Package Type</option>

                </select>
            </div>


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

<!-- 
                    <div class="add_form col-md-5 mb-1">
                        <label for="title_id" class="form-label">Select Stays</label>
                        <select name="package_stay" id="package_stay" class="form-control package">
                            <option disabled selected>Select Package Type</option>

                        </select>
                    </div> -->

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
                                        <h4 class="add_head fw-bold mb-2">02. Tour Planning <span class="text-danger">*</span></h4>
                                        <div id="day-wrapper"></div>
                                        <!-- Add New Plan Button -->
                                        <!-- <div class="text-end ">
                                        <button type="button" id="add-plan-btn"
                                            class="btn-add rounded-3 border-0 px-3 py-2 text-white">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Add
                                        </button>
                                    </div> -->
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="row mb-2">
                            <div class="col">
                                <div class="form-body px-5 rounded-4">
                                    <h4 class="fw-bold mb-2">04.Tour Date & Time</h4>
                                    <div class="mb-3">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-4">
                                                <label class="mb-2">Start Date <span class="text-danger"></span></label>
                                                <input type="date" class="form-control py-2 rounded-3 shadow-sm"
                                                    name="start_date" id="start_date" value="{{old('start_date')}}"
                                                    >
                                            </div>
                                            <div class="col-md-4">
                                                <label class=" mb-2">Return Date <span
                                                        class="text-danger"></span></label>
                                                <input type="date" class="form-control py-2 rounded-3 shadow-sm"
                                                    name="return_date" id="return_date" value="{{old('return_date')}}"
                                                    >
                                            </div>
                                            <div class="col-md-4">
                                                <label class="mb-2">Total No. of Days</label>
                                                <input type="number" class="form-control py-2 rounded-3 shadow-sm"
                                                    id="total_days" name="total_days" value="{{old('total_days')}}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                            <!-- 4. Needed -->
                            <!-- <div class="row mb-2">
                            <div class="col">
                                <div class="form-body px-5 rounded-4">
                                    <h4 class="fw-bold mb-2">04.Rooms and Beds</h4>
                                    <div class="mb-3">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-3">
                                                <label class=" mb-2">Rooms<span class="text-danger"></span></label>
                                                <input type="number" class="form-control py-2 rounded-3 shadow-sm"
                                                    name="total_room" id="total_room" value="{{old('total_room')}}"
                                                    >
                                            </div>
                                            <div class="col-md-3">
                                                <label class="mb-2">Bath Rooms<span class="text-danger"></span></label>
                                                <input type="number" class="form-control py-2 rounded-3 shadow-sm"
                                                    name="bath_room" id="bath_room" value="{{old('bath_room')}}"
                                                    >
                                            </div>
                                            <div class="col-md-3">
                                                <label class=" mb-2">Bed Rooms</label>
                                                <input type="number" class="form-control py-2 rounded-3 shadow-sm"
                                                    id="bed_room" name="bed_room" value="{{old('bed_room')}}" >
                                            </div>
                                            <div class="col-md-3">
                                                <label class=" mb-2">Hall</label>
                                                <input type="number" class="form-control py-2 rounded-3 shadow-sm"
                                                    id="hall" name="hall" value="{{old('hall')}}" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->



                            <!-- 5.PRICING -->
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="form-body rounded-4">
                                        <h4 class="add_head fw-bold mb-2">03. Pricing</h4>
                                        <div id="price-fields-container" class="mb-2">
                                            <!-- 
                                    <div class="row mb-2">
                                        <div class="col-lg-6">
                                            <label class="form-label form-label-top form-label-auto fw-bold mb-2">
                                                Title
                                            </label>
                                            <input type="text" name="price_title[]" class="form-control py-2 rounded-3 shadow-sm"
                                                placeholder="Title">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="fw-bold mb-2">Amount <span class="text-danger">*</span></label>
                                            <div class="position-relative">
                                                <span class="position-absolute top-50 start-0 translate-middle-y ps-3">₹</span>
                                                <input type="number" name="price_amount[]" class="form-control py-2 ps-5 rounded-3 shadow-sm"
                                                    placeholder="Actual Amount">
                                            </div>
                                        </div>
                                    </div> -->
                                            <!-- <div class="row mb-2">
                                        <div class="col-lg-6">
                                            <label class="form-label form-label-top form-label-auto fw-bold mb-2">
                                                Title
                                            </label>
                                            <input type="text" name="price_title[]" class="form-control py-2 rounded-3 shadow-sm"
                                                placeholder="Title">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="fw-bold mb-2">Amount <span class="text-danger">*</span></label>
                                            <div class="position-relative">
                                                <span class="position-absolute top-50 start-0 translate-middle-y ps-3">₹</span>
                                                <input type="number" name="price_amount[]" class="form-control py-2 ps-5 rounded-3 shadow-sm"
                                                    placeholder="Actual Amount">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-6">
                                            <label class="form-label form-label-top form-label-auto fw-bold mb-2">
                                                Title
                                            </label>
                                            <input type="text" name="price_title[]" class="form-control py-2 rounded-3 shadow-sm"
                                                placeholder="Title">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="fw-bold mb-2">Amount <span class="text-danger">*</span></label>
                                            <div class="position-relative">
                                                <span class="position-absolute top-50 start-0 translate-middle-y ps-3">₹</span>
                                                <input type="number" name="price_amount[]" class="form-control py-2 ps-5 rounded-3 shadow-sm"
                                                    placeholder="Actual Amount">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-6">
                                            <label class="form-label form-label-top form-label-auto fw-bold mb-2">
                                                Title
                                            </label>
                                            <input type="text" name="price_title[]" class="form-control py-2 rounded-3 shadow-sm"
                                                placeholder="Title">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="fw-bold mb-2">Amount <span class="text-danger">*</span></label>
                                            <div class="position-relative">
                                                <span class="position-absolute top-50 start-0 translate-middle-y ps-3">₹</span>
                                                <input type="number" name="price_amount[]" class="form-control py-2 ps-5 rounded-3 shadow-sm"
                                                    placeholder="Actual Amount">
                                            </div>
                                        </div>
                                    </div> -->

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
                                            <!-- <div class="row g-2 mb-1 align-items-center camp-rule-field">
                                    
                                        <label class="mb-1">Payment Policy <span
                                                class="text-danger">*</span></label>

                                        <div class="col-md-11">
                                            <input type="text" name="camp_rule[]" id="camp_rule"
                                                class="form-control py-2 rounded-3 shadow-sm"
                                                placeholder="Payment Policy">
                                        </div>
                                       
                                        <div class="col-md-1">
                                            <button type="button"
                                                class="btn-add rounded border-0 px-4 py-2 text-white"
                                                onclick="addCampRuleField()">
                                                <i class="fa fa-plus" aria-hidden="true"></i> Add
                                            </button>
                                        </div>
                                    </div> -->
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
                                        <h4 class="add_head fw-bold mb-2">05. Notes <span class="text-danger">*</span></h4>
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
                                                    <input type="hidden" id="program_inclusion" name="program_inclusion">
                                                    <!-- <textarea id="important_info" class="container__textarea p-5 textarea-feild" name="important_info" value="{{old('important_info')}}" required></textarea> -->
                                                    <!-- <div class="mb-3">
                                    <div id="commentEditor5" class="form-control" style="height: 200px;"></div>
                                </div> -->
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
                                                    <input type="hidden" id="program_exclusion" name="program_exclusion">
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

                            <!-- <div class="row mb-3">
                            <div class="col">
                                <div class="form-body px-1 py-3 rounded-4">
                                    <h4 class="fw-bold mb-3">9. Location</h4>
                                    <div>
                                        <div class="row align-items-start">
                                            <div class="col-lg-6">
                                                <label for="google_map" class="fw-bold mb-3">Google Map<span class="text-danger">*</span></label>
                                                <input
                                                    type="text"
                                                    id="google_map"
                                                    name="google_map"
                                                    class="form-control py-3 rounded-3 shadow-sm"
                                                    placeholder="Enter Google Map Embed Iframe">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fw-bold mb-3">Map Preview</label>
                                                <iframe
                                                    id="map_preview"
                                                    width="100%"
                                                    height="250"
                                                    frameborder="0"
                                                    style="border:0;"
                                                    allowfullscreen
                                                    aria-hidden="false"
                                                    tabindex="0">
                                                </iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

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


                            <!-- <div class="row mb-3">
                            <div class="col">
                                <div class="form-body px-5 rounded-4">
                                    <h4 class="fw-bold mb-3">08. Upload PDF</h4>
                                    <div class="mb-1">
                                        <div class="row g-2 mb-2">
                                            <div class="col">
                                                <label class="form-label form-label-top form-label-auto mb-2">Upload PDF</label>
                                                <input type="file" id="program_pdf" name="program_pdf" class="form-control py-2 rounded-3 shadow-sm" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                            <!-- <div class="row mb-2">
                            <div class="col">
                                <div class="form-body px-5 rounded-4">
                                    <h4 class="fw-bold mb-3">09. Food Menu</h4>
                                    <div class="mb-1">
                                        <div class="row g-2">
                                            <div class="col-lg-4">
                                                <label
                                                    class="form-label form-label-top form-label-auto mb-2">Breakfast</label>
                                                <input type="hidden" id="break_fast" name="break_fast">
                                                <div class="mt-2">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div id="summernote6" style="height: 200px;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label
                                                    class="form-label form-label-top form-label-auto  mb-2">Lunch</label>
                                                <input type="hidden" id="lunch" name="lunch">
                                                <div class="mt-2">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div id="summernote7" style="height: 200px;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label
                                                    class="form-label form-label-top form-label-auto mb-2">Dinner</label>
                                                <input type="hidden" id="dinner" name="dinner">
                                                <div class="mt-2">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div id="summernote8" style="height: 200px;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->


                            <!-- 8. AMENITIES -->
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="form-body rounded-4">
                                        <h4 class="add_head fw-bold mb-2">08. Amenities</h4>
                                        <div class="d-flex flex-wrap">
                                            @foreach($amenities as $index => $amenity)
                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                                                <div class="form-check d-flex align-items-center">
                                                    <input type="checkbox" class="me-2 custom-checkbox"
                                                        id="amenity-{{ $amenity->id }}" name="amenity_services[]"
                                                        value="{{ $amenity->id }}">
                                                    <label for="amenity-{{ $amenity->id }}"
                                                        class="mb-0">{{ $amenity->amenity_name }}</label>
                                                </div>
                                            </div>
                                            @if(($index + 1) % 4 == 0)
                                            <div class="w-100"></div> <!-- Forces a line break after every 4 items -->
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
                                            @foreach($foodBeverages as $index => $item)
                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                                                <div class="form-check d-flex align-items-center">
                                                    <input type="checkbox" class="me-2 custom-checkbox"
                                                        id="food-beverage-{{ $item->id }}" name="food_beverages[]"
                                                        value="{{ $item->id }}">
                                                    <label for="food-beverage-{{ $item->id }}"
                                                        class="mb-0">{{ $item->food_beverage }}</label>
                                                </div>
                                            </div>
                                            @if(($index + 1) % 4 == 0)
                                            <div class="w-100"></div> <!-- Forces a line break after every 4 items -->
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
                                            @foreach($activities as $index => $item)
                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                                                <div class="form-check d-flex align-items-center ">
                                                    <input type="checkbox" class="me-2 custom-checkbox"
                                                        id="activities-{{ $item->id }}" name="activities[]"
                                                        value="{{ $item->id }}">
                                                    <label for="activities-{{ $item->id }}"
                                                        class="mb-0">{{ $item->activities }}</label>
                                                </div>
                                            </div>
                                            @if(($index + 1) % 4 == 0)
                                            <div class="w-100"></div> <!-- Forces a line break after every 4 items -->
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
                                            @foreach($safety_features as $index => $item)
                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                                                <div class="form-check d-flex align-items-center mb-1">
                                                    <input type="checkbox" class="me-2 custom-checkbox"
                                                        id="safety_features-{{ $item->id }}" name="safety_features[]"
                                                        value="{{ $item->id }}">
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



                            <!-- 6.rule & Regulation
                <div class="row mb-5">
                    <div class="col">
                        <div class="form-body px-5 rounded-4">
                            <h4 class="fw-bold mb-5">14. Payment Policy</h4>
                            <div class="mb-3">
                                <div id="camp-rule-container">
                                    <div class="row g-2 mb-4 camp-rule-field">
                                        <div class="col">
                                            <label class="fw-bold mb-4">Payment Policy <span class="text-danger">*</span></label>
                                            <input type="text" name="camp_rule[]" id="camp_rule" class="form-control py-3  px-3 rounded-3 shadow-sm" placeholder="Payment Policy" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn-add rounded border-0 px-5 py-3 text-white" onclick="addCampRuleField()">
                                        <i class="fa fa-plus" aria-hidden="true"></i> Add
                                    </button>
                                </div>
                            </div> -->

                            <br>

                            <div 
                            class="row g-2">
                                <div class="add_form col">
                                    <h4> <label class="fw-bold">Status</label></h4>
                                    <div class="form-check form-switch d-flex align-items-center">
                                        <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                                    </div>
                                </div>
                            </div>


                            <div class="row g-3">
                                <div class="add_form col-lg-4">
                                    <label class="fw-bold mb-3 ">Order</label>
                                    <input type="number" placeholder="Order" id="list_order" name="list_order"
                                        value="{{old('order')}}" class="form-control py-2 rounded-3 shadow-sm">
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

    //package change

    $(".package").change(function() {
        var selectedOption = $(this).val();
        var packageData = JSON.parse(selectedOption);

        var package_id = packageData.id;
        // var package_id = $('#package').val();
        $.ajax({
            url: '/customer-package/package-details',
            type: 'POST',
            data: {
                id: package_id,
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
                        .text('Select Package Type')
                        .prop('disabled', true)
                        .prop('selected', true)
                    );

                    if (response && response.cities_details && Array.isArray(response.cities_details)) {
                        const validStays = response.cities_details.filter(stay =>
                            stay && stay.id !== undefined && stay.stay_title
                        );

                        if (validStays.length > 0) {
                            // Add each valid stay as an option
                            validStays.forEach(stay => {
                                packageStaySelect.append(
                                    $('<option></option>')
                                    .val(stay.id)
                                    .text(stay.stay_title)
                                );
                            });

                            // Enable select if it was disabled
                            packageStaySelect.prop('disabled', false);
                        } else {
                            // No valid stays found
                            packageStaySelect.append(
                                $('<option></option>')
                                .val('')
                                .text('No available stays')
                            );
                            packageStaySelect.prop('disabled', true);
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
                        // First, clear all existing day blocks except the first one
                        $('#day-wrapper').html('');

                        tourVal.forEach(function(day, i) {
                            const wrapper = document.getElementById('day-wrapper');
                            const div = document.createElement('div');
                            div.classList.add('row', 'g-2', 'mb-2', 'day-block');

                            div.innerHTML = `
                                <div class="col-md-5 mb-2">
                                    <input type="text" name="tour_planning[${i}][title]" class="form-control py-2 rounded-3 shadow-sm" value="${day.title}" placeholder="Day Title (e.g., Day ${i + 1})">
                                </div>
                                <div class="col-md-5 mb-2">
                                    <input type="text" name="tour_planning[${i}][subtitle]" class="form-control py-2 rounded-3 shadow-sm" value="${day.subtitle}" placeholder="Activity Subtitle">
                                </div>
                                <div class="col-md-10 mb-2">
                                    <input type="hidden" name="tour_planning[${i}][description]" class="tour-description-hidden">
                                    <div class="tour-description-editor"></div>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    ${i > 0 ? `<button type="button" class="btn btn-danger remove-day" onclick="removeDay(this)">
                                        <i class="fa fa-trash"></i>
                                    </button>` : ''}
                                </div>
                            `;
                            wrapper.appendChild(div);
                            // Initialize Summernote and set saved HTML description
                            const editor = $(div).find('.tour-description-editor');
                            const hiddenInput = $(div).find('.tour-description-hidden');

                            editor.summernote({
                                height: 120,
                                callbacks: {
                                    onChange: function(contents) {
                                        hiddenInput.val(contents);
                                    }
                                }
                            });

                            // Set old saved description to Summernote and hidden field
                            editor.summernote('code', day.description);
                            hiddenInput.val(day.description);
                        });

                        if (response.package_details.location) {
                            $('#title').val(response.package_details.title)
                            $('#summernote10').summernote('code', response.package_details.location);

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
                                        <div class="row mb-2">
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-top form-label-auto fw-bold mb-2">
                                                    Title
                                                </label>
                                                <input type="text" name="price_title[]" class="form-control py-2 rounded-3 shadow-sm"
                                                    placeholder="Title"
                                                    value="${priceTitle[i] || ''}">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fw-bold mb-2">Amount <span class="text-danger">*</span></label>
                                                <div class="position-relative">
                                                    <span class="position-absolute top-50 start-0 translate-middle-y ps-3">₹</span>
                                                    <input type="number" name="price_amount[]" class="form-control py-2 ps-5 rounded-3 shadow-sm"
                                                        placeholder="Actual Amount"
                                                        value="${priceAmount[i] || ''}">
                                                </div>
                                            </div>
                                        </div>`;

                            priceContainer.append(fieldHtml);
                        }

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
                        $('#summernote4').summernote('code', response.package_details.important_info); // notes
                        $('#summernote5').summernote('code', response.package_details.program_inclusion); // program Inclusion
                        $('#summernote9').summernote('code', response.package_details.program_exclusion); // program Exclusion


                        //aminites
                        if (response.selectedAmenities) {
                            // Convert array to object for faster lookup
                            const selectedAmenities = {};
                            $.each(response.selectedAmenities, function(index, id) {
                                selectedAmenities[id] = true;
                            });

                            // Loop through all checkboxes just once
                            $('input[name="amenity_services[]"]').each(function() {
                                $(this).prop('checked', selectedAmenities[$(this).val()] || false);
                            });
                        }
                        //food beverages
                        if (response.selectedfood_beverages) {
                            const selectedFoodBeverages = {};
                            $.each(response.selectedfood_beverages, function(index, id) {
                                selectedFoodBeverages[id] = true;
                            });
                            $('input[name="food_beverages[]"]').each(function() {
                                $(this).prop('checked', selectedFoodBeverages[$(this).val()] || false);
                            });
                        }
                        //Activities
                        if (response.selectedactivities) {
                            const selectedactivities = {};
                            $.each(response.selectedactivities, function(index, id) {
                                selectedactivities[id] = true;
                            });
                            $('input[name="activities[]"]').each(function() {
                                $(this).prop('checked', selectedactivities[$(this).val()] || false);
                            });
                        }
                        //Safety Features
                        if (response.selectedsafety_features) {
                            const selectedsafety_features = {};
                            $.each(response.selectedsafety_features, function(index, id) {
                                selectedsafety_features[id] = true;
                            });
                            $('input[name="safety_features[]"]').each(function() {
                                $(this).prop('checked', selectedsafety_features[$(this).val()] || false);
                            });
                        }

                        //destination check
                        // const selectElement = document.getElementById('cities_name');
                        // if (selectElement && response.selectedCityId) {
                        //     // Find the option with matching ID (data attribute approach)
                        //     const option = $(`#cities_name option[data-id="${response.selectedCityId}"]`);
                        //     if (option.length) {
                        //         selectElement.value = option.val();
                        //         $('#cities_name').trigger('change');
                        //         $('#district_name').trigger('change');
                        //     }
                        // }
                        // if (response.selectedLocationname) {
                        //     districtState.value = response.selectedLocationname;
                        //     console.log('District value set:', districtState.value);

                        //     // Try to select the district immediately
                        //     const districtSelect = $('#district_name');
                        //     const option = $(`#district_name option[value="${districtState.value}"]`);

                        //     if (option.length) {
                        //         districtSelect.val(districtState.value).trigger('change');
                        //     } else {
                        //         // If district not found, trigger city change to reload districts
                        //         const citySelect = $('#cities_name');
                        //         if (citySelect.val()) {
                        //             citySelect.trigger('change');
                        //         }
                        //     }
                        // }

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
                        }

                        //status
                        if (response.package_details.status !== undefined) {
                            $('input[name="status"]').prop('checked', true);
                        }
                        //order
                        if (response.package_details.list_order !== null && response.package_details.list_order !== undefined) {
                            $('input[name="list_order"]').val(response.package_details.list_order);
                        }

                        // Update hidden input on form submission
                        $('form').on('submit', function() {

                            $('#plan_description').val($('#summernote3').summernote('code'));
                            $('#location').val($('#summernote10').summernote('code'));
                            $('#important_info').val($('#summernote4').summernote('code'));
                            $('#program_inclusion').val($('#summernote5').summernote('code'));
                            $('#program_exclusion').val($('#summernote9').summernote('code'));
                        });


                    }
                } catch (e) {
                    console.error('Error processing response:', e);
                }
            },

            error: function(xhr, status, error) {
                alert('AJAX Error: ' + error);
            }
        });


    });

    // console.log('check init', initialDistrict);

    $(document).on('click', '.remove-day', function() {
        $(this).closest('.day-block').remove();
    });

    $(document).ready(function() {
        $('#stays-section, #activities-section, #cabs-section').hide();
        $('#pricing_calculator').change(function() {
            const pricingval = $(this).val();

            // Hide all sections initially
            $('#stays-section, #activities-section, #cabs-section').hide();
            $('#stayDropdownText').text('Select stays');
            $('#activityDropdownText').text('Select activities');
            $('#cabDropdownText').text('Select options');
            $('#stayHiddenInput, #activityHiddenInput, #cabHiddenInput').val('');

            $.ajax({
                url: "{{ route('admin.c_pricing_details') }}",
                type: 'POST',
                data: {
                    pricingval: pricingval,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
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
                        $('#stayDropdownText').text(selectedStays.length > 0 ? `${selectedStays.length} selected` : 'Select stays');
                        $('#stayHiddenInput').val(selectedStays.join(','));

                        // Trigger change event for pre-checked boxes on initial load
                        if (selectedStays.length > 0) {
                            $('.stay-checkbox:checked').trigger('change');
                        }

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
                        // const selectedCabs = [];
                        const selectedCabs = data.pricing_cabs ?
                            Object.keys(data.pricing_cabs)
                            .filter(key => data.cabs[key]) // Only keep keys that exist in current cabs
                            .map(key => ({
                                key: key,
                                value: data.cabs[key]
                            })) : [];

                        cabsDropdown.empty();

                        // Using Object.keys to iterate through the key-value pairs
                        Object.keys(data.cabs).forEach(function(key) {
                            const value = data.cabs[key];
                            cabsDropdown.append(`
                                    <li>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input cab-checkbox cab_details" 
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

                        if (selectedCabs.length > 0) {
                            $('.cab-checkbox:checked').trigger('change');
                        }

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
            const pricingval = $('#pricing_calculator').val();
            const selectedStays = [];

            $('.stay-checkbox:checked').each(function() {
                selectedStays.push($(this).val());
            });

            if (selectedStays.length === 0) {
                $('#stays-details-container').empty();
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
            const pricingval = $('#pricing_calculator').val();
            const selectedStays = [];

            $('.activity-checkbox:checked').each(function() {
                selectedStays.push($(this).val());
            });

            if (selectedStays.length === 0) {
                $('#activity-details-container').empty();
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
            const pricingval = $('#pricing_calculator').val();
            const selectedCabIds = [];

            $('.cab-checkbox:checked').each(function() {
                selectedCabIds.push($(this).val());
            });

            if (selectedCabIds.length === 0) {
                $('#cabs-details-container, #cabsdetails-container').hide();
                return;
            }

            $.ajax({
                url: "{{ route('admin.c_travel_details') }}",
                type: 'POST',
                data: {
                    pricingval: pricingval,
                    travelmodes: selectedCabIds,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    updateCabDetailsDropdown(data.cabs, data.pricing_cab);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $('#cabs-details-container, #cabsdetails-container').hide();
                }
            });
        });

        // Handle cab details selection
        $(document).on('change', '.cab-details-checkbox', function() {
            const pricingval = $('#pricing_calculator').val();
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
                url: "{{ route('admin.c_cabs_details') }}",
                type: 'POST',
                data: {
                    pricingval: pricingval,
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
        function updateCabDetailsDropdown(cabsData, cabids) {
            const container = $('#cabs-details-container');
            const dropdownMenu = container.find('.dropdown-menu');
            const dropdownText = container.find('#cabDetailsDropdownText');
            const hiddenInput = container.find('#cabDetailsHiddenInput');

            // Reset previous selections
            dropdownMenu.empty();
            hiddenInput.val(cabids);
            dropdownText.text(cabids.length > 0 ? `${cabids.length} selected` : 'Select options');
            $('#cabsdetails-container').empty().hide();

            if (cabsData && Object.keys(cabsData).length > 0) {
                const selectedCabDetails = cabids
                    .filter(id => cabsData[id]) // Only keep IDs that exist in current data
                    .map(id => ({
                        id: id,
                        text: cabsData[id]
                    }));

                $.each(cabsData, function(id, title) {
                    const isChecked = cabids.includes(id.toString());
                    dropdownMenu.append(`
                            <li>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input cab-details-checkbox" 
                                        id="cab-detail-${id}" value="${id}" 
                                        ${isChecked ? 'checked' : ''} 
                                        data-text="${title}">
                                    <label class="form-check-label" for="cab-detail-${id}">${title}</label>
                                </div>
                            </li>
                        `);
                });

                // Update UI with initial selections
                if (selectedCabDetails.length > 0) {
                    dropdownText.text(selectedCabDetails.map(opt => opt.text).join(', '));
                    hiddenInput.val(selectedCabDetails.map(opt => opt.id).join(','));
                }

                // Single unified change handler
                $(document).off('change', '.cab-details-checkbox').on('change', '.cab-details-checkbox', function() {
                    const pricingval = $('#pricing_calculator').val();
                    const currentSelections = [];

                    // Get all checked cab details
                    $('.cab-details-checkbox:checked').each(function() {
                        currentSelections.push({
                            id: $(this).val(),
                            text: $(this).data('text')
                        });
                    });

                    // Get all checked main cab types
                    const selectedCabIds = $('.cab-checkbox:checked').map(function() {
                        return $(this).val();
                    }).get();

                    // Update UI
                    dropdownText.text(
                        currentSelections.length > 0 ?
                        currentSelections.map(opt => opt.text).join(', ') :
                        'Select options'
                    );
                    hiddenInput.val(currentSelections.map(opt => opt.id).join(','));

                    // Make AJAX call if we have selections
                    if (currentSelections.length > 0) {
                        $.ajax({
                            url: "{{ route('admin.c_cabs_details') }}",
                            type: 'POST',
                            data: {
                                pricingval: pricingval,
                                cabdetails: currentSelections.map(d => d.id),
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

                // Trigger change for pre-checked boxes
                if (cabids.length > 0) {
                    $('.cab-details-checkbox:checked').trigger('change');
                }

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