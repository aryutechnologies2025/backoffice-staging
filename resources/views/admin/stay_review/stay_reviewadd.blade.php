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
        width: 100% !important;
    }

    .form-control {
        width: 70%;
    }
</style>
<div class="container-wrapper pt-5">
    <div class="row">
        <!-- <div class="col-lg-12"> -->
        <b><a href="/dashboard">Dashboard</a> > <a href="/stay_review">Stay Review</a> > <a class="add">Add</a></b>
        <br>
        <br>
        <h3 class="fw-bold">{{$title}}</h3>
    </div>
</div>
<!-- FORM -->
<div class="row mb-5">
    <div class="col">
        <div class="form-body px-4 mb-5 rounded-4">
            <form id="form_valid" action="{{ route('admin.stay_review_insert') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <div class="g-2 mb-2">

                        <div class="row g-2 mb-4">
                            <div class="col-md-6">
                                <label class="fw-bold mb-2"> Stay Name <span class="text-danger">*</span></label>
                                <select id="program_name" name="program_name" class="form-control py-2 rounded-3 shadow-sm" required>
                                    <option value="">Select Stay</option>
                                    @foreach($program_dts as $id => $name)
                                    <option value="{{ $id }}" @if(old('program_name')==$id) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-bold mb-2" id="label_textarea"> Client Name <span class="text-danger">*</span></label>
                                <!-- <input type="text" placeholder="Client Name" id="client_name" name="client_name" value="{{old('client_name')}}" class="form-control py-2 rounded-3 shadow-sm" required> -->

                                <select id="client_name" name="client_name" class="form-control py-2 rounded-3 shadow-sm" required>
                                    <option value="">Select User</option>
                                    @foreach($users as $id => $name)
                                    <option value="{{ $name->id }}" @if(old('client_name')==$name->id) selected @endif>{{ $name->first_name }} {{ $name->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="g-2 mb-2">
                                <div class="col">

                                    <label class=" fw-bold mb-2" id="label_textarea"> Client Review <span class="text-danger">*</span></label>
                                    <!-- <textarea id="program_description" class="container__textarea p-5 textarea-feild" name="client_review" required></textarea> -->
                                    <input type="hidden" id="client_review" name="client_review">
                                    <div class=" mt-2">
                                        <div class="row">
                                            <div class="col-lg-10 ">
                                                <div id="summernote" style="height: 200px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2 mb-4">
                                <div class="col-md-6">
                                    <label class="fw-bold mb-2" id="label_textarea">Rating <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control py-2 rounded-3 shadow-sm" name="rating" id="rating" value="{{ old('rating') }}" required>
                                </div>


                                <div class="text-end mt-4">
                                    <a href="{{ route('admin.stay_review_list') }}">
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
</script>
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
                    if (img.width > 600 && img.height > 120) {
                        console.log("Dimensions exceed allowed size!"); // Debugging log
                        showError('Image size must not exceed 600x120 pixels.');
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
