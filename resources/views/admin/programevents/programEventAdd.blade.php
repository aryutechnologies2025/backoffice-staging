@extends('layouts.app')
@Section('content')
<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a href="{{ route('admin.programeventslist') }}">Events</a> > <a
                class="add">Add</a></b>
    </div>

</div>
<div class="row mb-5">
    <div class="col-lg-12">
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form class="" id="form_valid" action="#" method="POST"
                autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <div class="row g-4">
                        <div class="add_form col-md-4">
                            <label class="mb-2">Title <span class="text-danger">*</span></label>
                            <input type="text" placeholder="Title" id="title" name="title"
                                class="form-control py-2 rounded-3 shadow-sm" value="{{ old('title') }}">
                        </div>

                        <div class="add_form col-md-4">
                            <label class="mb-2">Event Type<span class="text-danger">*</span></label>
                            <select id="event_type" name="event_type"
                                class="form-select py-2 rounded-3 shadow-sm" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="public">Public</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="row g-4">
                        <!-- Start Date -->
                        <div class="col-md-4 mb-3">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="text" class="form-control datepicker" id="startDate" placeholder="Select start date">
                        </div>

                        <!-- Start Time -->
                        <div class="col-md-6 mb-3">
                            <label for="startTime" class="form-label">Start Time</label>
                            <select class="form-select" id="startTime">
                                <option value="">Select start time</option>
                                <option value="08:00 AM">08:00 AM</option>
                                <option value="09:00 AM">09:00 AM</option>
                                <option value="10:00 AM">10:00 AM</option>
                                <option value="11:00 AM" selected>11:00 AM</option>
                                <option value="12:00 PM">12:00 PM</option>
                                <option value="01:00 PM">01:00 PM</option>
                                <option value="02:00 PM">02:00 PM</option>
                                <option value="03:00 PM">03:00 PM</option>
                                <option value="04:00 PM">04:00 PM</option>
                                <option value="05:00 PM">05:00 PM</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection