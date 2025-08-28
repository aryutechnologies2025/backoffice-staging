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


</style>

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">Client Review Edit</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a href="/user">User</a> > <a class="edit">Edit</a></b>
    </div>

</div>


<div class="row mb-5">
    <div class="col-lg-12">
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form id="form_valid" action="{{ route('admin.stay_review_update', ['id'=>$client_details->id]) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                
                    <div class="row g-2 mb-4">
                        <div class="add_form col-lg-6 pe-4">
                            <label class="fw-bold mb-2">Stay Name <span class="text-danger">*</span></label>
                            <select id="program_name" name="program_name" class="form-control py-2 rounded-3 shadow-sm" required>
                                <option value="">Select Stay</option>
                                @foreach($program_dts as $id => $name)
                                <option value="{{ $id }}" {{ old('program_name', $client_details->stag_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="add_form  col-lg-6">
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
                        <div class="add_form  col">
                            <label class="fw-bold mb-2">Client Review <span class="text-danger">*</span></label>
                            <input type="hidden" id="client_review" name="client_review" value="{{ old('client_review', strip_tags($client_details->review)) }}">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div id="summernote">{!! $client_details->review !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mb-4">
                       
                        <div class="add_form  col-lg-6">
                            <label class="fw-bold mb-2">Rating <span class="text-danger">*</span></label>
                            <input type="number" class="form-control py-2 rounded-3 shadow-sm" name="rating" id="rating" value="{{ old('rating', $client_details->rating) }}" required>
                        </div>
                    </div>

                  
                </div>

                <div class="col-lg-12 text-center mt-5">
                    <a href="{{ route('admin.stay_review_list') }}">
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
