@extends('layouts.app')
@section('content')
<style>
    a:hover {
        color: red;
    }
    a{
        color:rgb(37, 150, 190);
    }
    .edit{
        color:blue;
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
<div class="container-wrapper pt-5">
    <div class="row">
    <b><a href="/dashboard" >Dashboard</a> > <a href="/safety_features" >Safety Features</a> > <a class="edit" >Edit</a></b>
        <br>
        <br>
        <h3 class="fw-bold">{{$title}}</h3>
    </div>
</div>
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="form-body px-4 mb-5 rounded-4">
                <form id="form_valid" action="{{ route('admin.safety_features_update', ['id' => $safety_feature_details->id]) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        
                        <div class="row align-items-center">
        <div class="col-lg-2 photo-upload-field">
            <div class="form-input text-center">
                                <!-- <label class="fw-bold mt-4"> Safety Features Logo </label>
                                <div class="row d-flex mb-4">
                                    <div class="col-lg-2">
                                        <div class="form-input"> -->
                                            <!-- Existing image preview -->
                                            @if ($safety_feature_details->safety_features_pic)
                                            <img id="file-ip-1-preview" src="{{ asset($safety_feature_details->safety_features_pic) }}" alt="Thumbnail Preview" class="img-thumbnail mb-3" style="max-height: 150px; object-fit: cover;">
                                            @else
                                            <img id="file-ip-1-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg" alt="Add Pic" class="img-thumbnail mb-3">
                                            @endif

                                            <label for="file-ip-1" class="d-block text-center py-3" style="cursor: pointer;">
                                                <p class="text-center fw-light">Add Pic</p>
                                            </label>
                                            <input type="file" id="file-ip-1" name="image_1" accept="image/png, image/jpeg" onchange="previewImage(event)">
                                            <div id="file-ip-1-error" class="text-danger"></div>
                                            <label class="fw-bold mb-5 text-danger border-0"><small>* Upload size [640*120] *</small></label>
            </div>
        </div>
        <!-- Input Section -->
        <div class="col-lg-8">
            <div class="row g-2"> <!-- Adjusted spacing for proper margin -->
                <!-- First Input: Upload Image Name -->
                <div class="col-lg-6">
                    <label class="fw-bold mt-4">Upload Image Name <span class="text-danger">*</span></label>
                    <input type="text" placeholder="Rename the Photo" id="upload_image_name" name="upload_image_name" value="{{$safety_feature_details->upload_image_name }}" class="form-control py-2 rounded-3 shadow-sm  " required> <!-- Added mt-4 here -->
                </div>
                <div class="col-lg-6 ">
                    <label class="fw-bold mt-4">Alternate Image Name <span class="text-danger">*</span></label>
                    <input type="text" placeholder="Alternate Name" id="alternate_image_name" name="alternate_image_name" value="{{$safety_feature_details->alternate_name }}" class="form-control py-2 rounded-3 shadow-sm " required> <!-- Added mt-4 here -->
                </div>
            </div>
        </div>
    </div>
</div>

                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Safety Features <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Safety Features" id="safety_features" name="safety_features" class="form-control py-2 rounded-3 shadow-sm" required value="{{ $safety_feature_details->safety_features }}">
                            </div>
                        

                        <div class="row g-2">
                            <div class="col">
                                <label class="fw-bold">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input check_bx" type="checkbox" id="status" name="status" {{ $safety_feature_details->status ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 text-end mt-5">
                        <a href="{{ route('admin.safety_features_list') }}">
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
