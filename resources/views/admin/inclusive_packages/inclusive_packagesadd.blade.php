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

.px-5 {

    padding-left: 0rem !important;
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
</style>
<div class="container-wrapper py-5">
    <div class="row">
        <div class="col-lg-12">
            <b><a href="/dashboard">Dashboard</a> > <a href="/all-inclusive-package">Program</a> > <a
                    class="add">Add</a></b>
            <br>
            <br>
            <h3 class="fw-bold">{{$title}}</h3>
        </div>

        <!-- FORM -->
        <form id="form_valid" action="{{ route('admin.inclusive_package_insert') }}" method="POST" autocomplete="off"
            enctype="multipart/form-data">
            @csrf
            <!-- 1.INFORMATION -->
            <div class="row mb-3">
                <div class="col">
                    <div class="form-body px-5 rounded-4">
                        <h4 class="fw-bold mb-5">01. Information</h4>
                        <!-- Theme and Destination -->
                        <div class="mb-3">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class=" mb-2">Theme <span class="text-danger">*</span></label>
                                    <select id="themes_name" name="themes_name"
                                        class="form-select py-2 rounded-3 shadow-sm" required>
                                        <option value="">Select Theme</option>
                                        @foreach($themes as $id => $name)
                                        <option value="{{ $id }}" @if(old('themes_name')=='{{ $id }}' ) selected @endif>
                                            {{ $name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="mb-2">Destination <span class="text-danger">*</span></label>
                                    <select id="cities_name" name="cities_name"
                                        class="form-select py-2 rounded-3 shadow-sm" required>
                                        <option value="">Select Destination</option>
                                        @foreach($cities as $id => $name)
                                        <option value="{{ $id }}" @if(old('cities_name')=='{{ $id }}' ) selected @endif>
                                            {{ $name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="mb-2">Title <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Title" id="title" name="title"
                                        class="form-control py-2 rounded-3 shadow-sm" required
                                        value="{{ old('title') }}">

                                </div>
                            </div>
                        </div>
                        <!-- <input type="hidden" id="plan_description" name="plan_description[]">
                            <label class="form-label fw-bold mb-2">Plan Description <span class="text-danger">*</span></label>
                            <div id="summernote3" style="height: 200px;"></div> -->
                        <!-- Title -->
                        <!-- <div class="row mt-4">
                            <div class="col">
                                <label class="fw-bold mb-2">Title <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Title" id="title" name="title"
                                    class="form-control py-2 rounded-3 shadow-sm" required value="{{ old('title') }}">
                            </div>
                        </div> -->

                        <!-- Program Description -->
                        <div class="row mt-4">
                            <div class="col">
                                <label class="mb-2">Program Description <span class="text-danger">*</span></label>
                                <textarea id="program_description" name="program_description"
                                    style="display:none;"></textarea>
                                <div id="summernote1" style="height: 200px;"></div>
                            </div>
                        </div>

                        <!-- Flags -->

                        <div class="row g-2 mt-4">
                            <label class="mb-1">Flags <span class="text-danger">*</span></label>

                            <div class="col-md-3 col-sm-3">

                                <div class="form-check d-flex justify-content-center align-items-center">
                                    <input type="checkbox" class="form-check-input me-3" id="popular_program"
                                        name="prop_cat[]" value="popular_program">
                                    <label class="form-check-label" for="popular_program">Popular Program</label>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-3">
                                <div class="form-check d-flex align-items-center">
                                    <input type="checkbox" class="form-check-input me-3" id="upcoming_program"
                                        name="prop_cat[]" value="upcoming_program">
                                    <label class="form-check-label" for="upcoming_program">Upcoming Program</label>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-3">
                                <div class="form-check d-flex align-items-center">
                                    <input type="checkbox" class="form-check-input me-3" id="featured" name="prop_cat[]"
                                        value="featured">
                                    <label class="form-check-label" for="featured">Featured</label>
                                </div>
                            </div>
                        </div>


                        <!-- Cover Image -->
                        <div class="row mt-4">
                            <div class="col-md-2 h-25">
                                <label for="file-ip-1" class="form-label ">Cover Image</label>
                                <div class="form-input text-start pt-2 pb-0">
                                    <label for="file-ip-1" class="d-block pt-4">
                                        <img id="file-ip-1-preview"
                                            src="/assets/image/dashboard/innerpece_addpic_icon.svg"
                                            class="img-thumbnail" onchange="validateImage(this)">
                                        <p class="mt-2">Add Pic</p>
                                    </label>
                                    <input type="file" id="file-ip-1" name="cover_img" class="form-control"
                                        accept="image/png, image/jpeg, image/svg+xml" onchange="validateImage(this)">
                                    <div id="file-ip-1-error" class="text-danger"></div>

                                    <small class="text-danger d-block mt-2 text-center">* Upload size [640x120]</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row g-3 pt-4">
                                    <div id="file-ip-1-error" class="text-danger"></div>
                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                    <script>
                                    function showError(message) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: message,
                                        });
                                    }
                                    </script>
                                    <div class="col-12 pt-4 forms">
                                        <label class="">Upload Image Name <span class="text-danger">*</span></label>
                                        <input type="text" id="upload_image_name" name="upload_image_name"
                                            placeholder="Rename the Photo" value="{{ old('upload_image_name') }}"
                                            class="form-control py-2 rounded-3 shadow-sm w-50" required>
                                    </div>
                                    <div class="col-12 forms">
                                        <label class="">Alternate Image Name <span class="text-danger">*</span></label>
                                        <input type="text" id="alternate_image_name" name="alternate_image_name"
                                            placeholder="Alternate Name" value="{{ old('alternate_image_name') }}"
                                            class="form-control py-2 rounded-3 shadow-sm w-50" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Gallery Images -->
                        <div class="row mt-4 py-5">
                            <div class="col">
                                <label class="py-3">Gallery Image</label>
                                <div id="photo-upload-container" class="row g-6">
                                    <!-- Dynamically added photo containers will go here -->
                                </div>
                                <div class="text mt-3">
                                    <button type="button" class="btn-add rounded border-0 px-3 py-3 text-white"
                                        onclick="addPhotoField()">
                                        <i class="fa fa-plus" aria-hidden="true"></i> Add Photo
                                    </button>
                                </div>
                            </div>
                        </div>

                        <script>
                        // Function to add a new photo field dynamically
                        let photoCount = 1; // Initialize photo count

                        function addPhotoField() {
                            const container = document.getElementById('photo-upload-container');
                            photoCount++; // Increment photo count

                            // Create a new photo upload field with delete button
                            const photoField = document.createElement('div');
                            photoField.classList.add('col-lg-2', 'photo-upload-field');

                            photoField.innerHTML = `
                        <div class="form-input">
                            <label for="file-ip-${photoCount}" class="px-4 py-2 text-center">
                                <img class="text-center mt-3" id="file-ip-${photoCount}-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg">
                                <p class="text-center fw-light mt-3"> Add Pic</p>
                            </label>
                            <input type="file" name="img_${photoCount}" id="file-ip-${photoCount}" data-number="${photoCount}" accept="image/png, image/jpeg, image/svg+xml" onchange="previewImage(event, this)">
                            <label class="mb-4 text-danger d-block w-100 text-center">
                                * Upload size [640*120] *
                            </label>
                            <button type="button" class="btn btn-danger mt-2" onclick="deletePhoto(this)">Delete</button>
                        </div>
                    `;

                            // Append the new photo field to the container
                            container.appendChild(photoField);
                        }

                        // Function to preview the image after selection
                        function previewImage(event, inputElement) {
                            const file = event.target.files[0];
                            const reader = new FileReader();

                            reader.onload = function() {
                                const preview = inputElement.previousElementSibling.querySelector('img');
                                preview.src = reader.result;
                            };

                            if (file) {
                                reader.readAsDataURL(file);
                            }
                        }

                        // Function to delete the image container
                        function deletePhoto(button) {
                            const photoField = button.closest('.photo-upload-field');
                            photoField.remove();
                        }
                        </script>



                        <!-- 2. LOCATION -->
                        <div class="row mb-3">

                            <h4 class="fw-bold mb-2 py-2">2. Location</h4>
                            <div class="col-md-3 mb-3">
                        <textarea class="form-control rounded-3 shadow-sm" id="location" name="location" placeholder=" Enter City & State & Country" required>{{ old('location') }}</textarea>
                    </div>

                        </div>



                        <!-- 3. TOUR PLANNING -->
                        <!-- 3. TOUR PLANNING -->
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-body px-5 rounded-4">
                                    <h4 class="fw-bold mb-2">3. Tour Planning <span class="text-danger">*</span></h4>
                                    <div id="plan-container">
                                        <!-- Plan Item -->
                                        <div class="plan-item">
                                            <!-- Title and Subtitle in One Row -->
                                            <div class="row g-2 mt-2">
                                                <div class="col-lg-5">
                                                    <label class="form-label mb-2">Plan Title</label>
                                                    <input type="text" name="plan_title[]" id="plan_title"
                                                        class="form-control py-2 rounded-3 shadow-sm"
                                                        placeholder="Plan Title" required>
                                                </div>
                                                <div class="col-lg-5">
                                                    <label class="form-label mb-2">Plan Subtitle</label>
                                                    <input type="text" name="plan_subtitle[]"
                                                        class="form-control py-2 rounded-3 shadow-sm"
                                                        placeholder="Plan Subtitle" required>
                                                </div>
                                            </div>
                                            <!-- Description and Add Button in Second Row -->
                                            <div class="row g-2 mt-3 align-items-center">
                                                <div class="col-lg-10">
                                                    <label class="form-label mb-2">Plan Description <span
                                                            class="text-danger">*</span></label>
                                                    <input type="hidden" id="plan_description"
                                                        name="plan_description[]">
                                                    <div id="summernote3" style="height: 200px;"></div>
                                                </div>
                                                <div class="col-lg-1 ">
                                                    <button type="button" id="add-plan-btn"
                                                        class="btn-add rounded-3 border-0 px-3 py-2 text-white">
                                                        <!-- <button type="button" class="btn-add rounded border-0 px-1 py-3 text-white" -->

                                                        <i class="fa fa-plus" aria-hidden="true"></i> Add
                                                    </button>
                                                    <div class="row g-2 mt-3 text-end">
                                                        <div class="col-lg-1">
                                                            <a href="#" class="table-link danger remove-plan">
                                                                <span class="fa-stack">
                                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Delete Button -->
                                            <!-- <div class="row g-2 mt-3 text-end">
                        <div class="col">
                            <a href="#" class="table-link danger remove-plan">
                                <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </div>
                    </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row mb-2">
                            <div class="col">
                                <div class="form-body px-5 rounded-4">
                                    <h4 class="fw-bold mb-2">04.Tour Date & Time</h4>
                                    <div class="mb-3">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-4">
                                                <label class="mb-2">Start Date <span class="text-danger"></span></label>
                                                <input type="date" class="form-control py-2 rounded-3 shadow-sm"
                                                    name="start_date" id="start_date" value="{{old('start_date')}}"
                                                    required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class=" mb-2">Return Date <span
                                                        class="text-danger"></span></label>
                                                <input type="date" class="form-control py-2 rounded-3 shadow-sm"
                                                    name="return_date" id="return_date" value="{{old('return_date')}}"
                                                    required>
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
                        </div>

                        <!-- 4. Needed -->
                        <div class="row mb-2">
                            <div class="col">
                                <div class="form-body px-5 rounded-4">
                                    <h4 class="fw-bold mb-2">04.Rooms and Beds</h4>
                                    <div class="mb-3">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-3">
                                                <label class=" mb-2">Rooms<span class="text-danger"></span></label>
                                                <input type="number" class="form-control py-2 rounded-3 shadow-sm"
                                                    name="total_room" id="total_room" value="{{old('total_room')}}"
                                                    required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="mb-2">Bath Rooms<span class="text-danger"></span></label>
                                                <input type="number" class="form-control py-2 rounded-3 shadow-sm"
                                                    name="bath_room" id="bath_room" value="{{old('bath_room')}}"
                                                    required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class=" mb-2">Bed Rooms</label>
                                                <input type="number" class="form-control py-2 rounded-3 shadow-sm"
                                                    id="bed_room" name="bed_room" value="{{old('bed_room')}}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class=" mb-2">Hall</label>
                                                <input type="number" class="form-control py-2 rounded-3 shadow-sm"
                                                    id="hall" name="hall" value="{{old('hall')}}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- 5.PRICING -->
                        <div class="row mb-3 ">
                            <div class="col">
                                <div class="form-body px-5  rounded-4">
                                    <h4 class="fw-bold mb-1">05. Pricing</h4>
                                    <div class="mb-1">
                                        <div class="row mb-2">
                                            <div class="col-md-3 py-4">
                                                <label class="pb-2">Member Capacity <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" id="member_capacity" name="member_capacity"
                                                    class="form-control py-2 rounded-3 shadow-sm"
                                                    placeholder="Member Capacity" required
                                                    value="{{old('member_capacity')}}">
                                            </div>
                                            <div class="col-md-3 py-4">
                                                <label class="pb-2">Sprit Amount</label>
                                                <select id="mem_type" name="mem_type"
                                                    class="form-select py-2 rounded-3 shadow-sm" required>
                                                    <option value="">Select</option>
                                                    <option value="perhead" @if(old('mem_type')=='perhead' ) selected
                                                        @endif>Perhead
                                                    </option>
                                                    <option value="full" @if(old('mem_type')=='full' ) selected @endif>
                                                        Full</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 py-4">
                                                <label class="pb-2 "> Actual Amount <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" id="price" name="price"
                                                    class="form-control py-2 rounded-3 shadow-sm"
                                                    placeholder="Actual Amount" value="{{old('price')}}" required>
                                            </div>
                                            <div class="col-md-3 py-4">
                                                <label class=" pb-2 "> Discount Amount <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" id="actual_price" name="actual_price"
                                                    class="form-control py-2 rounded-3 shadow-sm"
                                                    placeholder="Actual Price" value="{{old('actual_price')}}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 6. Payment Policy -->
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-body px-5 rounded-4">
                                    <h4 class="fw-bold mb-4 ps-0">6. Payment Policy</h4>
                                    <div id="camp-rule-container">
                                        <div class="row g-2 mb-1 align-items-center camp-rule-field">
                                            <!-- Payment Policy Field -->
                                            <label class="mb-1">Payment Policy <span
                                                    class="text-danger">*</span></label>

                                            <div class="col-md-11">
                                                <input type="text" name="camp_rule[]" id="camp_rule"
                                                    class="form-control py-2 rounded-3 shadow-sm"
                                                    placeholder="Payment Policy" required>
                                            </div>
                                            <!-- Add Button -->
                                            <div class="col-md-1">
                                                <button type="button"
                                                    class="btn-add rounded border-0 px-4 py-2 text-white"
                                                    onclick="addCampRuleField()">
                                                    <i class="fa fa-plus" aria-hidden="true"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- 7.Important info -->
                        <div class="row mb-2">
                            <div class="col">
                                <div class="form-body px-5  rounded-4">
                                    <h4 class="fw-bold mb-3">06.Important info <span class="text-danger">*</span></h4>
                                    <div class="mb-1">
                                        <div class="row g-2 mb-1">
                                            <div class="col">
                                                <input type="hidden" id="important_info" name="important_info">
                                                <!-- <textarea id="important_info" class="container__textarea p-5 textarea-feild" name="important_info" value="{{old('important_info')}}" required></textarea> -->
                                                <!-- <div class="mb-3">
                                    <div id="commentEditor4" class="form-control" style="height: 200px;"></div>
                                </div> -->
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
                                    <h4 class="fw-bold mb-2">07.Program Inclusion </h4>
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

                        <div class="row mb-2">
                            <div class="col">
                                <div class="form-body px-5 rounded-4">
                                    <h4 class="fw-bold mb-3">9. Location</h4>
                                    <div class="mb-1">
                                        <div id="camp-rule-container">
                                            <div class="row g-2 mb-2 camp-rule-field">
                                                <div class="col">
                                                    <label class="mb-2">Google Map<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="google_map" id="google_map"
                                                        class="form-control py-2 rounded-3 shadow-sm"
                                                        placeholder="Google Map" required value="{{old('google_map')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col">
                                <div class="form-body px-5 rounded-4">
                                    <h4 class="fw-bold mb-3">09. Food Menu</h4>
                                    <div class="mb-1">
                                        <!-- Food Menu Inputs in One Row -->
                                        <div class="row g-2">
                                            <!-- Breakfast -->
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
                                            <!-- Lunch -->
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
                                            <!-- Dinner -->
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
                        </div>
                        <!-- 8. AMENITIES -->
                        <div class="row mb-2">
                            <div class="col">
                                <div class="form-body px-5 py-3 rounded-4">
                                    <h4 class="fw-bold mb-3">10. Amenities</h4>
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
                                    <h4 class="fw-bold mb-3">11. Food and Beverages</h4>
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
                                    <h4 class="fw-bold mb-3">12. Activities</h4>
                                    <div class="d-flex flex-wrap">
                                        @foreach($activities as $index => $item)
                                        <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                                            <div class="form-check d-flex align-items-center">
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
                                    <h4 class="fw-bold mb-3">13. Safety Features</h4>
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
                            width: 25px;
                            height: 25px;
                        }

                        /* Ensure responsiveness on all screen sizes */
                        @media (max-width: 768px) {
                            .custom-checkbox {
                                width: 20px;
                                height: 20px;
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
                </div>



        </form>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#summernote1,#summernote2,#summernote3,#summernote4,#summernote5,#summernote6,#summernote7,#summernote8')
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


    // document.addEventListener('DOMContentLoaded', function() {
    //     let planCount = 1; // Initialize with existing plan count

    //     // Function to clone the existing plan item
    //     function createPlanFields() {
    //         planCount++; // Increment plan count
    //         const container = document.getElementById('plan-container');
    //         const template = container.querySelector('.plan-item');

    //         // Clone the template
    //         const newPlan = template.cloneNode(true);

    //         // Update IDs and Names for new plan items
    //         newPlan.querySelectorAll('input, textarea').forEach((field) => {
    //             // Ensure name is array-style and IDs are unique
    //             field.name = field.name.replace(/\[\]$/, []);
    //             field.id = field.id + '-' + planCount;
    //         });

    //         container.appendChild(newPlan);
    //     }

    //     // Event listener for the "Add" button
    //     document.getElementById('add-plan-btn').addEventListener('click', function() {
    //         createPlanFields();
    //     });

    //     // Event delegation to handle removal of plan items
    //     document.getElementById('plan-container').addEventListener('click', function(event) {
    //         if (event.target.closest('.remove-plan')) {
    //             event.preventDefault();
    //             const planItem = event.target.closest('.plan-item');
    //             planItem.remove();
    //         }
    //     });
    // });

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
            newPlan.querySelectorAll('input, textarea').forEach((field) => {
                // Ensure name is array-style and IDs are unique
                field.name = field.name.replace(/\[\]$/, []);
                field.id = field.id + '-' + planCount;
            });

            container.appendChild(newPlan);

            // Re-initialize Summernote for newly added fields
            $('#summernote3').summernote({
                height: 200
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
                <input type="text" name="camp_rule[]" class="form-control py-3 rounded-3 shadow-sm" placeholder="Payment Policy" required>
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
    </script>
    @endsection

    <script>
    function validateImage(input) {
        const file = input.files[0];
        const errorElement = document.getElementById('file-ip-1-error');
        const previewElement = document.getElementById('file-ip-1-preview');

        // Clear previous error messages and reset preview
        errorElement.textContent = '';
        previewElement.src = '/assets/image/dashboard/innerpece_addpic_icon.svg';

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = new Image();

                img.onload = function() {
                    console.log('Image loaded with width: ' + img.width + ' and height: ' + img.height);


                    if (1200 > img.width || 200 > img.height) {
                        console.log("Dimensions exceed allowed size!");
                        showError('Image sixe must be max of  1200x120 pixels.');
                        input.value = '';
                    } else {
                        console.log("Image dimensions are valid.");

                        previewElement.src = e.target.result;
                    }
                };

                // Handling image load error
                img.onerror = function() {
                    console.log("Error loading image file."); // Debugging log for errors
                    showError("Error loading the image file. It might be corrupted or not a valid image.");
                };

                img.src = e.target.result;
            };

            // Read the image as a data URL
            reader.readAsDataURL(file);
        } else {
            showError('No file selected.');
        }
    }

    function showError(message) {
        const errorElement = document.getElementById('file-ip-1-error');
        errorElement.textContent = message;
    }
    </script>