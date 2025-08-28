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


/* .form-control {
    width: 80%;
} */
</style>

 <div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
          <b><a href="/dashboard">Dashboard</a> > <a href="/themes">Themes</a> > <a class="add">Add</a></b>
    </div>

 </div>


<!-- FORM -->

<div class="row mb-5">
    <div class="col-lg-12">
        <!-- INFORMATION -->
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form id="form_valid" action="{{ route('admin.themes_insert') }}" method="POST" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <div class="row align-items-center">
                        <div class="col-lg-2 photo-upload-field">


                            <div class="form-input text-center">
                                <label for="file-ip-1" class="px-4 py-3">
                                    <img class="text-center mt-3" id="file-ip-1-preview"
                                        src="/assets/image/dashboard/innerpece_addpic_icon.svg"
                                        alt="{{ old('alternate_image_name', 'Alternate Image Name') }}">

                                    <p class="text-center fw-light mt-3">Add Pic</p>
                                </label>
                                <input type="file" id="file-ip-1" name="image_1" accept="image/png, image/jpeg"
                                    onchange="validateImage(this)" required>
                                <label class="fw-bold mb-5 text-danger border-0"><small>* Upload size [600*120]
                                        *</small></label>
                            </div>
                        </div>

                        <!-- Input Section -->
                        <div class="col-lg-8">
                            <div class="row g-1">
                                <div id="file-ip-1-error" class="text-danger"></div>
                               
                                <!-- First Input: Upload Image Name -->
                                <div class="add_form col-lg-6 pe-4">
                                    <label class="fw-bold">Upload Image Name <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Rename the Photo" id="upload_image_name"
                                        name="upload_image_name" value="{{ old('upload_image_name') }}"
                                        class="form-control py-2 rounded-3 shadow-sm" required>
                                </div>
                                <!-- Second Input: Alternate Image Name -->
                                <div class="add_form col-lg-6">
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
                    <div class="add_form col-md-6 pe-4">
                        <label class="fw-bold mb-2 "> Title <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Title" id="theme_name" name="theme_name"
                            class="form-control py-2 rounded-3 shadow-sm" required value="{{old('theme_name')}}">
                    </div>



                    <div class="add_form col-md-6 ">
                        <label class="fw-bold mb-2 ">Order <span class="text-danger">*</span></label>
                        <input type="number" placeholder="Order" id="list_order" name="list_order"
                            value="{{old('order')}}" class="form-control py-2 rounded-3 shadow-sm" required>
                    </div>
                </div>

                <div class="row g-2 mb-4">
                    <div class="add_form col">
                        <label class="fw-bold ">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                        </div>
                    </div>
                </div>


                <div class=" text-center mt-4">
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
