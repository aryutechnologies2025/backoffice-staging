@extends('layouts.app')

@section('content')
<style>
    .table thead>tr>th {
        background-color: #C2C2C2 !important;
    }

    .btn-add {
        background-color: green !important;
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

    .modal {
        width: 100% !important;
        padding-top: 10% !important;
    }
</style>

<div class="row body-sec py-4 justify-content-around ">
    <div class="col-lg-6">
        <b><a href="/dashboard">Dashboard</a> > <a class="" href="/home-enquiry">Enquiry List</a> >
            <a class="enquiry" href="">FollowUp</a>
        </b>
        <br><br>
        <h3 class="fw-bold">Follow-Up-List</h3>
    </div>
    <div class="col-lg-6">
        <div class="d-flex justify-content-end">
            <!-- <button class="mt-5 btn btn-primary">Add Follow-up data</button> -->
        </div>
    </div>
</div>


<div class="row mb-3 ">
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

<div class="row mb-3">


    <div class="col-md-4">
        <strong><label for="comments" class="form-label">Notes: </strong>{{ $enquiry->comments }}</label>
    </div>
</div>

<div class="col-lg-6">
    <div class="d-flex justify-content-start">
        <button class="mt-5 btn btn-primary text-white" id="openFormButton">Add Follow-up data</button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="followUpModals" tabindex="-1" aria-labelledby="followUpModalLabels" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="followUpModalLabels">Add Follow-up Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_valid" method="POST" action="{{ route('admin.enquiry.addFollowUp', $enquiry->id) }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><label class="form-label">Follow-up Date <span class="text-danger">*</span></label></strong>
                            <input type="date" id="follow_up_date" name="follow_up_date" class="form-control" value="{{ old('follow_up_date', date('Y-m-d')) }}" >
                        </div>
                        <div class="col-md-6">
                            <strong><label for="lead_source" class="form-label">Lead Source <span class="text-danger">*</span></label></strong>
                            <select id="lead_source" name="lead_source" class="form-control">
                                <option value="Facebook">Facebook</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Website">Website</option>
                                <option value="Whatsapp">Whatsapp</option>
                                <option value="Influencer">Influencer</option>
                                <option value="Social Media">Social Media</option>
                                <option value="Travel Agents">Travel Agents</option>
                                <option value="Freelancers">Freelancers</option>
                                <option value="Affiliate">Affiliate</option>
                                <option value="Referal">Referal</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><label for="lead_status" class="form-label">Lead Status <span class="text-danger">*</span></label></strong>
                            <select id="lead_status" name="lead_status" class="form-control">
                                <option value="No Response-Initial">No Response-Initial</option>
                                <option value="⁠No Response-In Process">⁠No Response-In Process</option>
                                <option value="No Service">No Service</option>
                                <option value="⁠In Enquiry Process">⁠In Enquiry Process</option>
                                <option value="⁠⁠In Booking Process">⁠⁠In Booking Process</option>
                                <option value="⁠⁠⁠Booking Confirmed">⁠⁠⁠Booking Confirmed</option>
                                <option value="⁠⁠Trip Completed">⁠⁠Trip Completed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <strong><label for="follow_up_notes" class="form-label">Follow-up Notes <span class="text-danger">*</span></label></strong>
                            <textarea id="follow_up_notes" name="follow_up_notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">

                        <div class="col-md-6">
                            <strong><label for="deal_value" class="form-label">Deal Value <span class="text-danger">*</span></label></strong>
                            <input type="number" step="0.01" id="deal_value" name="deal_value" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <strong><label for="assigned_to" class="form-label">Assigned To <span class="text-danger">*</span></label></strong>
                            <input type="text" id="assigned_to" name="assigned_to" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><label for="next_follow_up_date" class="form-label">Next FollowUp Date <span class="text-danger">*</span></label></strong>
                            <input type="date" id="next_follow_up_date" name="next_follow_up_date" class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-add">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('followUpModals'), {
            keyboard: false
        });

        document.getElementById('openFormButton').addEventListener('click', function() {
            myModal.show();
        });
    });
</script>

<h4 class="mt-5">Follow-up History</h4>
<table class="table mb-5" id="followUpTable">
    <thead>
        <tr>
            <th>Date</th>
            <th>Lead Source</th>
            <th>Lead Status</th>
            <th>Notes</th>
            <th>Deal Value</th>
            <th>Next FollowUp</th>
            <th>Assigned To</th>
        </tr>
    </thead>
    <tbody>
        @foreach($enquiry->followUps as $followUp)
        <tr>
            <td>{{ $followUp->follow_up_date }}</td>
            <td>{{ $followUp->lead_source }}</td>
            <td>{{ $followUp->lead_status }}</td>
            <td>{{ $followUp->follow_up_notes }}</td>
            <td>{{ $followUp->deal_value }}</td>
            <td>{{$followUp->next_follow_up_date }}</td>
            <td>{{ $followUp->assigned_to }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

<script>
    $(document).ready(function() {
        $('#followUpTable').DataTable();
    });
</script>
@endsection