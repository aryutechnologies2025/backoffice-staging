@extends('layouts.app')
@section('content')

<style>
    a:hover {
        color: rgb(27, 108, 138);
    }

    a {
        font-family: 'Poppins', sans-serif;
        font-weight:500;
        color:#8B7eff;
        font-size:13px;
    }

    .contact {
        font-family: 'Poppins', sans-serif;
        font-weight:600;
        color:#282833;
        font-size:13px;
    }

    .custom-message-modal {
        width: 100% !important;
        background: #29292960;
    }

    .sticky-btn {
        position: fixed;
        bottom: 10px;
        right: 10px;
        width: 40px;
        height: 40px;
        background-color: #ff0000ff;
    }
</style>

<div>

<div class="row body-sec py-3 px-5 justify-content-around" id="watch">

<div class="text-start col-lg-6">
<h3 class="admin-title fw-bold">{{$title}}</h3>
</div>

<div class="text-end col-lg-6">
<b>
<a href="/dashboard">Dashboard</a> >
<a class="contact" href="/contact-us">Contact-Us</a>
</b>
</div>

</div>


<div class="row body-sec px-5">
<div class="bg-white pt-3 col-lg-12">
<div class="table-sec rounded-bottom-4 mb-5">

<table id="cityTable" class="table table-bordered pt-2">

<thead>
<tr class="rounded-top-4">
<th class="text-start">S.No</th>
<th class="text-start">Name</th>
<th class="text-start">Email</th>
<th class="text-start">Phone</th>
<th class="text-start">Date & Time</th>
<th class="text-start">Message</th>
</tr>
</thead>

<tbody>

@if($contact_dts->isEmpty())

<tr>
<td colspan="6" class="text-center">No records</td>
</tr>

@else

@foreach ($contact_dts as $row)

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ $row->first_name }} {{ $row->last_name }}</td>

<td>{{ $row->email }}</td>

<td>{{ $row->phone }}</td>

<!-- TIMESTAMP DATE + TIME -->
<td>
{{ $row->created_at ? $row->created_at->format('d-m-Y h:i A') : '-' }}
</td>

<td>

<a href="#"
class="btn btn-sm view-message-btn"
title="View"
data-message="{{ htmlspecialchars($row->message, ENT_QUOTES) }}"
data-bs-toggle="modal"
data-bs-target="#customMessageModal">

<i class="fa fa-eye" style="color:#0d6efd;"></i>

</a>

<a href="javascript:void(0);"
class="table-link danger delconfirm"
title="Delete"
data-row_id="{{ $row->id }}"
data-act_url="{{ route('admin.contact_delete') }}"
data-csrf_token="{{ csrf_token() }}">

<span class="fa-stack">
<i class="fa fa-trash-o fa-stack-1x fa-inverse" style="color:red !important;"></i>
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


<!-- MESSAGE MODAL -->
<div class="custom-message-modal modal fade"
id="customMessageModal"
tabindex="-1"
aria-labelledby="customMessageModalLabel"
aria-hidden="true">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content shadow-lg">

<div class="modal-header">

<h5 class="modal-title">Message Details</h5>

<button type="button"
class="btn-close"
data-bs-dismiss="modal">
</button>

</div>

<div class="modal-body">

<p id="messageContent"></p>

</div>

<div class="modal-footer">

<button type="button"
class="btn btn-secondary text-white"
data-bs-dismiss="modal">

Close

</button>

</div>

</div>
</div>
</div>

</div>


<button class="sticky-btn" id="myBtn">
<a href="#watch">
<i class="bi bi-caret-up-square text-white"></i>
</a>
</button>

@endsection


@section('scripts')

<script>

$(document).ready(function(){

$('#cityTable').DataTable({

pageLength:10,
lengthChange:true,
ordering:true,
searching:true,

language:{
emptyTable:"No records found",
searchPlaceholder:"Search contacts...",
search:""
},

columnDefs:[
{ orderable:true, targets:[0,3] }
]

});


$('.view-message-btn').on('click',function(){

const message=$(this).data('message');

$('#messageContent').text(message);

});

});

</script>

@endsection