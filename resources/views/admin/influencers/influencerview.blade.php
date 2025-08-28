@extends('layouts.app')
@Section('content')
<style>
    a:hover {
        color: red;
    }

    a {
        color: rgb(37, 150, 190);
    }

    .edit {
        color: blue;
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
        <b><a href="/dashboard">Dashboard</a> > <a href="{{ route('admin.influencer_list') }}">Influencers</a> > <a class="edit">View</a></b>
    </div>

</div>
<div class="row mb-5">
    <div class="col-lg-12">
        <!-- INFORMATION -->
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">

            <div class="mb-3">

                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Name:</label>
                        <input type="text" class="form-control "
                            name="name"
                            value="{{ $user_details->full_name }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Email:</label>
                        <input type="email" class="form-control"
                            name="email"
                            value="{{ $user_details->email }}"
                            readonly>
                    </div>
                </div>

                 <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Phone:</label>
                        <input type="Phone" class="form-control "
                            name="name"
                            value="{{ $user_details->phone }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Whatsapp:</label>
                        <input type="email" class="form-control"
                            name="email"
                            value="{{ $user_details->whatsapp }}"
                            readonly>
                    </div>
                </div>

                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Gender:</label>
                        <input type="text" class="form-control "
                            name="gender"
                            value="{{ $user_details->gender }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Age:</label>
                        <input type="email" class="form-control"
                            name="age"
                            value="{{ $user_details->age }}"
                            readonly>
                    </div>
                </div>

                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col-lg-6 pe-3">
                        <label class="fw-bold mb-2">City:</label>
                        <input type="text" class="form-control "
                            name="city"
                            value="{{ $user_details->city }}"
                            readonly>
                    </div>

                </div>

                
                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Instagram Name</label>
                        <input type="text" class="form-control "
                            name="Instagram Name"
                            value="{{ $user_details->instagram_name }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Instagram Link</label>
                        <input type="text" class="form-control"
                            name="Instagram Link"
                            value="{{ $user_details->instagram_profile_link }}"
                            readonly>
                    </div>
                </div>

                 <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">LinkedIn Name</label>
                        <input type="text" class="form-control "
                            name="LinkedIn Name"
                            value="{{ $user_details->linkedin_name }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">LinkedIn Link</label>
                        <input type="text" class="form-control"
                            name="LinkedIn Link"
                            value="{{ $user_details->linkedin_profile_link }}"
                            readonly>
                    </div>
                </div>


                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Youtube Name</label>
                        <input type="text" class="form-control "
                            name="Youtube Name"
                            value="{{ $user_details->youtube_name }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Youtube Link</label>
                        <input type="text" class="form-control"
                            name="Youtube Link"
                            value="{{ $user_details->youtube_profile_link }}"
                            readonly>
                    </div>
                </div>


                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Twitter Name</label>
                        <input type="text" class="form-control "
                            name="Twitter Name"
                            value="{{ $user_details->twitter_name }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Twitter Link</label>
                        <input type="text" class="form-control"
                            name="Twitter Link"
                            value="{{ $user_details->twitter_profile_link }}"
                            readonly>
                    </div>
                </div>


                
                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Facebook Name</label>
                        <input type="text" class="form-control "
                            name="Facebook Name"
                            value="{{ $user_details->facebook_name }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Facebook Link</label>
                        <input type="text" class="form-control"
                            name="Facebook Link"
                            value="{{ $user_details->facebook_profile_link }}"
                            readonly>
                    </div>
                </div>


                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col-lg-6 pe-3">
                        <label class="fw-bold mb-2">Date & Time:</label>
                        <input type="text" class="form-control"
                            name="Date & Time"
                            value="{{ $user_details->created_at->format('d/m/Y h:i:s') }}"
                            readonly>
                    </div>

                </div>
            </div>

            <div class="col-lg-12 text-center mt-5">
                <a href="{{ route('admin.influencer_list') }}">
                    <button type="button" class="cancel-btn"> Cancel </button>
                </a>
            </div>
            </form>
        </div>
    </div>
</div>

</div>

@endsection