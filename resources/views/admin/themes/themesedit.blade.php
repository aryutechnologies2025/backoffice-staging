@extends('layouts.app')
@Section('content')

<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-12">
        <h3 class="fw-bold"><span class="vr"></span>&nbsp;{{$title}}</h3>
    </div>

    <div class="row mb-5">
        <div class="col">
            <div class="form-body px-5 py-5 rounded-4 m-auto ">
                <form id="form_valid" action="{{ route('admin.themes_update', ['id'=>$themes_details->id]) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Title <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Title" id="theme_name" name="theme_name" value="{{ $themes_details->themes_name }}" class="form-control py-3 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mt-4"> Image </label>
                                <div class="row d-flex mb-4">
                                    <div class="col-lg-2">
                                        <div class="form-input">
                                            <!-- Existing image preview -->
                                            @if ($themes_details->theme_pic)
                                            <img id="file-ip-1-preview" src="{{ asset($themes_details->theme_pic) }}" alt="Thumbnail Preview" class="img-thumbnail mb-3" style="max-height: 150px; object-fit: cover;">
                                            @else
                                                <img id="file-ip-1-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg" alt="Add Pic" class="img-thumbnail mb-3">
                                            @endif
                                            
                                            <label for="file-ip-1" class="d-block text-center py-3" style="cursor: pointer;">
                                                <p class="text-center fw-light">Add Pic</p>
                                            </label>
                                            <input type="file" id="file-ip-1" name="image_1" accept="image/png, image/jpeg" onchange="previewImage(event)">
                                            <div id="file-ip-1-error" class="text-danger"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <h6>*Supported formats: PNG & JPG; File size limit: 2 MB</h6> -->
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 ">Order <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Order" id="list_order" name="list_order" value="{{ $themes_details->list_order }}" class="form-control py-3 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col">
                                <label class="fw-bold ">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input check_bx" type="checkbox" id="status" name="status" {{ $themes_details->status ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 text-end mt-5">
                        <a href="{{ route('admin.themes_list') }}">
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