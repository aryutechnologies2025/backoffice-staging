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
width: 80%;
}
</style>
<div class="container-wrapper pt-5">
    <div class="row">
        <!-- <div class="col-lg-12"> -->
        <b><a href="/dashboard">Dashboard</a> > <a href="/themes">Themes</a> > <a class="add">Add</a></b>
        <br>
        <br>
        <h3 class="fw-bold">{{ $title }}</h3>
    </div>
</div>

<!-- FORM -->
<div class="row mb-5">
    <div class="col-lg-12">
        <div class="form-body px-4 mb-5 rounded-4 ">
            <form id="form_valid" action="{{ route('admin.themes_insert') }}" method="POST" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <div class="row align-items-center">
                        <div class="col-lg-2 photo-upload-field">


                            <div class="form-input text-center">
                                <label for="file-ip-1" class="px-4 py-3">
                                    <img class="text-center mt-3" id="file-ip-1-preview"
                                        src="/assets/image/dashboard/innerpece_addpic_icon.svg">
                                    <p class="text-center fw-light mt-3">Add Pic</p>
                                </label>
                                <input type="file" id="file-ip-1" name="image_1" accept="image/png, image/jpeg">
                                <div id="file-ip-1-error" class="text-danger"></div>
                                <label class="fw-bold mb-5 text-danger border-0"><small>* Upload size [640*120]
                                        *</small></label>
                            </div>
                        </div>

                        <!-- Input Section -->
                        <div class="col-lg-8">
                            <div class="row g-1">
                                <!-- First Input: Upload Image Name -->
                                <div class="col-lg-6">
                                    <label class="fw-bold">Upload Image Name <span
                                            class="text-danger">*</span></label>
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
                    <div class="col-md-6">
                        <label class="fw-bold mb-2 "> Title <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Title" id="theme_name" name="theme_name"
                            class="form-control py-2 rounded-3 shadow-sm" required value="{{old('theme_name')}}">
                    </div>



                    <div class="col-md-6">
                        <label class="fw-bold mb-2 ">Order <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Order" id="list_order" name="list_order"
                            value="{{old('order')}}" class="form-control py-2 rounded-3 shadow-sm" required>
                    </div>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col">
                        <label class="fw-bold ">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                        </div>
                    </div>
                </div>


                <div class=" text-end mt-4">
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