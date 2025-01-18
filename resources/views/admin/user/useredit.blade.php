@extends('layouts.app')
@Section('content')
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
/* Align the form with the title */
.container-wrapper {
        padding-left: 30px;
        /* Adjust as per your layout */
        padding-right: 30px;
        /* Consistent padding for both sides */
    }

    .form-body {
      
        border-radius: 10px;
        
    }
</style>
<div class="container-wrapper pt-5">
    <div class="row">
    <b><a href="/dashboard" >Dashboard</a> > <a href="/user" >User</a> > <a class="edit" >Edit</a></b>
        <br>
        <br>
        <h3 class="fw-bold">{{$title}}</h3>
    </div>
</div>
    <!-- FORM -->
    <div class="row mb-5">
        <div class="col-lg-12">
            <!-- INFORMATION -->
            <div class="form-body px-4 mb-5 rounded-4">
                <form id="form_valid" action="{{ route('admin.user_update', ['id'=>$user_details->id]) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 ">First Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="First Name" id="first_name" name="first_name" class="form-control py-2 rounded-3 shadow-sm" value="{{ $user_details->first_name }}" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 ">Last Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="last Name" id="last_name" name="last_name" class="form-control py-2 rounded-3 shadow-sm" value="{{ $user_details->last_name }}" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Email <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Email" id="email" name="email" value="{{ $user_details->email }}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4">Old Password</label>
                                <input type="password" id="old_password" name="old_password" placeholder="Old Password" class="form-control py-2 rounded-3 shadow-sm">
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4">New Password</label>
                                <input type="password" placeholder="New Password" id="new_password" name="new_password" class="form-control py-2 rounded-3 shadow-sm">
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4">Re-type New Password</label>
                                <input type="password" placeholder="Re-type New Password" id="new_password_confirmation" name="new_password_confirmation" class="form-control py-2 rounded-3 shadow-sm">
                            </div>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Dob <span class="text-danger">*</span></label>
                                <input type="date" id="dob" name="dob" value="{{ $user_details->dob }}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Phone Number <span class="text-danger">*</span></label>
                                <input type="number" id="phone" placeholder="Phone Number" name="phone" value="{{ $user_details->phone }}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Street <span class="text-danger">*</span></label>
                                <input type="text" id="street" placeholder="Street" name="street" value="{{ $user_details->street }}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> City <span class="text-danger">*</span></label>
                                <input type="text" id="city" placeholder="City" name="city" value="{{ $user_details->city }}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> State <span class="text-danger">*</span></label>
                                <input type="text" id="state" placeholder="State" name="state" value="{{ $user_details->state }}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Zip/Postal Code <span class="text-danger">*</span></label>
                                <input type="number" id="zip_province_code" placeholder="Zip/Postal Code" name="zip_province_code" value="{{ $user_details->zip_province_code }}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Country <span class="text-danger">*</span></label>
                                <input type="text" id="country" placeholder="Country" name="country" value="{{ $user_details->country }}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Preferred Language <span class="text-danger">*</span></label>
                                <input type="text" id="preferred_lang" placeholder="Preferred Language" name="preferred_lang" value="{{ $user_details->preferred_lang }}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mt-3">
                            <div class="col">
                                <label class="fw-bold ">New Letter</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input check_bx" type="checkbox" id="newsletter_sub" name="newsletter_sub" {{ $user_details->newsletter_sub ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mt-3">
                            <div class="col">
                                <label class="fw-bold ">Terms And Condition</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input check_bx" type="checkbox" id="terms_condition" name="terms_condition" {{ $user_details->terms_condition ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mt-3">
                            <div class="col">
                                <label class="fw-bold ">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input check_bx" type="checkbox" id="status" name="status" {{ $user_details->status ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 text-end mt-5">
                        <a href="{{ route('admin.user_list') }}">
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