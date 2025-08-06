@extends('layouts.app')

@section('content')
<style>
    a:hover {
        color: rgb(27, 108, 138);
    }
    a{
        font-family: 'Poppins', sans-serif;
        font-weight:500;
        color:#8B7eff;
        font-size:13px;
    }
    .city{
        font-family: 'Poppins', sans-serif;
        font-weight:600;
        color:#282833;
        font-size:13px;
    }

    .px-5 {
  
  padding-left: 1rem !important; 
}
.form-body {
   
    padding-top: 1% !important;
}
</style>
<div class="row body-sec py-5 px-5 justify-content-around">
    <div class="text-end col-lg-12 mb-3">
    <b><a href="/dashboard" >Dashboard</a> > <a class="city " href="/settings" >Settings</a></b>
      
   </div>   
    <div class="col-lg-12 mb-3"> 
        <h3 class="admin-title fw-bold">{{ $title }}</h3>
        <!-- <p class="fw-light">There are many variations of passages of Lorem Ipsum</p> -->
    </div>

    <!-- FORM -->
    <form id="form_valid" action="{{ route('admin.settings_insert') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <!-- APP INFORMATION -->
            <h5 class="fw-bold mt-1">General Setting</h5>
            <div class="form-body px-5 py-5 rounded-4">
                <div class="row mb-2">
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4 ">Meta Title <span class="text-danger">*</span></label>
                        <input type="text" id="meta_title" name="meta_title" class="form-control py-2 rounded-3 shadow-sm mb-2" value="{{ old('meta_title', $settings->meta_title ?? '') }}" placeholder="Meta Title" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4 ">Meta Keywords <span class="text-danger">*</span></label>
                        <input type="text" id="meta_keywords" name="meta_keywords" class="form-control py-2 rounded-3 shadow-sm" value="{{ old('meta_keywords', $settings->meta_keywords ?? '') }}" placeholder="Meta Keywords" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4 ">Meta Description <span class="text-danger">*</span></label>
                        <input type="text" id="meta_desc" name="meta_desc" class="form-control py-2 rounded-3 shadow-sm" value="{{ old('meta_desc', $settings->meta_desc ?? '') }}" placeholder="Meta Description" required>
                    </div>
                </div>

                <!-- Site Logo, Footer Logo, Official Logo, and Favicon -->
                <div class="row g-2">
                    <div class="col-lg-3">
                        <label class="fw-bold mt-4"> Site Logo </label>
                        <div class="form-input text-center mb-4">
                            <label for="file-ip-1" class="px-4 py-3">
                                <img id="file-ip-1-preview" src="{{ $settings->site_logo ? asset($settings->site_logo) : '/assets/image/dashboard/innerpece_addpic_icon.svg' }}" alt="Preview" style="width: 100%; height: auto;">
                                <p class="fw-light mt-3">Add Pic</p>
                            </label>
                            <input type="file" id="file-ip-1" name="site_logo" accept="image/png, image/jpeg, image/svg+xml">
                            <div id="file-ip-1-error" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="fw-bold mt-4"> Footer Logo </label>
                        <div class="form-input text-center mb-4">
                            <label for="file-ip-2" class="px-4 py-3">
                                <img id="file-ip-2-preview" src="{{ $settings->footer_logo ? asset($settings->footer_logo) : '/assets/image/dashboard/innerpece_addpic_icon.svg' }}" alt="Preview" style="width: 100%; height: auto;">
                                <p class="fw-light mt-3">Add Pic</p>
                            </label>
                            <input type="file" id="file-ip-2" name="footer_logo" accept="image/png, image/jpeg, image/svg+xml">
                            <div id="file-ip-2-error" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="fw-bold mt-4"> Footer Official Logo </label>
                        <div class="form-input text-center mb-4">
                            <label for="file-ip-4" class="px-4 py-3">
                                <img id="file-ip-4-preview" src="{{ $settings->official_logo ? asset($settings->official_logo) : '/assets/image/dashboard/innerpece_addpic_icon.svg' }}" alt="Preview" style="width: 100%; height: auto;">
                                <p class="fw-light mt-3">Add Pic</p>
                            </label>
                            <input type="file" id="file-ip-4" name="official_logo" accept="image/png, image/jpeg, image/svg+xml">
                            <div id="file-ip-4-error" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="fw-bold mt-4"> Favicon </label>
                        <div class="form-input text-center mb-4">
                            <label for="file-ip-3" class="px-4 py-3">
                                <img id="file-ip-3-preview" src="{{ $settings->fav_icon ? asset($settings->fav_icon) : '/assets/image/dashboard/innerpece_addpic_icon.svg' }}" alt="Preview" style="width: 100%; height: auto;">
                                <p class="fw-light mt-3">Add Pic</p>
                            </label>
                            <input type="file" id="file-ip-3" name="fav_icon" accept="image/png, image/jpeg">
                            <div id="file-ip-3-error" class="text-danger"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- APPLICATION INFO -->
        <div class="row mb-5">
            <h5 class="fw-bold mt-3">Application Info</h5>
            <div class="form-body px-5 py-5 rounded-4">
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4 ">Application Name <span class="text-danger">*</span></label>
                        <input type="text" id="app_name" name="app_name" class="form-control py-2 rounded-3 shadow-sm" value="{{ old('app_name', $settings->app_name ?? '') }}" placeholder="Application Name" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4 ">Contact Email <span class="text-danger">*</span></label>
                        <input type="text" id="contact_email" name="contact_email" class="form-control py-2 rounded-3 shadow-sm" value="{{ old('contact_email', $settings->contact_email ?? '') }}" placeholder="Contact Email" required>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4 ">Contact Phone <span class="text-danger">*</span></label>
                        <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number', $settings->contact_number ?? '') }}" class="form-control py-2 rounded-3 shadow-sm" placeholder="Contact No" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4 ">Contact Address <span class="text-danger">*</span></label>
                        <input type="text" id="contact_address" name="contact_address" value="{{ old('contact_address', $settings->contact_address ?? '') }}" class="form-control py-2 rounded-3 shadow-sm" placeholder="Address" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- SOCIAL PROFILE -->
        <div class="row mb-5">
            <h5 class="fw-bold mb-3">Social Media</h5>
            <div class="form-body px-5 py-5 rounded-4 ">
                <div class="row mb-4">
                    <div class="col-lg-4">
                        <label class="fw-bold mb-4 "><i class="bi bi-facebook"></i>&nbsp;&nbsp;Facebook URL</label>
                        <input type="text" id="facebook" name="facebook" class="form-control py-2 rounded-3 shadow-sm" value="{{ old('facebook', $settings->facebook ?? '') }}" placeholder="Facebook URL">
                    </div>
                    <div class="col-lg-4">
                        <label class="fw-bold mb-4 "><i class="bi bi-instagram"></i>&nbsp;&nbsp;Instagram URL</label>
                        <input type="text" id="instagram" name="instagram" class="form-control py-2 rounded-3 shadow-sm" value="{{ old('instagram', $settings->instagram ?? '') }}" placeholder="Instagram URL">
                    </div>
                    <div class="col-lg-4">
                        <label class="fw-bold mb-4 "><i class="bi bi-twitter-x"></i>&nbsp;&nbsp; X URL</label>
                        <input type="text" id="twitter_x" name="twitter_x" class="form-control py-2 rounded-3 shadow-sm" value="{{ old('twitter_x', $settings->twitter_x ?? '') }}" placeholder="X URL">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-4">
                        <label class="fw-bold mb-4 "><i class="bi bi-pinterest"></i>&nbsp;&nbsp;Pinterest</label>
                        <input type="text" id="pinterest" name="pinterest" class="form-control py-2 rounded-3 shadow-sm" value="{{ old('pinterest', $settings->pinterest ?? '') }}" placeholder="Pinterest">
                    </div>
                    <div class="col-lg-4">
                        <label class="fw-bold mb-4 "><i class="bi bi-linkedin"></i>&nbsp;&nbsp;LinkedIn</label>
                        <input type="text" id="linkedin" name="linkedin" class="form-control py-2 rounded-3 shadow-sm" value="{{ old('linkedin', $settings->linkedin ?? '') }}" placeholder="LinkedIn">
                    </div>
                    <div class="col-lg-4">
                        <label class="fw-bold mb-4 "><i class="bi bi-youtube"></i>&nbsp;&nbsp;YouTube URL</label>
                        <input type="text" id="youtube_url" name="youtube_url" class="form-control py-2 rounded-3 shadow-sm" value="{{ old('youtube_url', $settings->youtube_url ?? '') }}" placeholder="YouTube">
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-4">
                        <label class="fw-bold mb-4 "><i class="bi bi-android2"></i>&nbsp;&nbsp;Android Store Link</label>
                        <input type="text" id="android_link" name="android_link" class="form-control py-2 rounded-3 shadow-sm" value="{{ old('android_link', $settings->android_link ?? '') }}" placeholder="Android Store Link">
                    </div>
                    <div class="col-lg-4">
                        <label class="fw-bold mb-4 "><i class="bi bi-apple"></i>&nbsp;&nbsp;iOS Store Link</label>
                        <input type="text" id="ios_link" name="ios_link" class="form-control py-2 rounded-3 shadow-sm" value="{{ old('ios_link', $settings->ios_link ?? '') }}" placeholder="iOS Store Link">
                    </div>
                    <div class="col-lg-4">
                        <label class="fw-bold mb-4 "><i class="bi bi-c-circle"></i>&nbsp;&nbsp;Copyright Content</label>
                        <input type="text" id="copyright" name="copyright" class="form-control py-2 rounded-3 shadow-sm" value="{{ old('copyright', $settings->copyright ?? 'Copyright © ' . date('Y') . ' by Innerpece. All Rights Reserved') }}" placeholder="Copyright Content">
                    </div>
                </div>
                <div class="col-lg-12 text-end mt-5">
                    <a href="{{ route('admin.dashboard') }}">
                        <button type="button" class="cancel-btn"> Cancel </button>
                    </a>
                    <button class="submit-btn sbmtBtn ms-4"> Submit </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function previewFile(inputId, previewId) {
            const fileInput = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        // Initialize preview for each file input
        previewFile('file-ip-1', 'file-ip-1-preview');
        previewFile('file-ip-2', 'file-ip-2-preview');
        previewFile('file-ip-3', 'file-ip-3-preview');
        previewFile('file-ip-4', 'file-ip-4-preview');
    });
</script>
@endsection