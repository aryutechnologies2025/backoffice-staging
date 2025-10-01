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
<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">Add Stay Details</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a href="/stay_list">Stay</a> > <a
                class="add">Add</a></b>
    </div>

</div>
<div class="row mb-5">
    <div class="col-lg-12">
        <!-- INFORMATION -->
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <!-- FORM -->
            <form class="" id="form_valid" action="{{ route('admin.stay_details_insert') }}" method="POST" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <!-- 1.INFORMATION -->
                <div class="row mb-3">
                    <div class="col">
                        <div class="form-body  rounded-4">
                            <h4 class="add_head fw-bold mb-5 px-2 pt-1">Information</h4>


                            <div class="mb-3 px-2">
                                <div class="row">
                                    <!-- Theme and Destination -->
                                    <div class="add_form col-md-4 mb-3 ">
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
                                    <div class="add_form col-md-4">
                                        <label class="mb-2">District <span class="text-danger">*</span></label>
                                        <select id="district_name" name="district_name"
                                            class="form-select py-2 rounded-3 shadow-sm" required>
                                            <option value="" disabled selected>Select District</option>
                                            <!-- Districts will be populated dynamically -->
                                        </select>
                                    </div>




                                    <div class="add_form col-md-4">
                                        <label class="mb-2">Title <span class="text-danger">*</span></label>
                                        <input type="text" placeholder="Title" id="title" name="title"
                                            class="form-control py-2 rounded-3 shadow-sm"
                                            value="{{ old('title') }}" required>

                                    </div>
                                    <div class="add_form col-md-4 mt-2">
                                        <label class="mb-2">Tag Line <span class="text-danger">*</span></label>
                                        <input type="text" placeholder="Tag Line" id="tag_line" name="tag_line"
                                            class="form-control py-2 rounded-3 shadow-sm"
                                            value="{{ old('tag_line') }}" required>

                                    </div>
                                    <div class="add_form col-md-4 mt-2">
                                        <label class=" mb-2">Stay Location <span class="text-danger">*</span></label>
                                        <input type="text" placeholder="Stay Location - iframe" id="stay_location" name="stay_location"
                                            class="form-control py-2 rounded-3 shadow-sm"
                                            value="{{ old('stay_location') }}" required>

                                    </div>
                                </div>
                            </div>


                            <!-- Stay Location -->
                            <div class="row mt-4 px-2">
                                <div class="add_form col-md-6">
                                    <label class="mb-2">Location Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Stay Location - Name" id="stay_location_title" name="stay_location_title"
                                            class="form-control py-2 rounded-3 shadow-sm"
                                            value="{{ old('stay_location_title') }}" required>
                                </div>
                            </div>

                            <!-- Program Description -->
                            <div class="row mt-4 px-2">
                                <div class="add_form col">
                                    <label class="mb-2">Program Description <span class="text-danger">*</span></label>
                                    <textarea id="program_description" name="program_description"
                                        style="display:none;" required></textarea>
                                    <div id="summernote1" style="height: 200px;"></div>
                                </div>
                            </div>



                            <!-- Gallery Images -->
                            <div class="row mt-4 px-2 py-5">
                                <div class="col">
                                    <h4 class="add_head py-3 fw-bold">Gallery Image - Room</h4>
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








                            <!-- 5.PRICING -->
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="form-body px-2  rounded-4 ">
                                        <h4 class="add_head fw-bold mb-3"> Pricing</h4>
                                        <div class="mb-2">

                                            <div class="row mb-2">
                                                <div class="add_form col-lg-6 mb-3">
                                                    <label class="form-label form-label-top form-label-auto fw-bold mb-2">
                                                        Days
                                                    </label>
                                                    <input type="number" name="price_title" class="form-control py-2 rounded-3 shadow-sm"
                                                        placeholder="number of days">
                                                </div>
                                                <div class="add_form col-lg-6 ">
                                                    <label class="fw-bold mb-2">Actual Price <span class="text-danger">*</span></label>
                                                    <div class="position-relative">
                                                        <span class="position-absolute top-50 start-0 translate-middle-y ps-3">₹</span>
                                                        <input type="number" name="actual_price_amount" class="form-control py-2 ps-5 rounded-3 shadow-sm"
                                                            placeholder="Actual Amount">
                                                    </div>
                                                </div>
                                                <div class="add_form col-lg-6 mt-2">
                                                    <label class="fw-bold mb-2">Discount Price <span class="text-danger">*</span></label>
                                                    <div class="position-relative">
                                                        <span class="position-absolute top-50 start-0 translate-middle-y ps-3">₹</span>
                                                        <input type="number" name="price_amount" class="form-control py-2 ps-5 rounded-3 shadow-sm"
                                                            placeholder="Discount Amount">
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="row mb-1">
                                <div class="col">
                                    <div class="form-body px-2  rounded-4">
                                        <h4 class="add_head fw-bold mb-2">Stays Inclusion </h4>
                                        <div class="mb-2">
                                            <div class="row g-2 mb-2">
                                                <div class="col">
                                                    <input type="hidden" id="program_inclusion"
                                                        name="program_inclusion">
                                                    <!-- <textarea id="important_info" class="container__textarea p-5 textarea-feild" name="important_info"
                                                        value="{{ old('important_info') }}" required></textarea> -->
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
                                    <div class="form-body px-2  rounded-4">
                                        <h4 class="add_head fw-bold mb-2">Stays Exclusion </h4>
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


                            <!-- 8. AMENITIES -->
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="form-body px-2 py-3 rounded-4">
                                        <h4 class="add_head fw-bold mb-3"> Amenities</h4>
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
                                    <div class="form-body px-2 py-3 rounded-4">
                                        <h4 class="add_head fw-bold mb-3"> Food and Beverages</h4>
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
                                    <div class="form-body px-2 py-3 rounded-4">
                                        <h4 class="add_head fw-bold mb-3">Activities</h4>
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
                                    <div class="form-body px-2 py-3 rounded-4">
                                        <h4 class="add_head fw-bold mb-3"> Safety Features</h4>
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





                            <br>

                            <div class="row g-2 px-2">
                                <div class="col">
                                    <h4> <label class="add_head fw-bold">Status</label></h4>
                                    <div class="form-check form-switch d-flex align-items-center">
                                        <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 px-2 mt-2">
                                <div class="col-lg-3">
                                    <label class="fw-bold mb-2">Order <span class="text-danger">*</span></label>
                                    <input type="number" placeholder="Order" id="list_order" name="list_order"
                                        value="{{old('order')}}" class="form-control py-2 rounded-3 shadow-sm" required>
                                </div>
                            </div>

                            <style>
                                .form-check-input {
                                    transform: scale(1.5);
                                    /* Increase the size of the checkbox */
                                }
                            </style>

                            <div class="col-lg-12 text-center mt-5">
                                <a href="{{ route('admin.staylist') }}">
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
    </div>
</div>
</div>