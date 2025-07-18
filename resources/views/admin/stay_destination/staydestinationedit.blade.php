@extends('layouts.app')
@section('content')
<style>
    .form-check-input {
        transform: scale(1.5);       
    }
   
</style>

<div class="row body-sec py-5 px-5 justify-content-around">
    <div class="col-lg-12">
        <h3 class="fw-bold"><span class="vr"></span>&nbsp;{{$title}}</h3>
    </div>

    <div class="row mb-5">
        <div class="col">
            <div class="form-body py-5 rounded-4  ">
                <form id="form_valid" action="{{ route('admin.staydestination_update', ['id' => $destination_details->id]) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                        <div class="g-2 mb-4 ">
                    <div class="g-2 mb-4 d-flex gap-5 align-items-center">
                        <div class="col-3">
                            <label class="fw-bold mt-4 mb-2"> Image </label>
                            <div class="row d-flex mb-4">
                                <div class="w-100">
                                    <div class="form-input">
                                        <!-- Existing image preview -->
                                        @if ($destination_details->city_image)
                                        <img id="file-ip-1-preview" src="{{ asset($destination_details->city_image) }}"  style="max-height: 150px; object-fit: cover;">
                                       
                                        @endif

                                        <label for="file-ip-1" class="d-block text-center py-3" style="cursor: pointer;">
                                            <p class="text-center fw-light">Update Pic</p>
                                        </label>
                                        <input type="file" id="file-ip-1" name="image_1"  onchange="previewImage(event)">
                                        <div id="file-ip-1-error" class="text-danger"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 g-2">
                            <div class="col">
                                <label class="fw-bold mb-2">Upload Image Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Rename the Photo" id="upload_image_name" name="upload_image_name" value="{{ $destination_details->upload_image_name }}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>

                        <div class="col-4 g-2">
                            <div class="col">
                                <label class="fw-bold mb-2">Alternate Image Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Alternate Name" id="alternate_name" name="alternate_name" value="{{ $destination_details->alternate_name }}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>


                    </div>
                    <div class="mb-3 col-4">
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4"> Destination <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Destination" id="city_name" name="city_name" value="{{ $destination_details->city_name }}" class="form-control p-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>



                        <div class="row g-2">
                            <div class="col">
                                <label class="fw-bold">Status</label>
                                <div class="form-check form-switch ms-2">
                                    <input class="form-check-input check_bx" type="checkbox" id="status" name="status" {{ $destination_details->status ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 text-end mt-5">
                        <a href="{{ route('admin.staydestinationlist') }}">
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