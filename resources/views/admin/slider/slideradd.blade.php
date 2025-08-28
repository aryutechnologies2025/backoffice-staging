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
        padding-left: 30px;
        padding-right: 30px;

    }

    /* .form-control {
        width: 80%;
    } */
</style>
<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
         <b>
            <a href="/dashboard">Dashboard</a> >
            <a href="/slider">Slider</a> >
            <a class="add">Add</a>
        </b>
    </div>

</div>
<!-- FORM -->

<div class="row mb-5">
    <div class="col-lg-12">
        <!-- INFORMATION -->
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form id="form_valid" action="{{ route('admin.slider_insert') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <!-- Image Section -->
                <div class="mb-3">
                    <div class="row align-items-center w-50% ">
                        <div class="col-lg-2 photo-upload-field w-50% ">
                            <div class="form-input text-center ">
                                <label for="file-ip-1" class="px-4 py-3 ">
                                    <img id="file-ip-1-preview"
                                         src="/assets/image/dashboard/innerpece_addpic_icon.svg"
                                         alt="{{ old('alternate_image_name', 'Alternate Image Name') }}">
                                    <p class="text-center fw-light mt-3">Add Pic</p>
                                </label>
                                <input type="file" id="file-ip-1" name="image_1" accept="image/png, image/jpeg" onchange="validateImage(this)" required>
                                <label class="fw-bold mb-5 text-danger border-0">
                                    <small>* Upload size [up to 600x120] *</small>
                                </label>
                            </div>
                        </div>
                        <!-- Input Section -->
                        <div class="col-lg-8">
                            <div class="row g-1">
                                <div id="file-ip-1-error" class="text-danger"></div>
                               

                                <div class="add_form col-lg-6">
                                    <label class="fw-bold">Upload Image Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Rename the Photo" id="upload_image_name"
                                           name="upload_image_name" value="{{ old('upload_image_name') }}"
                                           class="form-control py-2 rounded-3 shadow-sm me-1" required>
                                </div>
                                <div class="add_form col-lg-6">
                                    <label class="fw-bold">Alternate Image Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Alternate Name" id="alternate_image_name"
                                           name="alternate_image_name" value="{{ old('alternate_image_name') }}"
                                           class="form-control py-2 rounded-3 shadow-sm" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Title, Subtitle, and Order -->
                <div class="row g-2 mb-4">
                    <div class="add_form col-md-4">
                        <label class="fw-bold mb-2">Title</label>
                        <input type="text" placeholder="Title" id="slider_name" name="slider_name"
                               value="{{ old('slider_name') }}" class="form-control py-2 rounded-2 shadow-sm">
                    </div>
                    <div class="add_form col-md-4">
                        <label class="fw-bold mb-2">Subtitle</label>
                        <input type="text" placeholder="Subtitle" id="sub_title" name="sub_title"
                               value="{{ old('sub_title') }}" class="form-control py-2 rounded-3 shadow-sm">
                    </div>
                    <div class="add_form col-md-4">
                        <label class="fw-bold mb-2">Order <span class="text-danger">*</span></label>
                        <input type="number" placeholder="Order" id="list_order" name="list_order"
                               value="{{ old('order') }}" class="form-control py-2 rounded-3 shadow-sm" required>
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
                <!-- Buttons -->
                <div class="text-center mt-4">
                    <a href="{{ route('admin.slider_list') }}">
                        <button type="button" class="cancel-btn">Cancel</button>
                    </a>
                    <button class="submit-btn sbmtBtn ms-4">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


