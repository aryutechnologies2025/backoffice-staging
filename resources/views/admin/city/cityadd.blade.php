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

    .form-body {
        padding: 1% 0 !important;
        width: 100% !important;
    }

    .form-control {
        width: 80%;
    }

    .error-message {
        color: red;
        font-size: 0.9rem;
    }
</style>

<div class="container-wrapper pt-5">
    <div class="row">
        <b>
            <a href="/dashboard">Dashboard</a> >
            <a href="/destination-c">Destination</a> >
            <a class="add">Add</a>
        </b>
        <br><br>
        <h3 class="fw-bold">{{ $title }}</h3>
    </div>
</div>

<div class="row mb-5">
    <div class="col-lg-12">
        <div class="form-body px-4 mb-5 rounded-4">
            <form class=" px-4" id="form_valid" action="{{ route('admin.city_insert') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 ">
                    <div class="row align-items-center">
                        <div class="col-lg-2 photo-upload-field">
                            <div class="form-input text-center">
                                <label for="file-ip-1" class="px-4 py-3 text-center">
                                    <img class="text-center mt-3" id="file-ip-1-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg"                                          alt="{{ old('alternate_image_name', 'Alternate Image Name') }}">

                                    <p class="text-center fw-light mt-3">Add Pic</p>
                                </label>
                                <input type="file" id="file-ip-1" name="image_1" accept="image/png, image/jpeg" required>
                                <div id="file-ip-1-error" class="error-message"></div>
                                <label class="fw-bold mb-5 text-danger border-0"><small>* Upload size [56x56] *</small></label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row g-1">
                                <div class="col-lg-6">
                                    <label class="fw-bold">Upload Image Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Rename the Photo" id="upload_image_name" name="upload_image_name" value="{{ old('upload_image_name') }}" class="form-control py-2 rounded-3 shadow-sm" required>
                                </div>
                                <div class="col-lg-6">
                                    <label class="fw-bold">Alternate Image Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Alternate Name" id="alternate_image_name" name="alternate_image_name" value="{{ old('alternate_image_name') }}" class="form-control py-2 rounded-3 shadow-sm" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-md-6">
                        <label class="fw-bold mb-2">Title <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Title" id="city_name" name="city_name" value="{{ old('city_name') }}" class="form-control py-2 rounded-3 shadow-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold mb-2">Order <span class="text-danger">*</span></label>
                        <input type="number" placeholder="Order" id="list_order" name="list_order" value="{{ old('order') }}" class="form-control py-2 rounded-3 shadow-sm" required>
                    </div>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col">
                        <label class="fw-bold">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
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

