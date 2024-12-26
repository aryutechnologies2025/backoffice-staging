@extends('layouts.app')

@section('content')
<style>
    a:hover {
        color: red;
    }
    a{
        color:rgb(37, 150, 190);
    }
    .edit{
        color:blue;
    }

</style>
<div class="row body-sec py-5 px-5 justify-content-around">
    <div class="col-lg-12 mb-5">
    <b><a href="/dashboard" >Dashboard</a> > <a href="/profile" >Profile</a> > <a class="edit" >Edit</a></b>
        <br>
        <br>
        <h3 class="fw-bold">Edit Profile</h3>
    </div>

    <!-- FORM -->
    <div class="row mb-5">
        <form id="form_valid" action="{{ route('admin.profile_update', ['id'=>$admin_dts->id]) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <!-- Avatar Section -->
            <div class="col-lg-2">
                <h5><label class="fw-bold mb-3" for="profile_pic">Avatar</label></h5>
            </div>
            <div class="form-body px-5 py-5 rounded-4">
                <div class="col">
                    <div class="row mb-5">
                        <div class="row mb-4">
                            <div class="col-lg-8">
                                <canvas id="canv1" class="rounded-3 shadow-sm"></canvas>
                                <p>Upload a new Avatar</p>
                                <div class="bg-white rounded-4 p-3 shadow-sm mb-3">
                                    <!-- Show existing profile picture if available -->
                                    <img id="profile_pic_preview" src="{{ asset($admin_dts->profile_pic) }}" alt="Profile Picture" class="img-fluid mb-3" style="max-width: 200px;">
                                    <input type="file" accept="image/*" id="profile_pic" name="profile_pic" onchange="upload()">
                                </div>
                                <!-- <h6>Png, Jpg, Svg dimension (300x350) max file not more than 4 MB</h6> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- USER INFORMATION -->
            <h5 class="fw-bold mt-3">Admin Information</h5>
            <div class="form-body px-5 py-5 rounded-4">
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4" for="first_name">First Name <span class="text-danger">*</span></label>
                        <input type="text" id="first_name" name="first_name" class="form-control py-2 rounded-3 shadow-sm" placeholder="First Name" value="{{ old('first_name', $admin_dts->first_name) }}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4" for="last_name">Last Name <span class="text-danger">*</span></label>
                        <input type="text" id="last_name" name="last_name" class="form-control py-2 rounded-3 shadow-sm" placeholder="Last Name" value="{{ old('last_name', $admin_dts->last_name) }}" required>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4" for="phone_number">Phone <span class="text-danger">*</span></label>
                        <input type="text" id="phone_number" name="phone_number" class="form-control py-2 rounded-3 shadow-sm" placeholder="Phone" value="{{ old('phone_number', $admin_dts->phone) }}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="fw-bold mb-4" for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="form-control py-2 rounded-3 shadow-sm" placeholder="Email" value="{{ old('email', $admin_dts->email) }}" required>
                    </div>
                </div>
            </div>

            <!-- PASSWORD -->
            <div class="row mt-5">
                <h5 class="fw-bold mb-3">Password Change Request</h5>
                <div class="form-body px-5 py-5 rounded-4">
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label class="fw-bold mb-4" for="old_password">Old Password</label>
                            <input type="password" id="old_password" name="old_password" class="form-control py-2 rounded-3 shadow-sm" placeholder="Old Password">
                        </div>
                        <div class="col-lg-6">
                            <label class="fw-bold mb-4" for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="form-control py-2 rounded-3 shadow-sm" placeholder="New Password">
                        </div>
                        <div class="col-lg-6 mt-3">
                            <label class="fw-bold mb-4" for="confirm_password">Re-type New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control py-2 rounded-3 shadow-sm" placeholder="Re-type New Password">
                        </div>
                        <!-- <div class="col-lg-6">
                            <label class="fw-bold mb-4" for="confirm_password">Re-type New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control py-3 rounded-3 shadow-sm" placeholder="Re-type New Password">
                        </div> -->
                    </div>
                    <div class="row g-2">
                        <div class="col">
                            <label class="fw-bold">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input check_bx" type="checkbox" id="status" name="status" {{ $admin_dts->status ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 text-end mt-5">
                        <a href="{{ route('admin.profile_list') }}">
                            <button type="button" class="cancel-btn"> Cancel </button>
                        </a>
                        <button type="submit" class="submit-btn sbmtBtn ms-4"> Submit </button>
                    </div>
                </div>
            </div>
            <!-- PASSWORD -->
            <!-- <div class="row mt-5">
                <h5 class="fw-bold mb-3"><span class="vr"></span>&nbsp;Password Change Request</h5>
                <div class="form-body px-5 py-5 rounded-4 m-auto">
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label class="fw-bold mb-4" for="old_password">Old Password</label>
                            <input type="password" id="old_password" name="old_password" class="form-control py-3 rounded-3 shadow-sm" placeholder="Old Password">
                        </div>
                        <div class="col-lg-6">
                            <label class="fw-bold mb-4" for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="form-control py-3 rounded-3 shadow-sm" placeholder="New Password">
                        </div>
                        <div class="col-lg-6 mt-3">
                            <label class="fw-bold mb-4" for="confirm_password">Re-type New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control py-3 rounded-3 shadow-sm" placeholder="Re-type New Password">
                        </div>
                    </div>
                    <div class="col-lg-12 text-end mt-5">
                        <a href="{{ route('admin.profile_list') }}">
                            <button type="button" class="cancel-btn"> Cancel </button>
                        </a>
                        <button type="submit" class="submit-btn sbmtBtn ms-4"> Submit </button>
                    </div>
                </div>
            </div> -->
        </form>
    </div>

    <script>
        function upload() {
            const fileInput = document.getElementById('profile_pic');
            const canvas = document.getElementById('canv1');
            const ctx = canvas.getContext('2d');

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = new Image();

                    img.onload = function() {
                        canvas.width = 200; // Desired width
                        canvas.height = 250; // Desired height

                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    };

                    img.src = e.target.result;
                };

                reader.readAsDataURL(fileInput.files[0]);

                // Preview the selected image
                const profilePicPreview = document.getElementById('profile_pic_preview');
                profilePicPreview.src = URL.createObjectURL(fileInput.files[0]);
            }
        }
    </script>
    @endsection