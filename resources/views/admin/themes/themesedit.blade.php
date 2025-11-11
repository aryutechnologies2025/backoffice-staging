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
        <b><a href="/dashboard">Dashboard</a> > <a href="/themes">Theme</a> > <a class="edit">Edit</a></b>
    </div>

</div>

<div class="row mb-5">
    <div class="col-lg-12">
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form id="form_valid" action="{{ route('admin.themes_update', ['id'=>$themes_details->id]) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <div class="row align-items-center">
                        <div class="col-lg-2 photo-upload-field">
                            <!-- <label class="fw-bold mt-4"> Image </label>
                                <div class="row d-flex mb-4">
                                    <div class="col-lg-2"> -->
                            <div class="form-input text-center">
                                <!-- Existing image preview -->
                                @if ($themes_details->theme_pic)
                                <img id="file-ip-1-preview" src="{{ asset($themes_details->theme_pic) }}" alt="{{$themes_details->alternate_name}}" class="img-thumbnail mb-1" style="max-height: 200px; object-fit: cover;">
                                @else
                                <img id="file-ip-1-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg" alt="Add Pic" class="img-thumbnail mb-3">
                                @endif

                                <label for="file-ip-1" class="d-block text-center py-2" style="cursor: pointer;">
                                    <p class="text-center fw-light">Add Pic</p>
                                </label>
                                <input type="file" id="file-ip-1" name="image_1" accept="image/png, image/jpeg" onchange="validateImage(this)" required>
                                <!-- <label class="fw-bold mb-5 text-danger border-0"><small>* Upload size [600*120] *</small></label> -->
                            </div>
                        </div>
                        <!-- Input Section -->
                        <div class="col-lg-8">
                            <div class="row g-1"> <!-- Adjusted spacing for proper margin -->
                            <div id="file-ip-1-error" class="text-danger"></div>
                               
                            <!-- First Input: Upload Image Name -->
                                <div class="add_form col-lg-6">
                                    <label class="fw-bold mt-4 mb-2">Upload Image Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Rename the Photo" id="upload_image_name" name="upload_image_name" value="{{ $themes_details->upload_image_name }}" class="form-control py-2 rounded-3 shadow-sm  " required> <!-- Added mt-4 here -->
                                </div>
                                <div class="add_form col-lg-6 px-4">
                                    <label class="fw-bold mt-4 mb-2">Alternate Image Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Alternate Name" id="alternate_image_name" name="alternate_image_name" value="{{ $themes_details->alternate_name }}" class="form-control py-2 rounded-3 shadow-sm " required> <!-- Added mt-4 here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-4">
                    <div class="add_form col-md-6 pe-4">
                        <label class="fw-bold mb-2 "> Title <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Title" id="theme_name" name="theme_name" value="{{ $themes_details->themes_name }}" class="form-control py-2 rounded-3 shadow-sm" required>
                    </div>

                     <div class="add_form col-md-6 pe-4">
                        <label class="fw-bold mb-2 ">Description <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Description" id="description" name="description" value="{{ $themes_details->description }}" class="form-control py-2 rounded-3 shadow-sm" required>
                    </div>
                    <div class="add_form col-md-6">
                        <label class="fw-bold mb-2 ">Order <span class="text-danger">*</span></label>
                        <input type="number" placeholder="Order" id="list_order" name="list_order" value="{{ $themes_details->list_order }}" class="form-control py-2 rounded-3 shadow-sm" required>
                    </div>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col">
                        <label class="add_head fw-bold ">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input check_bx" type="checkbox" id="status" name="status" {{ $themes_details->status ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>


                <div class="text-center mt-4">
                    <a href="{{ route('admin.themes_list') }}">
                        <button type="button" class="cancel-btn"> Cancel </button>
                    </a>
                    <button class="submit-btn sbmtBtn ms-4"> Submit </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

