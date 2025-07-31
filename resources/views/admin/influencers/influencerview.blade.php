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
<div class="container-wrapper pt-5">
    <div class="row">
        <b><a href="/dashboard">Dashboard</a> > <a href="{{ route('admin.influencer_list') }}">Influencers</a> > <a class="edit">View</a></b>
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

            <div class="mb-3">
                <div class="row g-2 mb-4 d-flex">
                    <div class="col">
                        <label class="fw-bold mb-4">Name:</label>
                        <span class="ms-5">
                            {{ $user_details->full_name }}
                        </span>
                    </div>

                    <div class="col">
                        <label class="fw-bold mb-4 ">Email:</label>
                        <span class="ms-5">{{ $user_details->email }}</span>
                    </div>
                </div>
                <div class="row g-2 mb-4 d-flex">
                    <div class="col">
                        <label class="fw-bold mb-4">Phone:</label>
                        <span class="ms-5">{{ $user_details->phone }}</span>
                    </div>

                    <div class="col">
                        <label class="fw-bold mb-4 ">Whatsapp:</label>
                        <span class="ms-5">{{ $user_details->whatsapp }}</span>
                    </div>
                </div>
                <div class="row g-2 mb-4 d-flex">
                    <div class="col">
                        <label class="fw-bold mb-4">Gender:</label>
                        <span class="ms-5">{{ $user_details->gender }}</span>
                    </div>

                    <div class="col">
                        <label class="fw-bold mb-4">Age:</label>
                        <span class="ms-5">{{ $user_details->age }}</span>
                    </div>

                </div>
                <div class="row g-2 mb-4 d-flex">

                    <div class="col">
                        <label class="fw-bold mb-4 ">City:</label>
                        <span class="ms-5">{{ $user_details->city }}</span>
                    </div>
                </div>
                <div class="row g-2 mb-4 d-flex">
                    <div class="col">
                        <label class="fw-bold mb-4">Instagram Name:</label>
                        <span class="ms-5">{{ $user_details->instagram_name }}</span>
                    </div>

                    <div class="col">
                        <label class="fw-bold mb-4 ">Instagram Link:</label>
                        <span class="ms-5">{{ $user_details->instagram_profile_link }}</span>
                    </div>
                </div>
                <div class="row g-2 mb-4 d-flex">
                    <div class="col">
                        <label class="fw-bold mb-4">LinkedIn Name:</label>
                        <span class="ms-5">{{ $user_details->linkedin_name }}</span>
                    </div>

                    <div class="col">
                        <label class="fw-bold mb-4 ">LinkedIn Link:</label>
                        <span class="ms-5">{{ $user_details->linkedin_profile_link }}</span>
                    </div>
                </div>
                <div class="row g-2 mb-4 d-flex">
                    <div class="col">
                        <label class="fw-bold mb-4">Youtube Name:</label>
                        <span class="ms-5">{{ $user_details->youtube_name }}</span>
                    </div>

                    <div class="col">
                        <label class="fw-bold mb-4 ">Youtube Link:</label>
                        <span class="ms-5">{{ $user_details->youtube_profile_link }}</span>
                    </div>
                </div>

                <div class="row g-2 mb-4 d-flex">
                    <div class="col">
                        <label class="fw-bold mb-4">Twitter Name:</label>
                        <span class="ms-5">{{ $user_details->twitter_name }}</span>
                    </div>

                     <div class="col">
                        <label class="fw-bold mb-4 ">Twitter Link:</label>
                        <span class="ms-5">{{ $user_details->twitter_profile_link }}</span>
                    </div>

                </div>

                 <div class="row g-2 mb-4 d-flex">
                    <div class="col">
                        <label class="fw-bold mb-4">Facebook Name:</label>
                        <span class="ms-5">{{ $user_details->facebook_name }}</span>
                    </div>

                    <div class="col">
                        <label class="fw-bold mb-4 ">Facebook Link:</label>
                        <span class="ms-5">{{ $user_details->facebook_profile_link }}</span>
                    </div>
                </div>

                <div class="row g-2 mb-4 d-flex">
                    <div class="col">
                        <label class="fw-bold mb-4 ">Date & Time:</label>
                        <span class="ms-5">{{ $user_details->created_at->format('d/m/Y h:i:s') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 text-end mt-5">
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