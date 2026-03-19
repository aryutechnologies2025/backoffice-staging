@extends('layouts.app')
@section('content')
<style>
    a:hover {
        color: red;
    }

    a {
        color: rgb(37, 150, 190);
    }

    .enquiry {
        color: blue;
    }
</style>

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a class="" href="/enquiry">Booking</a></b> > <a class="enquiry" href="">Add Booking</a>
    </div>

</div>
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="row mb-5">
    <div class="col-lg-12">
        <!-- INFORMATION -->
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form action="{{ route('admin.enquiry_store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="add_form col-md-4 mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone" required>
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" name="location" required>
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="days" class="form-label">Number of Days</label>
                        <input type="number" class="form-control" name="days" required>
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="travel_destination" class="form-label">Destination</label>
                        <input type="text" class="form-control" name="travel_destination" required>
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="budget_per_head" class="form-label">Budget Per Person</label>
                        <input type="number" class="form-control" name="budget_per_head" required>
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="cab_need" class="form-label">Need a Cab?</label>
                        <select class="form-control" name="cab_need" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="total_count" class="form-label">Total Travelers</label>
                        <input type="number" class="form-control" name="total_count" required>
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="male_count" class="form-label">Male Count</label>
                        <input type="number" class="form-control" name="male_count" required>
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="female_count" class="form-label">Female Count</label>
                        <input type="number" class="form-control" name="female_count" required>
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="travel_date" class="form-label">Travel Date</label>
                        <input type="date" class="form-control" name="travel_date" required>
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="rooms_count" class="form-label">Rooms Needed</label>
                        <input type="number" class="form-control" name="rooms_count" required>
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="program_title" class="form-label">Program Title</label>
                        <input type="text" class="form-control" name="program_title">
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="child_count" class="form-label">Number of Children</label>
                        <input type="number" class="form-control" name="child_count" required>
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="child_age" class="form-label">Child Age</label>
                        <input type="text" class="form-control" name="child_age">
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="engagement_date" class="form-label">Engagement Date</label>
                        <input type="date" class="form-control" name="engagement_date">
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="birth_date" class="form-label">Birth Date</label>
                        <input type="date" class="form-control" name="birth_date">
                    </div>

                    <div class="add_form col-md-4 mb-3">
                        <label for="comments" class="form-label">Comments</label>
                        <textarea class="form-control" name="comments" rows="3"></textarea>
                    </div>

                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="submit-btn mb-5">Submit </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection



