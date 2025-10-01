@extends('layouts.app')

@section('content')

<style>
    .modal {
        width: 100% !important;
        padding-top: 10% !important;
    }

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
<div class="row body-sec py-5 px-5 justify-content-around">
    <b class="mb-3"><a href="/dashboard">Dashboard</a> > <a class="" href="/enquiry">Booking</a> > <a class="enquiry" href="">FollowUp</a></b>

    <h2 class="fw-bold mb-3">Customer Travel Details</h2>

    <!-- Button to trigger modal -->
    <div class="">


        <div class="row mb-3">
            <div class="col-md-4">
                <strong><label for="name" class="form-label">Name:</strong>{{ $enquiry->name }}</label>
            </div>
            <div class="col-md-4">
                <strong><label for="email" class="form-label">Email:{{ $enquiry->email }}</strong></label>
            </div>
            <div class="col-md-4">
                <strong><label for="Phone" class="form-label">Phone:</strong>{{ $enquiry->phone }}</label>
            </div>

        </div>
        <div class="row mb-3">

            <div class="col-md-4">
                <strong><label for="program_title" class="form-label">Program Title: </strong>{{ $enquiry->program_title ?? 'null' }}</label>
            </div>
            <div class="col-md-4">
                <strong><label for="travel_date" class="form-label">Travel Date: </strong>{{ $enquiry->travel_date }} </label>
            </div>
            <div class="col-md-4">
                <strong><label for="budget_per_head" class="form-label">Budget: </strong>{{ $enquiry->budget_per_head }}</label>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <strong><label for="comments" class="form-label">Notes: </strong>{{ $enquiry->comments }}</label>
            </div>
        </div>

        <button type="button" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#travelDetailsModal">
            Open Form
        </button>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="travelDetailsModal" tabindex="-1" aria-labelledby="travelDetailsModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="travelDetailsModalLabel">Customer Travel Details Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form_valid" method="POST" action="{{ route('admin.enquiry.enquiryaddFollowUp', $enquiry->id) }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <label>Customer Name</label>
                                <input type="text" name="customer_name" class="form-control" value="{{ $enquiry->name }}">
                            </div>
                            <div class="col-md-4">
                                <label>Customer Location</label>
                                <input type="text" name="customer_location" class="form-control" value="{{ $enquiry->location }}">
                            </div>
                            <div class="col-md-4">
                                <label>Booking Date</label>
                                <input type="date" name="booking_date" class="form-control" value="{{ $enquiry->created_at ? date('Y-m-d', strtotime($enquiry->created_at)) : '' }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>Event Name</label>
                                <!-- <input type="text" name="event_name" class="form-control" required> -->
                                <select name="package_stay" id="package_stay" class="form-select">
                                    <option disabled selected>Select Program</option>
                                    @foreach($programdetails as $id => $name)
                                    <option value="{{$id}}">{{ $name }}</option>

                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>No. of Persons</label>
                                <input type="number" name="no_of_persons" class="form-control" value="{{ $enquiry->total_count }}">
                            </div>
                            <div class="col-md-4">
                                <label>Travel Start Date</label>
                                <input type="date" name="travel_start_date" class="form-control" value="{{ $enquiry->travel_date }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>Travel End date</label>
                                <input type="date" name="travel_end_date" class="form-control" value="{{ $enquiry->travel_enddate }}">
                            </div>
                            <div class="col-md-4">
                                <label>Native to Transport</label>
                                <select name="transportation_mode" class="form-control">
                                    <option value="Flight">Flight</option>
                                    <option value="Train">Train</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Travel Date</label>
                                <!-- <input type="datetime-local" name="travel_date_time" class="form-control"> -->
                                 <input type="date" name="travel_date_time" class="form-control"  value="{{ $enquiry->travel_date }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>Return To Native</label>
                                <select name="return_to_native" class="form-control">
                                    <option value="Flight">Flight</option>
                                    <option value="Train">Train</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Return Date</label>
                                <!-- <input type="datetime-local" name="return_date_time" class="form-control">
                                  -->
                                 <input type="date" name="return_date_time" class="form-control"  value="{{ $enquiry->travel_enddate }}">
                            </div>
                            <div class="col-md-4">
                                <label>Bus Service</label>
                                <select name="bus_service" class="form-control" id="bus_service" onchange="toggleBusDetails()">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bus Service Details -->
                        <div id="bus_details" class="mt-3" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Booked/Non-Booked</label>
                                    <select name="bus_status" class="form-control">
                                        <option value="Booked">Booked</option>
                                        <option value="Non-Booked">Non-Booked</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Bus Travel Date & Timing</label>
                                    <input type="datetime-local" name="bus_travel_date_time" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Cab Service Details (if bus is 'No') -->
                        <div id="cab_details" class="mt-3" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Cab Pickup Point & Name</label>
                                    <input type="text" name="cab_pickup" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label>Cab Travel Date & Timing</label>
                                    <input type="datetime-local" name="cab_travel_date_time" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <!-- Additional Program Details -->
                            <div class="col-md-6">
                                <label>Additional Program Details</label>
                                <input type="text" name="program_details" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>Anniversary/Birthday</label>
                                <input type="text" name="special_occasion" class="form-control">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <!-- Customer Stay List -->
                            <div class="col-md-6">
                                <label>Customer Total Stay List</label>
                                <textarea name="stay_list" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label>Property Name</label>
                                <input type="text" name="property_name" class="form-control">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Cab Service Details</label>
                                <input type="text" name="cab_service" class="form-control">
                            </div>
                            <!-- Trip Current Status -->
                            <div class="col-md-6">
                                <label>Trip Current Status</label>
                                <select name="trip_status" class="form-control">
                                    <option value="Completed">Completed</option>
                                    <option value="Non-Completed">Non-Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Follow-up History -->
    <h4 class="mt-5">Follow-up History</h4>
    <table class="table mb-5" id="followUpTable">
        <thead>
            <tr>
                <th class="text-center">Customer Name</th>
                <th class="text-center">Customer Location</th>
                <th class="text-center">Event Name</th>
                <th class="text-center">No Of Persons</th>
                <th class="text-center">Trip Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enquiry->enquiryFollowUps as $followUp)
            <tr>
                <td class="text-center">{{ $followUp->customer_name }}</td>
                <td class="text-center">{{ $followUp->customer_location }}</td>
                <td class="text-center">{{ $followUp->event_name }}</td>
                <td class="text-center">{{ $followUp->no_of_persons }}</td>
                <td class="text-center">{{ $followUp->trip_status }}</td>
                <td class="text-center">
                    <button class="btn btn-warning view-btn"
                        data-customer_name="{{ $followUp->customer_name }}"
                        data-customer_location="{{ $followUp->customer_location }}"
                        data-event_name="{{ $followUp->event_name }}"
                        data-no_of_persons="{{ $followUp->no_of_persons }}"
                        data-transportation_mode="{{ $followUp->transportation_mode }}"
                        data-travel_date_time="{{ $followUp->travel_date_time }}"
                        data-booking_date="{{ $followUp->booking_date }}"
                        data-travel_start_date="{{ $followUp->travel_start_date }}"
                        data-travel_end_date="{{ $followUp->travel_end_date }}"
                        data-return_mode="{{ $followUp->return_mode }}"
                        data-return_travel_date_time="{{ $followUp->return_travel_date_time }}"
                        data-bus_service="{{ $followUp->bus_service }}"
                        data-bus_status="{{ $followUp->bus_status }}"
                        data-bus_travel_date_time="{{ $followUp->bus_travel_date_time }}"
                        data-cab_pickup="{{ $followUp->cab_pickup }}"
                        data-cab_travel_date_time="{{ $followUp->cab_travel_date_time }}"
                        data-program_details="{{ $followUp->program_details }}"
                        data-special_occasion="{{ $followUp->special_occasion }}"
                        data-stay_list="{{ $followUp->stay_list }}"
                        data-property_name="{{ $followUp->property_name }}"
                        data-cab_service="{{ $followUp->cab_service }}"
                        data-trip_status="{{ $followUp->trip_status }}"
                        data-date="{{ $followUp->created_at->format('d/m/Y h:i:s') }}"
                        data-bs-toggle="modal"
                        data-bs-target="#viewModal">
                        <i class="bi bi-eye-fill"></i>

                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function toggleBusDetails() {
        let busService = document.getElementById("bus_service").value;
        document.getElementById("bus_details").style.display = (busService === "Yes") ? "block" : "none";
        document.getElementById("cab_details").style.display = (busService === "No") ? "block" : "none";
    }

    $(document).ready(function() {
        $('#followUpTable').DataTable();
    });


    $('.view-btn').on('click', function() {
        $('#modalcustomer_name').text($(this).data('customer_name'));
        $('#modalcustomer_location').text($(this).data('customer_location'));
        $('#modalevent_name').text($(this).data('event_name'));
        $('#modalno_of_persons').text($(this).data('no_of_persons'));
        $('#modaltransportation_mode').text($(this).data('transportation_mode'));
        $('#modaltravel_date_time').text($(this).data('travel_date_time'));
        $('#modalbooking_date').text($(this).data('booking_date'));
        $('#modaltravel_start_date').text($(this).data('travel_start_date'));
        $('#modaltravel_end_date').text($(this).data('travel_end_date'));
        $('#modalreturn_mode').text($(this).data('return_mode'));
        $('#modalreturn_travel_date_time').text($(this).data('return_travel_date_time'));
        $('#modalbus_service').text($(this).data('bus_service'));
        $('#modalbus_status').text($(this).data('bus_status'));
        $('#modalbus_travel_date_time').text($(this).data('bus_travel_date_time'));
        $('#modalcab_pickup').text($(this).data('cab_pickup'));
        $('#modalcab_travel_date_time').text($(this).data('cab_travel_date_time'));
        $('#modalprogram_details').text($(this).data('program_details'));
        $('#modalspecial_occasion').text($(this).data('special_occasion'));
        $('#modalstay_list').text($(this).data('stay_list'));
        $('#modalproperty_name').text($(this).data('property_name'));
        $('#modalcab_service').text($(this).data('cab_service'));
        $('#modaltrip_status').text($(this).data('trip_status'));
        $('#modaldate').text($(this).data('date'));
    });
