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
</style>
<div class="container-wrapper pt-5">
    <div class="row">
        <!-- <div class="col-lg-12"> -->
        <b><a href="/dashboard">Dashboard</a> > <a href="/food_beverage">Food Beverage</a> > <a class="add">Add</a></b>
        <br>
        <br>
        <h3 class="fw-bold">{{$title}}</h3>
    </div>
</div>
<!-- FORM -->
<div class="row mb-5">
    <div class="col-lg-12">
        <div class="form-body px-4 mb-5 rounded-4">
            <form id="form_valid" action="{{ route('admin.food_beverage_insert') }}" method="POST" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <div class="mb-3">

                    <div class="row align-items-center">
                        <div class="col-lg-2 photo-upload-field">
                            <!-- <label class="fw-bold mt-4"> Food&Beverage Logo </label>
                                <div class="row d-flex mb-4">
                                    <div class="col-lg-2"> -->
                            <div class="form-input text-center">
                                <label for="file-ip-1" class="px-4 py-3 text-center">
                                    <img class="text-center mt-3" id="file-ip-1-preview"
                                        src="/assets/image/dashboard/innerpece_addpic_icon.svg"
                                        alt="{{ old('alternate_image_name', 'Alternate Image Name') }}">
                                    <p class="text-center fw-light mt-3">Add Pic</p>
                                </label>
                                <input type="file" id="file-ip-1" name="image_1" accept="image/png, image/jpeg"
                                    onchange="validateImage(this)" required>
                                <div id="file-ip-1-error" class="text-danger"></div>
                                <label class="fw-bold mb-5 text-danger border-0"><small>* Upload size [30*30] *</small></label>

                            </div>
                        </div>
                        <!-- Input Section -->
                        <div class="col-lg-8">
                            <div class="row g-1">
                                <!-- First Input: Upload Image Name -->
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
                                <div class="col-lg-6">
                                    <label class="fw-bold">Upload Image Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Rename the Photo" id="upload_image_name"
                                        name="upload_image_name" value="{{ old('upload_image_name') }}"
                                        class="form-control py-2 rounded-3 shadow-sm" required>
                                </div>
                                <!-- Second Input: Alternate Image Name -->
                                <div class="col-lg-6">
                                    <label class="fw-bold">Alternate Image Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" placeholder="Alternate Name" id="alternate_image_name"
                                        name="alternate_image_name" value="{{ old('alternate_image_name') }}"
                                        class="form-control py-2 rounded-3 shadow-sm" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row g-2 mb-4">
                    <div class="col">
                        <label class="fw-bold mb-4 "> Food&Beverage Item <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Food&Beverage" id="food_beverage" name="food_beverage"
                            class="form-control py-2 rounded-3 shadow-sm" required value="{{old('food_beverage')}}">
                    </div>


                    <div class="row g-2">
                        <div class="col">
                            <label class="fw-bold ">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 text-end mt-5">
                    <a href="{{ route('admin.food_beveragelist') }}">
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

                // Check if the image exceeds the limit of 600x120
                if (img.width > 30 && img.height > 30) {
                    console.log("Dimensions exceed allowed size!"); // Debugging log
                    showError('Image size must not exceed 30x30 pixels.');
                    input.value = ''; // Clear the input if the size exceeds limits
                } else {
                    console.log("Image dimensions are valid.");
                    // Only show the image preview if dimensions are valid
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