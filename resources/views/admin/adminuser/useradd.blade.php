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
            <a href="/adminuser">User</a> >
            <a class="add">Add</a>
        </b>
    </div>

</div>
<!-- FORM -->

<div class="row mb-5">
    <div class="col-lg-12">
        <!-- INFORMATION -->
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form id="form_valid" action="{{ route('admin.admin_user_insert') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <!-- Image Section -->
                <div class="mb-3">
                    <div class="row align-items-center w-50% ">
                        <div class="col-lg-2 photo-upload-field w-50% ">
                            <div class="form-input text-center ">
                                <label for="file-ip-1" class="px-4 py-3 ">
                                    <img id="file-ip-1-preview"
                                        src="/assets/image/dashboard/innerpece_addpic_icon.svg"
                                        alt="Alternate Image Name">
                                    <p class="text-center fw-light mt-3">Add Pic</p>
                                </label>
                                <input type="file" id="file-ip-1" name="profile_pic" accept="image/png, image/jpeg, image/webp">
                                <!-- <label class="fw-bold mb-5 text-danger border-0">
                                    <small>* Upload size [up to 600x120] *</small>
                                </label> -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Title, Subtitle, and Order -->
                <div class="row g-2 mb-4">
                    <div class="add_form col-md-4">
                        <label class="fw-bold mb-2">First Name</label>
                        <input type="text" placeholder="First Name" id="first_name" name="first_name"
                            value="{{ old('first_name') }}" class="form-control py-2 rounded-2 shadow-sm">
                    </div>
                    <div class="add_form col-md-4">
                        <label class="fw-bold mb-2">Last Name</label>
                        <input type="text" placeholder="Last Name" id="last_name" name="last_name"
                            value="{{ old('last_name') }}" class="form-control py-2 rounded-2 shadow-sm">
                    </div>
                    <div class="add_form col-md-4">
                        <label class="fw-bold mb-2">Email</label>
                        <input type="email" placeholder="Email" id="email" name="email"
                            value="{{ old('email') }}" class="form-control py-2 rounded-2 shadow-sm">
                    </div>
                     <div class="add_form col-md-4">
                        <label class="fw-bold mb-2">Phone Number</label>
                        <input type="number" placeholder="Phone Number" id="phone" name="phone"
                            value="{{ old('phone') }}" class="form-control py-2 rounded-2 shadow-sm">
                    </div>
                     <div class="add_form col-md-4">
                        <label class="fw-bold mb-2">Password</label>
                        <input type="text" placeholder="Password" id="password" name="password"
                            value="{{ old('password') }}" class="form-control py-2 rounded-2 shadow-sm">
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
                    <a href="{{ route('admin.admin_user_list') }}">
                        <button type="button" class="cancel-btn">Cancel</button>
                    </a>
                    <button class="submit-btn sbmtBtn ms-4">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