</script>
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Enquiry Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Customer Name:</strong> <span id="modalcustomer_name"></span></p>
                <p><strong>Customer Location:</strong> <span id="modalcustomer_location"></span></p>
                <p><strong>Event Name:</strong> <span id="modalevent_name"></span></p>
                <p><strong>No of Persons:</strong> <span id="modalno_of_persons"></span></p>
                <p><strong>Transportation Mode:</strong> <span id="modaltransportation_mode"></span></p>
                <p><strong>Travel Date Time:</strong> <span id="modaltravel_date_time"></span></p>
                <p><strong>Booking Date:</strong> <span id="modalbooking_date"></span></p>
                <p><strong>Travel Start Date:</strong> <span id="modaltravel_start_date"></span></p>
                <p><strong>Travel End Date:</strong> <span id="modaltravel_end_date"></span></p>
                <p><strong>Return Mode:</strong> <span id="modalreturn_mode"></span></p>
                <p><strong>Return Travel Date Time:</strong> <span id="modalreturn_travel_date_time"></span></p>
                <p><strong>Bus Service:</strong> <span id="modalbus_service"></span></p>
                <p><strong>Bus Status:</strong> <span id="modalbus_status"></span></p>
                <p><strong>Bus Travel Date Time:</strong> <span id="modalbus_travel_date_time"></span></p>
                <p><strong>Cab Pickup:</strong> <span id="modalcab_pickup"></span></p>
                <p><strong>Cab Travel Date Time:</strong> <span id="modalcab_travel_date_time"></span></p>
                <p><strong>Program Details:</strong> <span id="modalprogram_details"></span></p>
                <p><strong>Special Occasion:</strong> <span id="modalspecial_occasion"></span></p>
                <p><strong>Stay List:</strong> <span id="modalstay_list"></span></p>
                <p><strong>Property Name:</strong> <span id="modalproperty_name"></span></p>
                <p><strong>Cab Service:</strong> <span id="modalcab_service"></span></p>
                <p><strong>Trip Status:</strong> <span id="modaltrip_status"></span></p>
                <p><strong>Date & Time:</strong> <span id="modalDate"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection