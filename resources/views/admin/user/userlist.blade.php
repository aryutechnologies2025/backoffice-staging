@extends('layouts.app')
@Section('content')
<style>
    a:hover {
        color: rgb(27, 108, 138);
    }

    a {
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        color: #8B7eff;
        font-size: 13px;
    }

    .user {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        color: #282833;
        font-size: 13px;
    }
</style>

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{ $title }}</h3>
    </div>

    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a class="user" href="/user">User</a></b>
    </div>

    <div class="mt-2 mb-2 col-lg-12">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.user_add_form') }}">
                <button class="btn-add px-4" type="button">Add User</button>
            </a>
        </div>
    </div>

</div>

<!-- USER LIST -->
<div class="row body-sec px-5">
<div class="bg-white pt-3 col-lg-12">
<div class="table-sec rounded-bottom-4 mb-5">

<table id="cityTable" class="table table-bordered pt-2">

<thead>
<tr class="rounded-top-4">
<th class="text-start">S.No</th>
<th class="text-start">Name</th>
<th class="text-start">Email</th>
<th class="text-start">Phone Number</th>
<th class="text-start">Address</th>
<th class="text-start">Created By</th>
<th class="text-start">Date</th>
<th class="text-start">Status</th>
<th class="text-start">Action</th>
</tr>
</thead>

<tbody>

@if ($user_dts->isEmpty())

<tr>
<td colspan="8" class="text-center">No records</td>
</tr>

@else

@foreach ($user_dts as $row)

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ $row->first_name }} {{ $row->last_name }}</td>

<td>{{ $row->email }}</td>

<td>{{ $row->phone }}</td>

<td>{{ $row->city }}</td>

<td>{{ $row->created_by ?? 'admin' }}</td>

<!-- TIMESTAMP DATE + TIME -->
<td>
{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y h:i A') }}
</td>

@php
$disp_status = 'In Active';
$actTitle = 'Click to activate';
$mode = 1;
$btnColr = 'btn-hold';

if(isset($row->status) && $row->status == '1'){
$disp_status = 'Active';
$mode = 0;
$btnColr = 'btn-live';
$actTitle = 'Click to deactivate';
}
@endphp

<td>
<a data-csrf_token="{{ csrf_token() }}"
data-original-title="{{ $actTitle }}"
class="stsconfirm"
href="javascript:void(0);"
data-row_id="{{ $row->id }}"
data-act_url="{{ route('admin.user_status') }}"
data-stsmode="{{ $mode }}">

<button type="button" class="btn {{ $btnColr }} px-5">
{{ $disp_status }}
</button>

</a>
</td>

<td style="width:20%;">

<a href="{{ route('admin.user_view_form', $row->id) }}" title="View">
<span class="fa-stack">
<i class="fa fa-eye fa-stack-1x fa-inverse" style="color:blue !important;"></i>
</span>
</a>

<a href="{{ route('admin.user_edit_form', $row->id) }}" title="Edit">
<span class="fa-stack">
<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
</span>
</a>

<a href="javascript:void(0);" title="Delete"
class="table-link danger delconfirm"
data-row_id="{{ $row->id }}"
data-act_url="{{ route('admin.user_delete') }}"
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
searchPlaceholder:"Search users...",
search:""
},

columnDefs:[
{
orderable:true,
targets:[0,3]
}
]

});

});

</script>

@endsection