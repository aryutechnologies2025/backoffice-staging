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
</style>

<div class="row body-sec py-4 justify-content-around">
    <div class="col-lg-6">
        <b><a href="/dashboard">Dashboard</a> > <a class="" href="/enquiry">Enquiry List</a> >
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


<div class="row mb-12">
    <div class="col-md-4">
        <strong><label for="name" class="form-label">Name</label></strong>
        <input type="text" id="name" name="name" class="form-control" value="{{ $enquiry->name }}" disabled>
    </div>
    <div class="col-md-4">
        <strong><label for="email" class="form-label">Email</label></strong>
        <input type="email" id="email" name="email" class="form-control" value="{{ $enquiry->email }}" disabled>
    </div>
    <div class="col-md-4">
        <strong><label for="Phone" class="form-label">Phone</label></strong>
        <input type="number" id="Phone" name="Phone" class="form-control" value="{{ $enquiry->phone }}" disabled>
    </div>

</div>
<div class="row mb-3">
    <div class="col-md-4">
        <strong><label for="phone" class="form-label">Phone</label></strong>
        <input type="text" id="phone" name="phone" class="form-control" value="{{ $enquiry->phone }}" disabled>
    </div>
    <div class="col-md-4">
        <strong><label for="program_title" class="form-label">Program Title</label></strong>
        <input type="text" id="program_title" name="program_title" class="form-control" value="{{ $enquiry->program_title }}" disabled>
    </div>
    <div class="col-md-4">
        <strong><label for="travel_date" class="form-label">Travel Date</label></strong>
        <input type="text" id="travel_date" name="travel_date" class="form-control" value="{{ $enquiry->travel_date }}" disabled>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <strong><label for="budget_per_head" class="form-label">Budget</label></strong>
        <input type="text" id="budget_per_head" name="budget_per_head" class="form-control" value="{{ $enquiry->budget_per_head }}" disabled>
    </div>

    <div class="col-md-4">
        <strong><label for="comments" class="form-label">Notes</label></strong>
        <textarea id="comments" name="comments" class="form-control" disabled>{{ $enquiry->comments }}</textarea>
    </div>
</div>

<div class="col-lg-6 ">
    <div class="d-flex justify-content-start">
        <button class="mt-5 btn btn-primary">Add Follow-up data</button>
    </div>
</div>
<form id="form_valid" method="POST" action="{{ route('admin.enquiry.addFollowUp', $enquiry->id) }}" >
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('form').style.display = 'none';

            document.querySelector('button.mt-5').addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default button behavior
                document.querySelector('form').style.display = 'block';
            });
        });
        document.querySelector('button.mt-5').addEventListener('click', function(e) {
            document.querySelector('form').style.display = 'block';
            e.preventDefault();
        });

        document.addEventListener('DOMConten   tLoaded', function() {
            document.querySelector('form').style.display = 'none';
            e.preventDefault();
            
        });
    </script>
    @csrf
    <div class="row mb-3">
        <div class="col-md-4">
            <strong> <label  class="form-label">Follow-up Date <span class="text-danger">*</span></label></strong>
            
            <input type="date" id="follow_up_date" name="follow_up_date" class="form-control"  >
        </div>
        <div class="col-md-4">
            <strong><label for="lead_source" class="form-label">Lead Source <span class="text-danger">*</span> </label></strong>
            <select id="lead_source" name="lead_source" class="form-control" >
                <option value="Facebook">Facebook</option>
                <option value="Instagram">Instagram</option>
                <option value="Website">Website</option>
                <option value="Whatsapp">Whatsapp</option>
                <option value="Influencer">Influencer</option>
            </select>
        </div>
        <div class="col-md-4">
            <strong> <label for="lead_status" class="form-label">Lead Status <span class="text-danger">*</span></label></strong>
            <select id="lead_status" name="lead_status" class="form-control" >
                <option value="No Response-Initial">No Response-Initial</option>
                <option value="⁠No Response-In Process">⁠No Response-In Process</option>
                <option value="No Service">No Service</option>
                <option value="⁠In Enquiry Process">⁠In Enquiry Process</option>
                <option value="⁠⁠In Booking Process">⁠⁠In Booking Process</option>
                <option value="⁠⁠⁠Booking Confirmed">⁠⁠⁠Booking Confirmed</option>
                <option value="⁠⁠Trip Completed">⁠⁠Trip Completed</option>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <strong><label for="follow_up_notes" class="form-label">Follow-up Notes <span class="text-danger">*</span></label></strong>
            <textarea id="follow_up_notes" name="follow_up_notes" class="form-control" ></textarea>
        </div>

        <div class="col-md-4">
            <strong> <label for="action_required" class="form-label">Action Required <span class="text-danger">*</span></label></strong>
            <select id="action_required" name="action_required" class="form-control" >
                <option value="Opened">Opened</option>
                <option value="Closed">Closed</option>
            </select>
        </div>
        <div class="col-md-4">
            <strong><label for="deal_value" class="form-label">Deal Value <span class="text-danger">*</span></label></strong>
            <input type="number" step="0.01" id="deal_value" name="deal_value" class="form-control" >
        </div>
    </div>
    <div class="row mb-3 ">
        <div class="col-md-4">
            <strong> <label for="interest_prospect" class="form-label">Accepted Or Rejected</label> <span class="text-danger">*</span></strong>
            <select id="interest_prospect" name="interest_prospect" class="form-control" >
                <!-- <option disabled></option> -->
                <option value="accepted">Accepted</option>
                <option value="rejected">Rejected</option>
            </select>
            <!-- <input type="text" id="interest_prospect" name="interest_prospect" class="form-control"> -->
        </div>
        <div class="col-md-4">
            <strong><label for="assigned_to" class="form-label">Assigned To</label> <span class="text-danger">*</span></strong>
            <input type="text" id="assigned_to" name="assigned_to" class="form-control" >
        </div>
        <div class="col-md-4">
            <strong> <label for="interest_prospect" class="form-label">Next FollowUp Date</label><span class="text-danger">*</span></strong>
            <input type="date" id="next_follow_up_date" name="next_follow_up_date" class="form-control" >
        </div>
    </div>
    <button type="submit" class="btn btn-add">Submit</button>
</form>

<h4 class="mt-5">Follow-up History</h4>
<table class="table mb-5" id="followUpTable">
    <thead>
        <tr>
            <th>Date</th>
            <th>Interest</th>
            <th>Lead Source</th>
            <th>Lead Status</th>
            <th>Notes</th>
            <th>Action Required</th>
            <th>Deal Value</th>
            <th>Next FollowUp</th>
            <th>Assigned To</th>
        </tr>
    </thead>
    <tbody>
        @foreach($enquiry->followUps as $followUp)
        <tr>
            <td>{{ $followUp->follow_up_date }}</td>
            <td>{{ $followUp->interest_prospect }}</td>
            <td>{{ $followUp->lead_source }}</td>
            <td>{{ $followUp->lead_status }}</td>
            <td>{{ $followUp->follow_up_notes }}</td>
            <td>{{ $followUp->action_required }}</td>
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