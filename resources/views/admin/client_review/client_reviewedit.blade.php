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

padding-top: 1% !important;
padding-bottom: 1% !important;
width: 100% !important;
}

.form-control {
width: 80%;
}
</style>


<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">Client Review Edit</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a href="/client_review">Client Review</a> > <a class="edit">Edit</a></b>
    </div>

</div>

<div class="row mb-5">
    <div class="col-lg-12">
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form id="form_valid" action="{{ route('admin.client_review_update', ['id'=>$client_details->id]) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                <div class="g-2 mb-4">
                        <div class="col">
                            <label class="fw-bold mt-2">Client Photo</label>
                            <div class="row d-flex mb-4">
                                <div class="col-lg-2">
                                    <div class="form-input">
                                        <!-- Existing image preview -->
                                        @if ($client_details->client_pic)
                                        <img id="file-ip-1-preview" src="{{ asset($client_details->client_pic) }}"alt="{{$client_details->alternate_name}}" class="img-thumbnail mb-3" style="max-height: 150px; object-fit: cover;">
                                        @else
                                        <img id="file-ip-1-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg" alt="{{$client_details->alternate_name}}" class="img-thumbnail mb-3">
                                        @endif

                                        <label for="file-ip-1" class="d-block text-center py-3" style="cursor: pointer;">
                                            <p class="text-center fw-light">Add Pic</p>
                                        </label>
                                        <input type="file" id="file-ip-1" name="image_1" accept="image/png, image/jpeg" required>
                                        <div id="file-ip-1-error" class="text-danger"></div>
                                        <!-- <label class="fw-bold mb-5 text-danger border-0"><small>* Upload size [640*120] *</small></label> -->
                                    </div>
                                </div>

                                <!-- Input Section -->
                                <div class="col-lg-8">
                                    <div class="row g-2">
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
                                        <div class="add_form col-lg-6">
                                            <label class="fw-bold mt-4 pb-2">Upload Image Name <span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Rename the Photo" id="upload_image_name" name="upload_image_name" value="{{ old('upload_image_name', $client_details->upload_image_name) }}" class="form-control py-2 rounded-3 shadow-sm">
                                        </div>
                                        <div class="add_form  col-lg-6 px-4">
                                            <label class="fw-bold mt-4 pb-2">Alternate Image Name <span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Alternate Name" id="alternate_image_name" name="alternate_image_name" value="{{ old('alternate_image_name', $client_details->alternate_name) }}" class="form-control py-2 rounded-3 shadow-sm" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-4">
                        <div class="add_form col-lg-6">
                            <label class="fw-bold mb-2">Program Name <span class="text-danger">*</span></label>
                            <select id="program_name" name="program_name" class="form-control py-2 rounded-3 shadow-sm" required>
                                <option value="">Select Program</option>
                                @foreach($program_dts as $id => $name)
                                <option value="{{ $id }}" {{ old('program_name', $client_details->package_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="add_form col-lg-6">
                            <label class="fw-bold mb-2">Client Name <span class="text-danger">*</span></label>
                            <select id="client_name" name="client_name" class="form-control py-2 rounded-3 shadow-sm" required>
                                    <option value="">Select User</option>
                                    @foreach($users as $id => $name)
                                    <option value="{{ $name->id }}" @if($name->id==$client_details->user_id) selected @endif>{{ $name->first_name }} {{ $name->last_name }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>

                    

                    

                    <div class="g-2 mb-4">
                        <div class="add_form col">
                            <label class="fw-bold mb-2">Client Review <span class="text-danger">*</span></label>
                            <input type="hidden" id="client_review" name="client_review" value="{{ old('client_review', strip_tags($client_details->client_review)) }}">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div id="summernote">{!! $client_details->comment !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mb-4">

                        <div class="add_form col-lg-6">
                            <label class="fw-bold mb-2">Review Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control py-2 rounded-3 shadow-sm" name="review_dt" id="review_dt" value="{{ old('review_dt', $client_details->review_dt) }}">
                        </div>
                        <div class="add_form col-lg-6">
                            <label class="fw-bold mb-2">Rating <span class="text-danger">*</span></label>
                            <input type="number" class="form-control py-2 rounded-3 shadow-sm" name="rating" id="rating" value="{{ old('rating', $client_details->rating) }}" required>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="add_form col">
                            <label class="fw-bold">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input check_bx" type="checkbox" id="status" name="status" {{ old('status', $client_details->status) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 text-center mt-5">
                    <a href="{{ route('admin.client_review_list') }}">
                        <button type="button" class="cancel-btn">Cancel</button>
                    </a>
                    <button class="submit-btn sbmtBtn ms-4">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

@endsection

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 200,
            callbacks: {
                onChange: function(contents) {
                    $('#client_review').val(contents); // Sync content to hidden input
                }
            }
        });
    });

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

        reader.onload = function (e) {
            const img = new Image();

            img.onload = function () {
                // Check image dimensions
                if (img.width > 600 || img.height > 120) {
                    showError('Image size must not exceed 600x120 pixels.');
                    input.value = ''; // Clear the input
                } else {
                    // Valid image, update preview
                    previewElement.src = e.target.result;
                }
            };

            img.onerror = function () {
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
