@extends('layouts.app')
@section('content')

<style>
a:hover {
color: rgb(27,108,138);
}

a{
font-family:'Poppins',sans-serif;
font-weight:500;
color:#8B7eff;
font-size:13px;
}

.enquiry{
font-family:'Poppins',sans-serif;
font-weight:600;
color:#282833;
font-size:13px;
}

.modal{
width:100%!important;
padding-top:10%!important;
}

.btn{
border-radius:6px!important;
color:#FFF!important;
font-size:15px!important;
}

.custom-message-modal{
width:100%!important;
background:#29292960;
}

.sticky-btn{
position:fixed;
bottom:10px;
right:10px;
width:40px;
height:40px;
background-color:#ff0000ff;
}

@media(max-width:768px){
.sticky-btn{
top:20px;
}
}
</style>


<div class="row body-sec py-3 px-5 justify-content-around" id="watch">

<div class="text-start col-lg-6">
<h3 class="admin-title fw-bold">{{ $title }}</h3>
</div>

<div class="text-end col-lg-6">
<b>
<a href="/dashboard">Dashboard</a> >
<a class="enquiry">Booking</a>
</b>
</div>

<div class="mt-2 mb-2 col-lg-12">
<div class="d-flex justify-content-end">
<a href="{{ route('admin.enquiry_add_form') }}">
<button class="btn-add px-4" type="button">Add Booking</button>
</a>
</div>
</div>

</div>


<div class="row body-sec px-5">

<form method="get" action="{{ route('admin.enquiry_list') }}">

<div class="row">

<div class="col-md-2 mb-3">
<label class="form-label">From Date</label>
<input type="date" name="from_date" class="form-control"
value="{{ request('from_date') }}">
</div>

<div class="col-md-2 mb-3">
<label class="form-label">To Date</label>
<input type="date" name="to_date" class="form-control"
value="{{ request('to_date') }}">
</div>

<div class="col-md-3 mb-3 mt-3">
<div class="pt-3 d-flex gap-2">
<button type="submit" class="btn btn-primary flex-fill">
<i class="fa fa-filter"></i> Filter
</button>
</div>
</div>

</div>

</form>

</div>


<div class="row body-sec px-5">

<div class="bg-white pt-3 col-lg-12">

<div class="table-sec rounded-bottom-4 mb-5">

<button id="downloadExcel" class="btn btn-success mb-3">Download List</button>

<table id="cityTable" class="table table-bordered pt-2" style="width:100%;">

<thead>

<tr class="rounded-top-4">

<th>S.No</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Budget</th>
<th>Program Name</th>
<th>Trip Date</th>

<!-- TIMESTAMP COLUMN -->
<th>Date & Time</th>

<th>Mail Processing</th>
<th>Action</th>

</tr>

</thead>

<tbody>

@if ($enquiry_dts->isEmpty())

<tr>
<td colspan="9" class="text-center">No records</td>
</tr>

@else

@foreach ($enquiry_dts as $row)

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ $row->name }}</td>

<td>{{ $row->email }}</td>

<td>{{ $row->phone }}</td>

<td>{{ $row->pricing }}</td>

<td>{{ $row->program_title ?? 'null' }}</td>

<td>{{ $row->travel_date ?? 'null' }}</td>


<!-- TIMESTAMP -->
<td>
{{ $row->created_at ? $row->created_at->format('d-m-Y h:i A') : '-' }}
</td>


<td>

<select name="email_template"
class="form-select mailtemplate"
data-id="{{ $row->id }}"
data-current-template="{{ $row->email_template ?? '' }}">

<option value="" disabled selected>Select a mail template</option>

<option value="send_booking_process"
{{ ($row->email_template ?? '') == 'send_booking_process' ? 'selected' : '' }}>
Send booking process
</option>

<option value="advance_payment_completed"
{{ ($row->email_template ?? '') == 'advance_payment_completed' ? 'selected' : '' }}>
Advance payment completed
</option>

<option value="final_payment_completed"
{{ ($row->email_template ?? '') == 'final_payment_completed' ? 'selected' : '' }}>
Final payment completed
</option>

<option value="trip_completed"
{{ ($row->email_template ?? '') == 'trip_completed' ? 'selected' : '' }}>
Trip completed
</option>

<option value="trip_cancelled"
{{ ($row->email_template ?? '') == 'trip_cancelled' ? 'selected' : '' }}>
Trip cancelled
</option>

</select>

</td>


<td class="d-flex gap-1">

<a class="btn view-btn"
title="View"
href="{{ route('admin.enquiry_view',$row->id) }}">
<i class="bi bi-eye-fill" style="color:#000!important;"></i>
</a>

<a href="{{ route('admin.enquiry.enquiryfollowups',$row->id) }}"
title="List"
class="btn">
<i class="bi bi-list-check" style="color:blue!important;"></i>
</a>

<a href="javascript:void(0);"
class="table-link danger delconfirm"
title="Delete"
data-row_id="{{ $row->id }}"
data-act_url="{{ route('admin.enquiry_delete') }}"
data-csrf_token="{{ csrf_token() }}">

<span class="fa-stack">
<i class="fa fa-trash-o fa-stack-1x fa-inverse"
style="color:red!important;"></i>
</span>

</a>

</td>

</tr>

@endforeach

@endif

</tbody>

</table>

</div>

</div>

</div>


<button class="sticky-btn" id="myBtn">
<a href="#watch">
<i class="bi bi-caret-up-square text-white"></i>
</a>
</button>

@endsection