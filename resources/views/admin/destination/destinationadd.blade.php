@extends('layouts.app')
@Section('content')
<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-12">
        <h3 class="fw-bold"><span class="vr"></span>&nbsp;{{$title}}</h3>
    </div>

    <!-- FORM -->
    <div class="row mb-5">
        <div class="col">
            <div class="form-body px-5 py-5 rounded-4 m-auto ">
                <form id="form_valid" action="{{ route('admin.destination_insert') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                    <div class="g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mt-4"> Image </label>
                                <div class="row d-flex mb-4">
                                    <div class="col-lg-2">
                                        <div class="form-input">
                                            <label for="file-ip-1" class="px-4 py-3 text-center">
                                                <img class="text-center mt-3" id="file-ip-1-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg">
                                                <p class="text-center fw-light mt-3">Add Pic</p>
                                            </label>
                                            <input type="file" id="file-ip-1" name="image_1" accept="image/png, image/jpeg, image/svg+xml">
                                            <div id="file-ip-1-error" class="text-danger"></div>
                                        </div>
                                    </div>



                                </div>
                                <!-- <h6>*Supported formats: PNG & JPG; File size limit: 2 MB</h6> -->
                            </div>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Destination <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Destination" id="destination_name" name="destination_name" class="form-control py-3 rounded-3 shadow-sm" required value="{{old('destination_name')}}">
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Place <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Place" id="place" name="place" class="form-control py-3 rounded-3 shadow-sm" required value="{{old('place')}}">
                            </div>
                        </div>
             
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4">Upload Image Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Rename the Photo" id="upload_image_name" name="upload_image_name" value="{{ old('upload_image_name') }}" class="form-control py-3 rounded-3 shadow-sm" required>
                            </div>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4">Alternate Image Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Alternate Name" id="alternate_image_name" name="alternate_image_name" value="{{ old('alternate_image_name') }}" class="form-control py-3 rounded-3 shadow-sm" required>
                            </div>
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
                        <a href="{{ route('admin.destinationlist') }}">
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