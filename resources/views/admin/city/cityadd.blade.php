@extends('layouts.app')
@section('content')
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

    .container-wrapper {
        padding: 0 30px;
    }

    /* .form-control {
        width: 80%;
    } */

    .error-message {
        color: red;
        font-size: 0.9rem;
    }
</style>

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b>
            <a href="/dashboard">Dashboard</a> >
            <a href="/destination-c">Destination</a> >
            <a class="add">Add</a>
        </b>
    </div>

</div>


<div class="row mb-5">
    <div class="col-lg-12">
        <!-- INFORMATION -->
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form class=" px-4" id="form_valid" action="{{ route('admin.city_insert') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <div class="row align-items-center">
                        <!-- Program Image Upload -->
                        <div class="col-lg-2 photo-upload-field">

                                <label class="fw-bold px-2 py-2">Add Program Image <span class="text-danger">*</span></label>
                        <div class="form-input text-center">

                                <label for="file-ip-program" class="px-4 py-3 text-center">
                                    <img class="text-center mt-3" id="file-ip-program-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg" alt="Program Image Preview">
                                    <p class="text-center fw-light mt-3">Add Pic</p>
                                </label>
                                <input type="file" id="file-ip-program" name="program_image" accept="image/png, image/jpeg" required>
                                <div id="file-ip-program-error" class="error-message"></div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row g-1">
                                <div class="add_form col-lg-6 pe-4">
                                    <label class="fw-bold">Program Image Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Rename the Program Photo" id="program_upload_image_name" name="program_upload_image_name" value="{{ old('program_upload_image_name') }}" class="form-control py-2 rounded-3 shadow-sm" required>
                                </div>
                                <div class="add_form col-lg-6">
                                    <label class="fw-bold">Program Alternate Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Program Alternate Name" id="program_alternate_image_name" name="program_alternate_image_name" value="{{ old('program_alternate_image_name') }}" class="form-control py-2 rounded-3 shadow-sm" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center mt-4">
                        <!-- Stay Image Upload -->
                        <div class="col-lg-2 photo-upload-field">
                            <label class="fw-bold px-2 py-2">Add Stay Image <span class="text-danger">*</span></label>
                            <div class="form-input text-center">
                                <label for="file-ip-stay" class="px-4 py-3 text-center">
                                    <img class="text-center mt-3" id="file-ip-stay-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg" alt="Stay Image Preview">
                                    <p class="text-center fw-light mt-3">Add Pic</p>
                                </label>
                                <input type="file" id="file-ip-stay" name="stay_image" accept="image/png, image/jpeg" required>
                                <div id="file-ip-stay-error" class="error-message"></div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row g-1">
                                <div class="add_form col-lg-6 pe-4">
                                    <label class="fw-bold">Stay Image Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Rename the Stay Photo" id="stay_upload_image_name" name="stay_upload_image_name" value="{{ old('stay_upload_image_name') }}" class="form-control py-2 rounded-3 shadow-sm" required>
                                </div>
                                <div class="add_form col-lg-6">
                                    <label class="fw-bold">Stay Alternate Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Stay Alternate Name" id="stay_alternate_image_name" name="stay_alternate_image_name" value="{{ old('stay_alternate_image_name') }}" class="form-control py-2 rounded-3 shadow-sm" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-4">
                    <div class="add_form col-md-6 pe-4">
                        <label class="fw-bold mb-2">Title <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Title" id="city_name" name="city_name" value="{{ old('city_name') }}" class="form-control py-2 rounded-3 shadow-sm" required>
                    </div>
                    <div class="add_form col-md-6 pe-4">
                        <label class="fw-bold mb-2">Description <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Description" id="description" name="description" value="{{ old('description') }}" class="form-control py-2 rounded-3 shadow-sm" required>
                    </div>
                    <div class="add_form col-md-6">
                        <label class="fw-bold mb-2">Order <span class="text-danger">*</span></label>
                        <input type="number" placeholder="Order" id="list_order" name="list_order" value="{{ old('order') }}" class="form-control py-2 rounded-3 shadow-sm" required>
                    </div>
                </div>

                <div class="row g-2 mb-4">
                    <div class="add_form col">
                        <label class="fw-bold">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('admin.citylist') }}">
                        <button type="button" class="cancel-btn">Cancel</button>
                    </a>
                    <button type="submit" class="submit-btn sbmtBtn ms-4">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // function validateImage(input) {
    //     const file = input.files[0];
    //     const errorElement = document.getElementById('file-ip-1-error');
    //     const previewElement = document.getElementById('file-ip-1-preview');

    //     // Clear previous error messages and reset preview
    //     errorElement.textContent = '';
    //     previewElement.src = '/assets/image/dashboard/innerpece_addpic_icon.svg';

    //     if (file) {
    //         const reader = new FileReader();

    //         reader.onload = function (e) {
    //             const img = new Image();

    //             img.onload = function () {
    //                 // Check dimensions
    //                 if (img.width > 56 && img.height > 56) {
    //                     showSweetError('Image size must not exceed 56x56 pixels.');
    //                     input.value = ''; // Clear the input
    //                 } else {
    //                     previewElement.src = e.target.result; // Set the preview
    //                 }
    //             };

    //             img.onerror = function () {
    //                 showSweetError('Error loading the image file. It might be corrupted or not a valid image.');
    //             };

    //             img.src = e.target.result; // Trigger the onload/onerror handlers
    //         };

    //         reader.readAsDataURL(file); // Read the file as a data URL
    //     } else {
    //         showSweetError('No file selected.');
    //     }
    // }

    function showSweetError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: message,
        });
    }
</script>