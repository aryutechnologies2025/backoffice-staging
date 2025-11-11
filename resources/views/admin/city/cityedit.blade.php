@extends('layouts.app')
@section('content')
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


        border-radius: 10px;

    }
</style>

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a href="/destination-c">Destination</a> > <a class="edit">Edit</a></b>
    </div>

</div>

<div class="row mb-5">
    <div class="col-lg-12">
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form id="form_valid" action="{{ route('admin.city_update', ['id'=>$city_details->id]) }}" method="POST"
                autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <!-- Program Image Section -->
                    <div class="row align-items-center">
                        <div class="col-lg-2 photo-upload-field">
                            <label class="fw-bold px-2 py-2">Edit Program Image <span class="text-danger">*</span></label>

                            <div class="form-input text-center">
                                <!-- Program Image Preview -->
                                @if ($city_details->cities_pic)
                                <img id="program-image-preview" src="{{ asset($city_details->cities_pic) }}"
                                    alt="{{ $city_details->alternate_name }}" class="img-thumbnail mb-1"
                                    style="max-height: 200px; object-fit: cover;">
                                @else
                                <img id="program-image-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg"
                                    alt="Add Program Pic" class="img-thumbnail mb-3">
                                @endif

                                <label for="program-image-input" class="d-block text-center py-3" style="cursor: pointer;">
                                    <p class="text-center fw-light">Add Pic</p>
                                </label>
                                <input type="file" id="program-image-input" name="program_image" accept="image/png, image/jpeg">
                                <div id="program-image-error" class="text-danger"></div>
                            </div>
                        </div>

                        <!-- Program Input Section -->
                        <div class="col-lg-8">
                            <div class="row g-2">
                                <div class="add_form col-lg-6">
                                    <label class="fw-bold mt-4 mb-2">Program Image Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Rename the Program Photo" id="program_upload_image_name"
                                        name="program_upload_image_name" value="{{ $city_details->upload_image_name }}"
                                        class="form-control py-2 rounded-3 shadow-sm" required>
                                </div>
                                <div class="add_form col-lg-6">
                                    <label class="fw-bold mt-4 mb-2">Program Alternate Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Program Alternate Name" id="program_alternate_image_name"
                                        name="program_alternate_image_name" value="{{ $city_details->alternate_name }}"
                                        class="form-control py-2 rounded-3 shadow-sm" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stay Image Section -->
                    <div class="row align-items-center mt-4">
                        <div class="col-lg-2 photo-upload-field">
                            <label class="fw-bold px-2 py-2">Edit Stay Image <span class="text-danger">*</span></label>

                            <div class="form-input text-center">

                                <!-- Stay Image Preview -->
                                @if ($city_details->stay_images)
                                <img id="stay-image-preview" src="{{ asset($city_details->stay_images) }}"
                                    alt="{{ $city_details->stay_alternate_name }}" class="img-thumbnail mb-1"
                                    style="max-height: 200px; object-fit: cover;">
                                @else
                                <img id="stay-image-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg"
                                    alt="Add Stay Pic" class="img-thumbnail mb-3">
                                @endif

                                <label for="stay-image-input" class="d-block text-center py-3" style="cursor: pointer;">
                                    <p class="text-center fw-light">Add Pic</p>
                                </label>
                                <input type="file" id="stay-image-input" name="stay_image" accept="image/png, image/jpeg">
                                <div id="stay-image-error" class="text-danger"></div>
                            </div>
                        </div>

                        <!-- Stay Input Section -->
                        <div class="col-lg-8">
                            <div class="row g-2">
                                <div class="add_form col-lg-6">
                                    <label class="fw-bold mt-4 mb-2">Stay Image Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Rename the Stay Photo" id="stay_upload_image_name"
                                        name="stay_upload_image_name" value="{{ $city_details->stay_upload_image_name }}"
                                        class="form-control py-2 rounded-3 shadow-sm" required>
                                </div>
                                <div class="add_form col-lg-6">
                                    <label class="fw-bold mt-4 mb-2">Stay Alternate Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Stay Alternate Name" id="stay_alternate_image_name"
                                        name="stay_alternate_image_name" value="{{ $city_details->stay_alternate_name }}"
                                        class="form-control py-2 rounded-3 shadow-sm" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- JavaScript for Image Preview -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Program Image Preview
                        const programInput = document.getElementById('program-image-input');
                        const programPreview = document.getElementById('program-image-preview');

                        programInput.addEventListener('change', function() {
                            const file = this.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    programPreview.src = e.target.result;
                                    programPreview.style.display = 'block';
                                }
                                reader.readAsDataURL(file);
                            }
                        });

                        // Stay Image Preview
                        const stayInput = document.getElementById('stay-image-input');
                        const stayPreview = document.getElementById('stay-image-preview');

                        stayInput.addEventListener('change', function() {
                            const file = this.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    stayPreview.src = e.target.result;
                                    stayPreview.style.display = 'block';
                                }
                                reader.readAsDataURL(file);
                            }
                        });
                    });

                    // Remove required attribute for edit page (files are optional in edit)
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('program-image-input').removeAttribute('required');
                        document.getElementById('stay-image-input').removeAttribute('required');
                    });
                </script>

                <div class="row g-2 mb-4">

                    <div class="add_form  col-lg-6 pe-4">
                        <label class="fw-bold mb-2 "> Title <span class="text-danger">*</span></label>
                        <input type="text" placeholder="City Name" id="city_name" name="city_name"
                            class="form-control py-2 rounded-3 shadow-sm" value="{{ $city_details->city_name }}"
                            required>
                    </div>
                    <div class="add_form  col-lg-6 pe-4">
                        <label class="fw-bold mb-2 "> Description <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Description" id="description" name="description"
                            class="form-control py-2 rounded-3 shadow-sm" value="{{ $city_details->description }}"
                            required>
                    </div>
                    <div class="add_form  col-lg-6">
                        <label class="fw-bold mb-2 "> Order <span class="text-danger">*</span></label>
                        <input type="number" placeholder="Order" id="list_order" name="list_order"
                            value="{{ $city_details->list_order }}" class="form-control py-2 rounded-3 shadow-sm"
                            required>
                    </div>
                </div>

                <div class="row g-2 mb-4">
                    <div class="add_form  col">
                        <label class="fw-bold ">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input check_bx" type="checkbox" id="status" name="status"
                                {{ $city_details->status ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
        </div>

        <div class="col-lg-12 text-end mt-5">
            <a href="{{ route('admin.citylist') }}">
                <button type="button" class="cancel-btn"> Cancel </button>
            </a>
            <button class="submit-btn sbmtBtn ms-4"> Submit </button>
        </div>
        </form>
    </div>
</div>
</div>

</div>
@endsection

<script>
    // function validateImage(input) {
    //     const file = input.files[0];
    //     const errorElement = document.getElementById('file-ip-1-error');
    //     const previewElement = document.getElementById('file-ip-1-preview');

    //     // Reset previous error messages and preview
    //     errorElement.textContent = '';
    //     previewElement.src = '/assets/image/dashboard/innerpece_addpic_icon.svg';

    //     if (file) {
    //         const reader = new FileReader();

    //         reader.onload = function(e) {
    //             const img = new Image();

    //             img.onload = function() {
    //                 // Check image dimensions
    //                 if (img.width > 56 && img.height > 56) {
    //                     showError('Image size must not exceed 56x56 pixels.');
    //                     input.value = ''; // Clear the input
    //                 } else {
    //                     // Valid image, update preview
    //                     previewElement.src = e.target.result;
    //                 }
    //             };

    //             img.onerror = function() {
    //                 showError('Error loading the image. It might be corrupted or not a valid image.');
    //                 input.value = ''; // Clear the input
    //             };

    //             img.src = e.target.result;
    //         };

    //         reader.readAsDataURL(file);
    //     } else {
    //         showError('No file selected.');
    //     }
    // }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: message,
        });

        // Update the error element text
        const errorElement = document.getElementById('file-ip-1-error');
        errorElement.textContent = message;
    }
</script>