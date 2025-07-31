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
        <b><a href="/dashboard">Dashboard</a> > <a href="/user">User</a> > <a class="edit">View</a></b>
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
                        <span class="ms-5">{{ $user_details->name }}</span>
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
                        <label class="fw-bold mb-4 ">Location:</label>
                        <span class="ms-5">{{ $user_details->location }}</span>
                    </div>
                </div>
                <div class="row g-2 mb-4 d-flex">
                    <div class="col">
                        <label class="fw-bold mb-4">Days:</label>
                        <span class="ms-5">{{ $user_details->days }}</span>
                    </div>

                    <div class="col">
                        <label class="fw-bold mb-4 ">No.of.child:</label>
                        <span class="ms-5">{{ $user_details->child_count }}</span>
                    </div>
                </div>
                <div class="row g-2 mb-4 d-flex">
                    <div class="col">
                        <label class="fw-bold mb-4">Travel Destination:</label>
                        <span class="ms-5">{{ $user_details->travel_destination }}</span>
                    </div>

                    <div class="col">
                        <label class="fw-bold mb-4 ">Budget:</label>
                        <span class="ms-5">{{ $user_details->pricing }}</span>
                    </div>
                </div>
                <div class="row g-2 mb-4 d-flex">
                    <div class="col">
                        <label class="fw-bold mb-4">Cab Need:</label>
                        <span class="ms-5">{{ $user_details->cab_need }}</span>
                    </div>

                    <div class="col">
                        <label class="fw-bold mb-4 ">Total Count:</label>
                        <span class="ms-5">{{ $user_details->total_count }}</span>
                    </div>
                </div>
                <div class="row g-2 mb-4 d-flex">
                    <div class="col">
                        <label class="fw-bold mb-4">Male Count:</label>
                        <span class="ms-5">{{ $user_details->male_count }}</span>
                    </div>

                    <div class="col">
                        <label class="fw-bold mb-4 ">Female Count:</label>
                        <span class="ms-5">{{ $user_details->female_count }}</span>
                    </div>
                </div>
                <div class="row g-2 mb-4 d-flex">
                    <div class="col">
                        <label class="fw-bold mb-4">Travel Date:</label>
                        <span class="ms-5">{{ $user_details->travel_date }}</span>
                    </div>

                    <div class="col">
                        <label class="fw-bold mb-4 ">Rooms Count:</label>
                        <span class="ms-5">{{ $user_details->rooms_count }}</span>
                    </div>
                </div>

                <div class="row g-2 mb-4 d-flex">
                    <div class="col">
                        <label class="fw-bold mb-4">Comments:</label>
                        <span class="ms-5">{{ $user_details->comments }}</span>
                    </div>

                    <div class="col">
                        <label class="fw-bold mb-4 ">Date & Time:</label>
                        <span class="ms-5">{{ $user_details->created_at->format('d/m/Y h:i:s') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 text-end mt-5">
                <a href="{{ route('admin.enquiry_list') }}">
                    <button type="button" class="cancel-btn"> Cancel </button>
                </a>
            </div>
            </form>
        </div>
    </div>
</div>

</div>

@endsection