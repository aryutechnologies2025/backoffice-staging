@extends('layouts.app')
@Section('content')
<style>
a:hover {
    color: red;
}

a {
    color: rgb(37, 150, 190);
}

.edit {
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
}

.mb-1 {
    margin-bottom: .5rem !important;
}

.px-5 {

    padding-left: 1rem !important;
}
</style>
<div class="container-wrapper pt-5">
    <div class="row">
        <div class="col-lg-12">
            <b><a href="/dashboard">Dashboard</a> > <a href="/all-inclusive-package">Program</a> > <a
                    class="edit">Edit</a></b>
            <br>
            <br>
            <h3 class="fw-bold">{{$title}}</h3>
        </div>

        <!-- FORM -->
        <form id="form_valid" action="{{ route('admin.inclusive_package_update', $package_details->id) }}" method="POST"
            autocomplete="off" enctype="multipart/form-data">
            @csrf
            <!-- 1.INFORMATION -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="form-body px-4 py-4 mb-5 rounded-4">
                        <h4 class="fw-bold mb-4">1. Information</h4>

                        <!-- Themes, Destination, and Title in One Line -->
                        <div class="row g-3 mb-4">
                            <div class="col-lg-4">
                                <label class="fw-bold mb-2">Themes</label>
                                <select id="themes_name" name="themes_name"
                                    class="form-select py-2 rounded-3 shadow-sm">
                                    <option value="">Select Theme</option>
                                    @foreach($themes as $id => $name)
                                    <option value="{{ $id }}" {{ $id == $selectedthemeId ? 'selected' : '' }}>
                                        {{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="fw-bold mb-2">Destination <span class="text-danger">*</span></label>
                                <select id="cities_name" name="cities_name" class="form-select py-2 rounded-3 shadow-sm"
                                    required>
                                    <option value="">Select Destination</option>
                                    @foreach($cities_dts as $id => $name)
                                    <option value="{{ $id }}" {{ $id == $selectedCityId ? 'selected' : '' }}>{{ $name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="fw-bold mb-2">Title <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Title" id="title" name="title"
                                    class="form-control py-2 rounded-3 shadow-sm" required
                                    value="{{$package_details->title}}">
                            </div>
                        </div>

                        <!-- Program Description -->
                        <div class="mb-4">
                            <label class="fw-bold mb-2">Program Description <span class="text-danger">*</span></label>
                            <textarea id="program_description" name="program_description" style="display:none;"></textarea>

                            @php
                            $plain_text_description = htmlspecialchars(is_array($package_details->program_description) ?
                            json_encode($package_details->program_description) : $package_details->program_description);
                            @endphp
                            <div id="summernote1" class="form-control py-3 shadow-sm"
                                style="height: 250px; overflow-y: auto;">{{$plain_text_description}}</div>
                        </div>

                        <!-- Flags -->
                        <div class="row g-2 mb-4">
                            <label class="fw-bold mb-2">Flags</label>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input me-1" id="popular_program"
                                        name="prop_cat[]" value="popular_program"
                                        {{ in_array('popular_program', $selectedprogram) ? 'checked' : '' }}>
                                    <label for="popular_program" class="form-check-label">Popular Program</label>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input me-1" id="upcoming_program"
                                        name="prop_cat[]" value="upcoming_program"
                                        {{ in_array('upcoming_program', $selectedprogram) ? 'checked' : '' }}>
                                    <label for="upcoming_program" class="form-check-label">Upcoming Program</label>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input me-1" id="featured" name="prop_cat[]"
                                        value="featured" {{ in_array('featured', $selectedprogram) ? 'checked' : '' }}>
                                    <label for="featured" class="form-check-label">Featured</label>
                                </div>
                            </div>
                        </div>
                        <!-- Cover Image -->
                        <div class="mb-4">
                            <label class="fw-bold">Cover Image</label>
                            <div class="row align-items-center">
                                <!-- Cover Image Preview -->
                                <div class="col-lg-3 text-center">
                                    @if($package_details->cover_img)
                                    <img id="file-ip-100-preview" src="{{ asset($package_details->cover_img) }}"
                                        alt="Cover Image" class="rounded-3 shadow-sm"
                                        style="max-width: 250px; max-height: 250px; object-fit: cover;">
                                    @else
                                    <img id="file-ip-100-preview"
                                        src="/assets/image/dashboard/innerpece_addpic_icon.svg" alt="Add Pic"
                                        class="rounded-3 shadow-sm" style="max-width: 250px; max-height: 250px;">
                                    @endif
                                    <p class="mt-2 fw-light">Add Pic</p>
                                    <input type="file" id="file-ip-100" name="cover_img"
                                        accept="image/png, image/jpeg, image/svg+xml" onchange="previewImage(event)"
                                        class="form-control mt-3">
                                    <div id="file-ip-100-error" class="text-danger mt-2"></div>
                                    <small class="text-danger d-block mt-2">* Upload size [640x120]</small>

                                </div>

                                <!-- Upload and Alternate Image Names -->
                                <div class="col-lg-9">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="fw-bold">Upload Image Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" placeholder="Rename the Photo" id="upload_image_name"
                                                name="upload_image_name" value="{{$package_details->upload_image_name}}"
                                                class="form-control py-2 rounded-3 shadow-sm" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="fw-bold">Alternate Image Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" placeholder="Alternate Name" id="alternate_image_name"
                                                name="alternate_image_name" value="{{$package_details->alternate_name}}"
                                                class="form-control py-2 rounded-3 shadow-sm" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div id="photo-upload-container" class="row">
                            <label class="fw-bold mt-4"> Gallery Image </label>
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
                                        <img class="text-center mt-3" id="file-ip-{{ $key }}-preview"
                                            src="{{ asset($image) }}" alt="Image Preview">
                                        <p class="text-center fw-light mt-3">Edit Pic</p>
                                    </label>
                                    <input type="file" name="img_{{ $key }}" id="file-ip-{{ $key }}"
                                        data-number="{{ $key }}" accept="image/*">
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
            {{-- <div class="row mb-1">
            <div class="col">
                <div class="form-body px-5  rounded-4">
                    <h4 class="fw-bold mb-3">2.Location</h4>
                    <div class="mb-3">
                        <div class="row mb-2">
                            <input type="hidden" id="address" name="address">
                            <label class="fw-bold mb-2 ">Address Details <span class="text-danger">*</span></label>
                            <div class="col-lg-12  ">
                                <!-- <input type="text" id="address" name="address" class="form-control py-3 rounded-3 shadow-sm" placeholder="Address" required value=""> -->
                                <!-- <textarea id="program_descriptions" class="container__textarea p-5 textarea-feild" name="address" required>{{$package_details->address}}</textarea>
            -->
            <!-- <div class="mb-3">
                                    <div id="commentEditor2" class="form-control " style="height: 200px;"></div>
                                </div> -->
            @php
            $plain_text_address = strip_tags($package_details->address);
            @endphp
            <div class=" mt-2">
                <div class="row">
                    <div class="col-lg-12 ">
                        <div id="summernote2">{{$plain_text_address}}</div>
                    </div>
                </div>
            </div>
    </div>
</div>
<div class="row g-2 mb-2 d-flex justify-content-around">
    <div class="col">
        <label class="fw-bold  mb-4">City <span class="text-danger">*</span></label>
        <input type="text" placeholder="City" id="city" name="city" class="form-control py-2 rounded-3 shadow-sm"
            required value="{{$package_details->city}}">
    </div>
    <div class="col">
        <label class="fw-bold mb-4 ">State <span class="text-danger">*</span></label>
        <input type="text" placeholder="State" id="state" name="state" class="form-control py-2 rounded-3 shadow-sm"
            required value="{{$package_details->state}}">
    </div>
</div>
<div class="row mb-4">
    <div class="col-lg-12">
        <label class="fw-bold mb-4 ">Country <span class="text-danger">*</span></label>
        <input type="text" id="country" name="country" class="form-control py-2 rounded-3 shadow-sm"
            placeholder="Country" required value="{{$package_details->country}}">
    </div>
</div>
</div>
</div>
</div>
</div> --}}


<div class="row mb-1">
    <div class="col">
        <div class="form-body px-5 py-4 rounded-4">
            <h4 class="fw-bold mb-3">2. Location</h4>
            <div class="row mb-4">
                @foreach($address_dts->chunk(4) as $chunk)
                <div class="row mb-3">
                    @foreach($chunk as $address)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <div class="form-check d-flex align-items-center">
                            <input type="checkbox" class="me-2" id="address-{{ $address->id }}"
                                name="address_services[]" value="{{ $address->id }}" @if(in_array((string) $address->id,
                            $selectedAddress)) checked @endif>
                            <label class="form-label" for="address-{{ $address->id }}">{{ $address->title }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


<!-- 3.TOUR PLANNING -->
<div class="row mb-1">
    <div class="col">
        <div class="form-body px-5 rounded-4">
            <h4 class="fw-bold mb-3">3. Tour Planning <span class="text-danger">*</span></h4>
            <div id="plan-container">
                @php
                // Decode JSON if needed
                $tourPlanning = json_decode($package_details->tour_planning, true);
                @endphp
                @foreach($tourPlanning['plan_subtitle'] as $index => $title)
                <div class="row g-2 mt-3 d-flex justify-content-between">
                    <!-- Plan Title -->
                    <div class="col-lg-5 col-md-6">
                        <label for="plan_title_{{ $index }}" class="form-label fw-bold">Plan Title</label>
                        <input type="text" name="plan_title[]" id="plan_title_{{ $index }}"
                            class="form-control py-2 rounded-3 shadow-sm" placeholder="Plan Title" required
                            value="{{ isset($tourPlanning['plan_title'][$index]) ? $tourPlanning['plan_title'][$index] : '' }}">
                    </div>

                    <!-- Plan Subtitle -->
                    <div class="col-lg-5 col-md-6">
                        <label for="plan_subtitle_{{ $index }}" class="form-label fw-bold">Plan Subtitle</label>
                        <input type="text" name="plan_subtitle[]" id="plan_subtitle_{{ $index }}"
                            class="form-control py-2 rounded-3 shadow-sm" placeholder="Plan Subtitle" required
                            value="{{ $tourPlanning['plan_subtitle'][$index] }}">
                    </div>
                </div>

                <div class="plan-item mb-3">
                    <div class="mt-2">
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="hidden" id="plan_description" name="plan_description[]">
                                <label class="form-label form-label-top form-label-auto fw-bold mb-2">Plan Description
                                    <span class="text-danger">*</span></label>
                                @php
                                $plain_text_plan_description = strip_tags($tourPlanning['plan_description'][$index]);
                                @endphp
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-lg-12 ">
                                            <div id="summernote3">{{$plain_text_plan_description}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>




<!-- 4.TOUR DATE & TIME -->
<div class="row mb-2">
    <div class="col">
        <div class="form-body px-5 rounded-4">
            <h4 class="fw-bold mb-2">4.Tour date & Time</h4>
            <div class="mb-2">
                <div class="row g-2 mb-2">
                    <!-- Start Date -->
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <label class="fw-bold mb-3">Start Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control py-2 rounded-3 shadow-sm" name="start_date"
                            id="start_date" value="{{$package_details->start_date}}" required>
                    </div>

                    <!-- Return Date -->
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <label class="fw-bold mb-3">Return Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control py-2 rounded-3 shadow-sm" name="return_date"
                            value="{{$package_details->return_date}}" id="return_date" required>
                    </div>

                    <!-- Total No. of Days -->
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <label class="fw-bold mb-3">Total No. of Days</label>
                        <input type="text" class="form-control py-2 rounded-3 shadow-sm" id="total_days"
                            name="total_days" value="{{$package_details->total_days}}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- 5.PRICING -->
<div class="row mb-3">
    <div class="col">
        <div class="form-body px-5  rounded-4 ">
            <h4 class="fw-bold mb-3">5. Pricing</h4>
            <div class="mb-2">
                <div class="row mb-2">
                    <div class="col-lg-6">
                        <label class="fw-bold mb-2 ">Member Capacity <span class="text-danger">*</span></label>
                        <input type="text" id="member_capacity" name="member_capacity"
                            class="form-control py-2 rounded-3 shadow-sm mb-2" placeholder="Member Capacity" required
                            value="{{$package_details->member_capacity}}">
                    </div>
                    <div class="col-lg-6">
                    <label class="fw-bold mb-2 ">Sprit Amount <span class="text-danger">*</span></label>
                    <select id="mem_type" name="mem_type" class="form-select py-2 rounded-3 shadow-sm mb-2"
                            required>
                            <option value="">Select</option>
                            <option value="perhead" {{ $package_details->member_type == 'perhead' ? 'selected' : '' }}>
                                Perhead</option>
                            <option value="full" {{ $package_details->member_type == 'full' ? 'selected' : '' }}>Full
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="fw-bold mb-2 ">Actual Amount <span class="text-danger">*</span></label>
                        <input type="text" id="price" name="price" class="form-control py-2 rounded-3 shadow-sm"
                            placeholder="Actual Amount" value="{{$package_details->price}}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="fw-bold mb-2">Discount Amount <span class="text-danger">*</span></label>
                        <input type="text" id="actual_price" name="actual_price"
                            class="form-control py-2 rounded-3 shadow-sm" placeholder="Discount Amount"
                            value="{{$package_details->actual_price}}" required>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- 6.Rule And Regulations -->
<div class="row mb-3">
    <div class="col">
        <div class="form-body px-5 rounded-4">
            <h4 class="fw-bold mb-3">6. Payment Policy <span class="text-danger">*</span></h4>
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
                    <div class="row g-2 mb-2 camp-rule-field">
                        <div class="col">
                            <input type="text" name="camp_rule[]" class="form-control py-2 rounded-3 shadow-sm"
                                placeholder="Rule And Regulations" value="{{ $rule }}" required>
                        </div>
                        <div class="col-lg-1 mt-2 text-end">
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
                    <button type="button" class="btn-add rounded border-0 px-5 py-2 text-white"
                        onclick="addCampRuleField()">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- 7. Important info -->
<div class="row mb-3">
    <div class="col">
        <div class="form-body px-4 py-3 rounded-4">
            <h4 class="fw-bold mb-3">7. Important info <span class="text-danger">*</span></h4>
            <div>
                <input type="hidden" id="important_info" name="important_info">
                @php
                $plain_text_important_info = strip_tags($package_details->important_info);
                @endphp
                <div class="mt-2">
                    <div id="summernote4">{{$plain_text_important_info}}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 8. Program Inclusion -->
<div class="row mb-3">
    <div class="col">
        <div class="form-body px-4 py-3 rounded-4">
            <h4 class="fw-bold mb-3">8. Program Inclusion</h4>
            <div>
                <input type="hidden" id="program_inclusion" name="program_inclusion">
                @php
                $plain_text_program_inclusion = strip_tags($package_details->program_inclusion);
                @endphp
                <div class="mt-2">
                    <div id="summernote5">{{$plain_text_program_inclusion}}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 9. Location -->
<div class="row mb-3">
    <div class="col">
        <div class="form-body px-4 py-3 rounded-4">
            <h4 class="fw-bold mb-3">9. Location</h4>
            <div>
                <div class="col-lg-6">
                    <label class="fw-bold mb-3">Google Map<span class="text-danger">*</span></label>
                    <input type="text" id="google_map" name="google_map" class="form-control py-3 rounded-3 shadow-sm"
                        placeholder="Google Map" required value="{{$package_details->google_map}}">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 10. Food Menu -->
<div class="row mb-3">
    <div class="col">
        <div class="form-body px-4 py-3 rounded-4">
            <h4 class="fw-bold mb-3">10. Food Menu</h4>
            <div class="row g-2">
                <!-- Breakfast -->
                <div class="col">
                    <label class="form-label form-label-top form-label-auto fw-bold mb-2">Breakfast</label>
                    <input type="hidden" id="break_fast" name="break_fast">
                    @php
                    $plain_text_break_fast = strip_tags($package_details->break_fast);
                    @endphp
                    <div class="mt-3">
                        <div id="summernote6">{{$plain_text_break_fast}}</div>
                    </div>
                </div>
                <!-- Lunch -->
                <div class="col">
                    <label class="form-label form-label-top form-label-auto fw-bold mb-2">Lunch</label>
                    <input type="hidden" id="lunch" name="lunch">
                    @php
                    $plain_text_lunch = strip_tags($package_details->lunch);
                    @endphp
                    <div class="mt-3">
                        <div id="summernote7">{{$plain_text_lunch}}</div>
                    </div>
                </div>
                <!-- Dinner -->
                <div class="col">
                    <label class="form-label form-label-top form-label-auto fw-bold mb-2">Dinner</label>
                    <input type="hidden" id="dinner" name="dinner">
                    @php
                    $plain_text_dinner = strip_tags($package_details->dinner);
                    @endphp
                    <div class="mt-3">
                        <div id="summernote8">{{$plain_text_dinner}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 11. Amenities -->
<div class="row mb-3">
    <div class="col">
        <div class="form-body px-4 py-3 rounded-4">
            <h4 class="fw-bold mb-3">11. Amenities</h4>
            <div class="row g-2 mb-2">
                @foreach($amenities_dts->chunk(4) as $chunk)
                @foreach($chunk as $amenity)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="amenity-{{ $amenity->id }}"
                            name="amenity_services[]" value="{{ $amenity->id }}" @if(in_array((string) $amenity->id,
                        $selectedAmenities)) checked @endif>
                        <label class="form-check-label"
                            for="amenity-{{ $amenity->id }}">{{ $amenity->amenity_name }}</label>
                    </div>
                </div>
                @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- 12. Food & Beverages -->
<div class="row mb-3">
    <div class="col">
        <div class="form-body px-4 py-3 rounded-4">
            <h4 class="fw-bold mb-3">12. Food & Beverages</h4>
            <div class="row g-2 mb-3">
                @foreach($foodBeverages_dts->chunk(6) as $chunk)
                @foreach($chunk as $item)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="food-beverage-{{ $item->id }}"
                            name="food_beverages[]" value="{{ $item->id }}" @if(in_array((string) $item->id,
                        $selectedfood_beverages)) checked @endif>
                        <label class="form-check-label"
                            for="food-beverage-{{ $item->id }}">{{ $item->food_beverage }}</label>
                    </div>
                </div>
                @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- 13. Activities -->
<div class="row mb-3">
    <div class="col">
        <div class="form-body px-4 py-3 rounded-4">
            <h4 class="fw-bold mb-3">13. Activities</h4>
            <div class="row g-2 mb-3">
                @foreach($activities_dts->chunk(6) as $chunk)
                @foreach($chunk as $item)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="activities-{{ $item->id }}"
                            name="activities[]" value="{{ $item->id }}" @if(in_array((string) $item->id,
                        $selectedactivities)) checked @endif>
                        <label class="form-check-label" for="activities-{{ $item->id }}">{{ $item->activities }}</label>
                    </div>
                </div>
                @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- 14. Safety Features -->
<div class="row mb-3">
    <div class="col">
        <div class="form-body px-4 py-3 rounded-4">
            <h4 class="fw-bold mb-3">14. Safety Features</h4>
            <div class="row g-2 mb-3">
                @foreach($safety_features_dts->chunk(6) as $chunk)
                @foreach($chunk as $item)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="safety_features-{{ $item->id }}"
                            name="safety_features[]" value="{{ $item->id }}" @if(in_array((string) $item->id,
                        $selectedsafety_features)) checked @endif>
                        <label class="form-check-label"
                            for="safety_features-{{ $item->id }}">{{ $item->safety_features }}</label>
                    </div>
                </div>
                @endforeach
                @endforeach
            </div>

            <div class="row g-2 mt-3">
                <div class="col">
                    <label class="fw-bold">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input check_bx" name="status" type="checkbox" id="status"
                            {{ $package_details->status ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
       

    <div class="col-lg-12 text-end mt-5 py-5">
        <a href="{{ route('admin.inclusive_package_list') }}">
            <button type="button" class="btn btn-outline-secondary px-4 py-3 fw-bold cancel-btn">
                Cancel
            </button>
        </a>
        <button type="submit" class="btn btn-primary ms-4 px-5 py-3 fw-bold submit-btn sbmtBtn ">
            Submit
        </button>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>

</form>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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
    $('#summernote1,#summernote2,#summernote3,#summernote4,#summernote5,#summernote6,#summernote7,#summernote8')
        .summernote({
            height: 200 // Set the height of the editor
        });

    // Update hidden input when Summernote content changes
    $('#summernote1').on('summernote.change', function() {
        $('#program_description').val($(this).val());
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
            if (file.size <= 20 * 1024 * 1024) { // 2 MB limit
                if (file.type === 'image/png' || file.type === 'image/jpeg' || file.type === 'image/svg+xml') {
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

function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('file-ip-2-preview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection