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

  
</style>
<div class="container-wrapper pt-5">
    <div class="row">
        <!-- <div class="col-lg-12"> -->
            <b><a href="/dashboard">Dashboard</a> > <a href="/client_review">Client Review</a> > <a class="add">Add</a></b>
            <br>
            <br>
            <h3 class="fw-bold">{{$title}}</h3>
        </div>
    </div>
    <!-- FORM -->
    <div class="row mb-5">
        <div class="col">
            <div class="form-body px-4 mb-5 rounded-4">
                <form id="form_valid" action="{{ route('admin.client_review_insert') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold  mb-4"> Program Name <span class="text-danger">*</span></label>
                                <select id="program_name" name="program_name" class="form-select py-2 rounded-3 shadow-sm" required>
                                    <option value="">Select Program</option>
                                    @foreach($program_dts as $id => $name)
                                    <option value="{{ $id }}" @if(old('program_name')==$id) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="g-2 mb-4">
                            <div class="col">
                                <label class=" fw-bold mb-4" id="label_textarea"> Client Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Client Name" id="client_name" name="client_name" value="{{old('client_name')}}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mt-4"> Client Photo </label>
                                <div class="row d-flex mb-4">
                                    <div class="col-lg-2">
                                        <div class="form-input">
                                            <label for="file-ip-1" class="px-4 py-3 text-center">
                                                <img class="text-center mt-3" id="file-ip-1-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg">
                                                <p class="text-center fw-light mt-3">Add Pic</p>
                                            </label>
                                            <input type="file" id="file-ip-1" name="image_1" accept="image/png, image/jpeg">
                                            <div id="file-ip-1-error" class="text-danger"></div>
                                            <label class="fw-bold mb-5 text-danger border-0"><small>* Upload size [640*120] *</small></label>

                                        </div>
                                    </div>
                                    <!-- Input Section -->
                                    <div class="col-lg-8">
                                        <div class="row g-1">
                                            <!-- First Input: Upload Image Name -->
                                            <div class="col-lg-6">
                                                <label class="fw-bold">Upload Image Name <span class="text-danger">*</span></label>
                                                <input type="text" placeholder="Rename the Photo" id="upload_image_name" name="upload_image_name" value="{{ old('upload_image_name') }}" class="form-control py-2 rounded-3 shadow-sm" required>
                                            </div>
                                            <!-- Second Input: Alternate Image Name -->
                                            <div class="col-lg-6 px-4">
                                                <label class="fw-bold">Alternate Image Name <span class="text-danger">*</span></label>
                                                <input type="text" placeholder="Alternate Name" id="alternate_image_name" name="alternate_image_name" value="{{ old('alternate_image_name') }}" class="form-control py-2 rounded-3 shadow-sm" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="g-2 mb-4">
                                <div class="col">

                                    <label class=" fw-bold mb-4" id="label_textarea"> Client Review <span class="text-danger">*</span></label>
                                    <!-- <textarea id="program_description" class="container__textarea p-5 textarea-feild" name="client_review" required></textarea> -->
                                    <input type="hidden" id="client_review" name="client_review">
                                    <div class=" mt-5">
                                        <div class="row">
                                            <div class="col-lg-12 ">
                                                <div id="summernote" style="height: 200px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="g-2 mb-4">
                                <div class="col">
                                    <label class=" fw-bold mb-4" id="label_textarea"> Review date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control py-2 rounded-3 shadow-sm " name="review_dt" id="review_dt" value="{{old('review_dt')}}" required>
                                </div>
                            </div>
                            <div class="g-2 mb-4">
                                <div class="col">
                                    <label class=" fw-bold mb-4" id="label_textarea"> Rating <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control py-2 rounded-3 shadow-sm " name="rating" id="rating" value="{{old('rating')}}" required>
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
                            <a href="{{ route('admin.client_review_list') }}">
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