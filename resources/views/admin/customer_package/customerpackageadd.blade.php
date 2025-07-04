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
</style>
<div class="container mt-5 mb-5">
    <div class="col-lg-12">
        <b><a href="/dashboard">Dashboard</a> > <a class="" href="/enquiry">Booking</a></b> > <a class="enquiry" href="">Add Booking</a>
        <br><br>
        <h3 class="fw-bold">{{$title}}</h3>
    </div>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form class="bg-white p-4 rounded-3" action="{{ route('admin.CustomerPackage_insert') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row d-flex gap-5">
            <div class="col-md-5 mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input id="name" type="text" class="form-control" name="name">
            </div>

            <div class="col-md-5 mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input id="phone" type="number" class="form-control" name="phone_number">
            </div>

            <div class="col-md-5 mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email" class="form-control" name="email">
            </div>

            <!-- <div class="col-md-5 mb-3">
                    <label for="type"  class="form-label">Package Type</label>
                    <input id="type" type="text" class="form-control" name="package_type" required>
                    
                </div> -->
            <!-- Package Type Selector -->
            <div class="col-md-5 mb-3">
                <label for="title_id" class="form-label">Select Package Type</label>
                <select class="package" name="package_type" id="package" class="form-control">
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
                <h2>Package Details</h2>
                <!-- 1.INFORMATION -->
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="fw-bold mb-2">Title </label>
                        <input type="text" placeholder="Title" id="title" name="title"
                            class="form-control py-2 rounded-3 shadow-sm">
                    </div>



                    <!-- 2. LOCATION -->

                    <div class="row mb-2">
                        <div class="col">
                            <div class="form-body px-5  rounded-4">
                                <h4 class="fw-bold mb-3">01. Location</h4>
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
                            <div class="form-body px-5 rounded-4">
                                <h4 class="fw-bold mb-2">02. Tour Planning <span class="text-danger">*</span></h4>
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
                            <div class="form-body px-5  rounded-4 ">
                                <h4 class="fw-bold mb-3">03. Pricing</h4>
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
                            <div class="form-body px-5 rounded-4">
                                <h4 class="fw-bold mb-4 ps-0">04. Payment Policy</h4>
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
                            <div class="form-body px-5  rounded-4">
                                <h4 class="fw-bold mb-3">05. Notes <span class="text-danger">*</span></h4>
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
                            <div class="form-body px-5  rounded-4">
                                <h4 class="fw-bold mb-2">06. Package Inclusion </h4>
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
                            <div class="form-body px-5  rounded-4">
                                <h4 class="fw-bold mb-2">07. Package Exclusion </h4>
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
                            <div class="form-body px-5 py-3 rounded-4">
                                <h4 class="fw-bold mb-3">08. Amenities</h4>
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
                            <div class="form-body px-5 py-3 rounded-4">
                                <h4 class="fw-bold mb-3">09. Food and Beverages</h4>
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
                            <div class="form-body px-5 py-3 rounded-4">
                                <h4 class="fw-bold mb-3">10. Activities</h4>
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
                    <div class="row mb-2">
                        <div class="col">
                            <div class="form-body px-5 py-3 rounded-4">
                                <h4 class="fw-bold mb-3">11. Safety Features</h4>
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

                    <div class="row g-2">
                        <div class="col">
                            <h4> <label class="fw-bold">Status</label></h4>
                            <div class="form-check form-switch d-flex align-items-center">
                                <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-3">
                            <label class="fw-bold mb-3 ">Order</label>
                            <input type="number" placeholder="Order" id="list_order" name="list_order"
                                value="{{old('order')}}" class="form-control py-2 rounded-3 shadow-sm">
                        </div>
                    </div>

                    <style>
                        .form-check-input {
                            transform: scale(1.5);
                            /* Increase the size of the checkbox */
                        }
                    </style>

                    <div class="col-lg-12 text-end mt-5">
                        <a href="{{ route('admin.inclusive_package_list') }}">
                            <button type="button" class="cancel-btn"> Cancel </button>
                        </a>
                        <button class="submit-btn sbmtBtn ms-4 mb-5"> Submit </button>
                    </div>
                </div>
    </form>
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

                        console.log(tourVal);

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

    $(document).on('click', '.remove-day', function() {
        $(this).closest('.day-block').remove();
    });
</script>
@endsection