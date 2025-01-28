@extends('layouts.app')

@section('content')
<style>
    .table thead>tr>th{
        background-color: #C2C2C2 !important;
        
    }
  .btn-add{
    background-color: green!important;
  }
  a:hover {
        color: red;
    }
    a {
        color:rgb(37, 150, 190);
    }
    .enquiry {
        color:blue;
    }
</style>
<div class="container py-5">
<b><a href="/dashboard" >Dashboard</a> > <a class="" href="/enquiry" >Enquiry List</a> >
<a class="enquiry" href="" >FollowUp</a>

</b>
<br><br>
    <h3>Follow-Ups for: {{ $enquiry->name }}</h3>

    <form method="POST" action="{{ route('admin.enquiry.addFollowUp', $enquiry->id) }}">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="follow_up_date" class="form-label">Follow-up Date</label>
                <input type="date" id="follow_up_date" name="follow_up_date" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="lead_source" class="form-label">Lead Source</label>
                <select id="lead_source" name="lead_source" class="form-control" onchange="toggleOtherLeadSource(this)">
                    <option value="Facebook">Facebook</option>
                    <option value="Instagram">Instagram</option>
                    <option value="Website">Website</option>
                    <option value="Whatsapp">Whatsapp</option>
                    <option value="Influencer">Influencer</option>
                    <option value="Others">Others</option>
                </select>
                <input type="text" id="other_lead_source" name="lead_source" class="form-control mt-2" style="display:none;" placeholder="Please specify">
            </div>

<script>
    function toggleOtherLeadSource(select) {
        var otherInput = document.getElementById('other_lead_source');
        if (select.value === 'Others') {
            otherInput.style.display = 'block';
        } else {
            otherInput.style.display = 'none';
        }
    }
</script>
            <div class="col-md-4">
                <label for="lead_status" class="form-label">Lead Status</label>
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
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="follow_up_notes" class="form-label">Follow-up Notes</label>
                <textarea id="follow_up_notes" name="follow_up_notes" class="form-control"></textarea>
            </div>
            <div class="col-md-4">
                <label for="action_required" class="form-label">Action Required</label>
                <select id="action_required" name="action_required" class="form-control">
                    <option value="Opened">Opened</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="deal_value" class="form-label">Deal Value</label>
                <input type="number" step="0.01" id="deal_value" name="deal_value" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="interest_prospect" class="form-label">Accepted Or Rejected</label>
                <input type="text" id="interest_prospect" name="interest_prospect" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="assigned_to" class="form-label">Assigned To</label>
                <input type="text" id="assigned_to" name="assigned_to" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-add">Add Follow-up</button>
    </form>

    <h4 class="mt-5 ">Follow-up History</h4>
    <table class="table ">
        <thead>
            <tr>
                <th>Date</th>
                <th>Interest</th>
                <th>Lead Source</th>
                <th>Lead Status</th>
                <th>Notes</th>
                <th>Action Required</th>
                <th>Deal Value</th>
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
                <td>{{ $followUp->assigned_to }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection