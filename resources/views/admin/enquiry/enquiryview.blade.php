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
        <b><a href="/dashboard">Dashboard</a> > <a href="/user">User</a> > <a class="edit">View</a></b>
    </div>

</div>

<!-- FORM -->
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
                            value="{{ $user_details->name ?? '' }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Email:</label>
                        <input type="email" class="form-control"
                            name="email"
                            value="{{ $user_details->email ?? '' }}"
                            readonly>
                    </div>
                </div>
                  
                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Phone:</label>
                        <input type="number" class="form-control "
                            name="phone"
                            value="{{ $user_details->phone ?? '' }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Location:</label>
                        <input type="text" class="form-control"
                            name="location"
                            value="{{ $user_details->location ?? '' }}"
                            readonly>
                    </div>
                </div>

                 <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Days:</label>
                        <input type="number" class="form-control "
                            name="days"
                            value="{{ $user_details->days ?? '' }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">No.of.child:</label>
                        <input type="text" class="form-control"
                            name="no.of.child"
                            value="{{ $user_details->child_count ?? '' }}"
                            readonly>
                    </div>
                </div>

                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Travel Destination:</label>
                        <input type="text" class="form-control "
                            name="Travel Destination"
                            value="{{ $user_details->travel_destination ?? '' }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Budget:</label>
                        <input type="number" class="form-control"
                            name="Budget"
                            value="{{ $user_details->pricing ?? $user_details->budget_per_head ?? '' }}"
                            readonly>
                    </div>
                </div>
  
                 <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Cab Need:</label>
                        <input type="text" class="form-control "
                            name="cab Need"
                            value="{{ $user_details->cab_need ?? '' }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Total Count:</label>
                        <input type="number" class="form-control"
                            name="Total Count"
                            value="{{ $user_details->total_count ?? '' }}"
                            readonly>
                    </div>
                </div>


               <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Male Count:</label>
                        <input type="text" class="form-control "
                            name="Male Count"
                            value="{{ $user_details->male_count ?? '' }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Female Count:</label>
                        <input type="text" class="form-control"
                            name="Female Count"
                            value="{{ $user_details->female_count ?? '' }}"
                            readonly>
                    </div>
                </div>


                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Travel Date:</label>
                        <input type="text" class="form-control "
                            name="Travel date"
                            value="{{ $user_details->travel_date ?? '' }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Rooms Count:</label>
                        <input type="text" class="form-control"
                            name="Rooms Count"
                            value="{{ $user_details->rooms_count ?? '' }}"
                            readonly>
                    </div>
                </div>

                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Comments:</label>
                        <input type="text" class="form-control "
                            name="Comments"
                            value="{{ $user_details->comments ?? '' }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Date & Time:</label>
                        <input type="text" class="form-control"
                            name="Date & Time"
                            value="{{ $user_details->created_at ? $user_details->created_at->format('d/m/Y h:i:s') : '' }}"
                            readonly>
                    </div>
                </div>  

            <div class="col-lg-12 text-center mt-5">
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