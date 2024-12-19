@extends('layouts.app')
@Section('content')

<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-12">
        <h3 class="fw-bold"><span class="vr"></span>&nbsp;{{$title}}</h3>
    </div>

    <!-- FORM -->
    <div class="row mb-5">
        <div class="col">
            <!-- INFORMATION -->
            <div class="form-body px-5 py-5 rounded-4 m-auto ">
                <form id="form_valid" action="{{ route('admin.address_update', ['id'=>$user_details->id]) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 ">Title <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Title" id="title" name="title" class="form-control py-3 rounded-3 shadow-sm" value="{{ $user_details->title }}" required>
                            </div>
                        </div>


                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <label class="form-label form-label-top form-label-auto fw-bold mb-4">Address <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <textarea
                                        id="basic-default-email"
                                        name="address"
                                        class="form-control custom-textarea"
                                        placeholder="Enter description here"
                                        aria-label="Address"
                                        aria-describedby="basic-default-email2"
                                        rows="3">{!! $user_details->address !!}</textarea>

                                </div>
                            </div>
                        </div>

                       
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> City <span class="text-danger">*</span></label>
                                <input type="text" placeholder="City" id="city" name="city" value="{{ $user_details->city }}" class="form-control py-3 rounded-3 shadow-sm" required>
                            </div>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> State <span class="text-danger">*</span></label>
                                <input type="text" placeholder="State" id="state" name="state" value="{{ $user_details->state }}" class="form-control py-3 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                       
                       

                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Zip/Postal Code <span class="text-danger">*</span></label>
                                <input type="text" id="pincode" placeholder="Zip/Postal Code" name="pincode" value="{{ $user_details->pincode }}" class="form-control py-3 rounded-3 shadow-sm" required>
                            </div>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4">Country</label>
                                <input type="text" id="country" name="country" value="{{ $user_details->country }}" placeholder="Country" class="form-control py-3 rounded-3 shadow-sm">
                            </div>
                        </div>
                       
                    <div class="col-lg-12 text-end mt-5">
                        <a href="{{ route('admin.address_list') }}">
                            <button type="button" class="cancel-btn"> Cancel </button>
                        </a>
                        <button class="submit-btn sbmtBtn ms-4"> Submit </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#basic-default-email').summernote({
            height: 150, // Set the height of the editor
            placeholder: 'Enter your description here'
        });
    });

</script>
@endsection