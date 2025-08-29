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
</style>
<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
         <b><a href="/dashboard">Dashboard</a> > <a href="/activities">Activities</a> > <a class="edit">Edit</a></b>
    </div>

</div>

<div class="row mb-5">
    <div class="col-lg-12">
        <div class="form-body px-4 mb-5 rounded-4">
            <form id="form_valid" action="{{ route('admin.activities_update', ['id' => $activities_details->id]) }}"
                method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">

                    <div class="row align-items-center">
                        <div class="col-lg-2 photo-upload-field">
                            <div class="form-input text-center">
                                <!-- <label class="fw-bold mt-4"> Image </label>
                                <div class="row d-flex mb-4">
                                    <div class="col-lg-2">
                                        <div class="form-input"> -->
                                <!-- Existing image preview -->
                                @if ($activities_details->activities_pic)
                                <img id="file-ip-1-preview" src="{{ asset($activities_details->activities_pic) }}"
                                    alt="{{$activities_details->alternate_name}}" class="img-thumbnail mb-3"
                                    style="max-height: 150px; object-fit: cover;">
                                @else
                                <img id="file-ip-1-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg"
                                    alt="{{$activities_details->alternate_name}}" class="img-thumbnail mb-3">
                                @endif

                                <label for="file-ip-1" class="d-block text-center py-3" style="cursor: pointer;">
                                    <p class="text-center fw-light">Add Pic</p>
                                </label>
                                <input type="file" id="file-ip-1" name="image_1" accept="image/png, image/jpeg"
                                    required>
                                <div id="file-ip-1-error" class="text-danger"></div>
                                <!-- <label class="fw-bold mb-5 text-danger border-0"><small>* Upload size [30*30]
                                        *</small></label> -->
                            </div>
                        </div>
                        <!-- Input Section -->
                        <div class="col-lg-8">
                            <div class="row g-2">
                                <!-- Adjusted spacing for proper margin -->
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
                                <div class="add_form col-lg-6 pe-4">
                                    <label class="fw-bold mt-4 mb-2">Upload Image Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" placeholder="Rename the Photo" id="upload_image_name"
                                        name="upload_image_name" value="{{$activities_details->upload_image_name }}"
                                        class="form-control py-2 rounded-3 shadow-sm  " required>
                                    <!-- Added mt-4 here -->
                                </div>
                                <div class="add_form col-lg-6 ">
                                    <label class="fw-bold mt-4 mb-2">Alternate Image Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" placeholder="Alternate Name" id="alternate_image_name"
                                        name="alternate_image_name" value="{{$activities_details->alternate_name }}"
                                        class="form-control py-2 rounded-3 shadow-sm " required>
                                    <!-- Added mt-4 here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-4">
                    <div class="add_form col-lg-6">
                        <label class="fw-bold  mb-2"> Activities <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Activities" id="activities" name="activities"
                            class="form-control py-2 rounded-3 shadow-sm" required
                            value="{{ $activities_details->activities }}">
                    </div>

                    <div class="row g-3">
                        <div class="col">
                            <label class="add_head fw-bold">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input check_bx" type="checkbox" id="status" name="status"
                                    {{ $activities_details->status ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 text-center mt-5">
                    <a href="{{ route('admin.activitieslist') }}">
                        <button type="button" class="cancel-btn"> Cancel </button>
                    </a>
                    <button class="submit-btn sbmtBtn ms-4"> Submit </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
<script>
function previewImage(event) {
    const preview = document.getElementById('file-ip-1-preview');
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function() {
        preview.src = reader.result;
    };

    if (file) {
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
<script>
function validateImage(input) {
    const file = input.files[0];
    const errorElement = document.getElementById('file-ip-1-error');
    const previewElement = document.getElementById('file-ip-1-preview');

    // Reset previous error messages and preview
    errorElement.textContent = '';
    previewElement.src = '/assets/image/dashboard/innerpece_addpic_icon.svg';

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            const img = new Image();

            img.onload = function() {
                // Check image dimensions
                if (img.width > 30 || img.height > 30) {
                    showError('Image size must not exceed 30x30 pixels.');
                    input.value = ''; // Clear the input
                } else {
                    // Valid image, update preview
                    previewElement.src = e.target.result;
                }
            };

            img.onerror = function() {
                showError('Error loading the image. It might be corrupted or not a valid image.');
                input.value = ''; // Clear the input
            };

            img.src = e.target.result;
        };

        reader.readAsDataURL(file);
    } else {
        showError('No file selected.');
    }
}

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