@extends('layouts.app')
@Section('content')
<style>
    a:hover {
        color: red;
    }
    a{
        color:rgb(37, 150, 190);
    }
    .add{
        color:blue;
    }
/* Align the form with the title */
.container-wrapper {
    padding-left: 30px; /* Adjust as per your layout */
    padding-right: 30px; /* Consistent padding for both sides */
}


</style>
<div class="container-wrapper pt-5">
    <div class="row">
    <!-- <div class="col-lg-12"> -->
    <b><a href="/dashboard" >Dashboard</a> > <a href="/user" >User</a> > <a class="add" >Add</a></b>
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
                <form id="form_valid" action="{{ route('admin.user_insert') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 ">First Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="First Name" id="first_name" name="first_name" value="{{old('first_name')}}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 ">Last Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Last Name" id="last_name" name="last_name" value="{{old('last_name')}}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Email <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Email" id="email" name="email" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Password <span class="text-danger">*</span></label>
                                <input type="password" placeholder="Password" id="password" name="password" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Dob <span class="text-danger">*</span></label>
                                <input type="date" id="dob" name="dob" value="{{old('dob')}}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Phone Number <span class="text-danger">*</span></label>
                                <input type="number" id="phone" placeholder="Phone Number" name="phone" value="{{old('phone')}}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Street <span class="text-danger">*</span></label>
                                <input type="text" id="street" placeholder="Street" name="street" value="{{old('street')}}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> City <span class="text-danger">*</span></label>
                                <input type="text" id="city" placeholder="City" name="city" value="{{old('city')}}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> State <span class="text-danger">*</span></label>
                                <input type="text" id="state" placeholder="State" name="state" value="{{old('state')}}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Zip/Postal Code <span class="text-danger">*</span></label>
                                <input type="number" id="zip_province_code" placeholder="Zip/Postal Code" name="zip_province_code" value="{{old('zip_province_code')}}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Country <span class="text-danger">*</span></label>
                                <input type="text" id="country" placeholder="Country" name="country" value="{{old('country')}}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Preferred Language <span class="text-danger">*</span></label>
                                <input type="text" id="preferred_lang" placeholder="Preferred Language" name="preferred_lang" value="{{old('preferred_lang')}}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>
                        <div class="row g-2 mt-3">
                            <div class="col">
                                <label class="fw-bold ">New Letter</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input check_bx" type="checkbox" id="newsletter_sub" name="newsletter_sub">
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mt-3">
                            <div class="col">
                                <label class="fw-bold ">Terms And Condition</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input check_bx" type="checkbox" id="terms_condition" name="terms_condition">
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mt-3">
                            <div class="col">
                                <label class="fw-bold ">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
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