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
<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard" >Dashboard</a> > <a href="/user" >User</a> > <a class="edit" >View</a></b>
    </div>

</div>
<div class="row mb-5">
    <div class="col-lg-12">
        <!-- INFORMATION -->
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
             
                    <div class="mb-3">
                        <div class="row g-2 mb-4">
                            <div class="add_form col-lg-6 pe-4">
                                <label class="fw-bold mb-2 ">First Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="First Name" id="first_name" name="first_name" class="form-control py-2 rounded-3 shadow-sm" value="{{ $user_details->first_name }}" readonly>
                            </div>
                             <div class="add_form col-lg-6">
                                <label class="fw-bold mb-2">Last Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="last Name" id="last_name" name="last_name" class="form-control py-2 rounded-3 shadow-sm" value="{{ $user_details->last_name }}" readonly>
                            </div>
                        </div>
                       
                        <div class="row g-2 mb-4">
                            <div class="add_form col-lg-6 pe-4">
                                <label class="fw-bold mb-2"> Email <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Email" id="email" name="email" value="{{ $user_details->email }}" class="form-control py-2 rounded-3 shadow-sm" readonly>
                            </div>
                            <div class="add_form col-lg-6">
                                <label class="fw-bold mb-2"> Dob <span class="text-danger">*</span></label>
                                <input type="date" id="dob" name="dob" value="{{ $user_details->dob }}" class="form-control py-2 rounded-3 shadow-sm" readonly>
                            </div>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="add_form col-lg-6 pe-4">
                                <label class="fw-bold mb-2"> Dob <span class="text-danger">*</span></label>
                                <input type="date" id="dob" name="dob" value="{{ $user_details->dob }}" class="form-control py-2 rounded-3 shadow-sm" readonly>
                            </div>
                             <div class="add_form col-lg-6">
                                <label class="fw-bold mb-2"> Phone Number <span class="text-danger">*</span></label>
                                <input type="number" id="phone" placeholder="Phone Number" name="phone" value="{{ $user_details->phone }}" class="form-control py-2 rounded-3 shadow-sm" readonly>
                            </div>
                        </div>
                   
                        <div class="row g-2 mb-4">
                            <div class="add_form col-lg-6 pe-4">
                                <label class="fw-bold mb-2"> Street <span class="text-danger">*</span></label>
                                <input type="text" id="street" placeholder="Street" name="street" value="{{ $user_details->street }}" class="form-control py-2 rounded-3 shadow-sm" readonly>
                            </div>
                             <div class="add_form col-lg-6">
                                <label class="fw-bold mb-2"> City <span class="text-danger">*</span></label>
                                <input type="text" id="city" placeholder="City" name="city" value="{{ $user_details->city }}" class="form-control py-2 rounded-3 shadow-sm" readonly>
                            </div>
                        </div>
                      
                        <div class="row g-2 mb-4">
                            <div class="add_form col-lg-6 pe-4">
                                <label class="fw-bold mb-2"> State <span class="text-danger">*</span></label>
                                <input type="text" id="state" placeholder="State" name="state" value="{{ $user_details->state }}" class="form-control py-2 rounded-3 shadow-sm" readonly>
                            </div>
                             <div class="add_form col-lg-6">
                                <label class="fw-bold mb-2"> Zip/Postal Code <span class="text-danger">*</span></label>
                                <input type="number" id="zip_province_code" placeholder="Zip/Postal Code" name="zip_province_code" value="{{ $user_details->zip_province_code }}" class="form-control py-2 rounded-3 shadow-sm" readonly>
                            </div>
                        </div>
                      
                        <div class="row g-2 mb-4">
                            <div class="add_form col-lg-6 pe-4">
                                <label class="fw-bold mb-2"> Country <span class="text-danger">*</span></label>
                                <input type="text" id="country" placeholder="Country" name="country" value="{{ $user_details->country }}" class="form-control py-2 rounded-3 shadow-sm" readonly>
                            </div>
                             <div class="add_form col-lg-6">
                                <label class="fw-bold mb-2"> Preferred Language <span class="text-danger">*</span></label>
                                <input type="text" id="preferred_lang" placeholder="Preferred Language" name="preferred_lang" value="{{ $user_details->preferred_lang }}" class="form-control py-2 rounded-3 shadow-sm" readonly>
                            </div>
                        </div>
                       
                        <div class="row g-2 mt-3">
                            <div class="add_form col-lg-2">
                                <label class="fw-bold ">New Letter</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input check_bx" type="checkbox" id="newsletter_sub" name="newsletter_sub" {{ $user_details->newsletter_sub ? 'checked' : '' }}>
                                </div>
                            </div>
                             <div class="add_form col-lg-2">
                                <label class="fw-bold ">Terms And Condition</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input check_bx" type="checkbox" id="terms_condition" name="terms_condition" {{ $user_details->terms_condition ? 'checked' : '' }}>
                                </div>
                            </div>
                             <div class="add_form col-lg-2">
                                <label class="fw-bold ">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input check_bx" type="checkbox" id="status" name="status" {{ $user_details->status ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 text-center mt-5">
                        <a href="{{ route('admin.user_list') }}">
                            <button type="button" class="cancel-btn"> Cancel </button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection