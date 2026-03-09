@extends('layouts.app')
@Section('content')

<div class="row body-sec py-5 px-5 justify-content-around">

    <div class="col-lg-6">
        <h3 class="fw-bold"><span class="vr"></span>{{$title}}</h3>
    </div>

    <div class="col-lg-6">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.destination_add_form') }}">
                <button class="btn btn-add px-5" type="button">Add Destinations</button>
            </a>
        </div>
    </div>

</div>


<!-- DESTINATION LIST -->

<div class="row body-sec px-5">

<div class="col-lg-12">

<div class="table-sec rounded-bottom-4 shadow-sm mb-5">

<table class="table user-list">

<thead>

<tr class="rounded-top-4">

<th class="text-center">Destination</th>

<th class="text-center">Place</th>

<th class="text-center">Destination Image</th>

<th class="text-center">Date & Time</th>

<th class="text-center">Status</th>

<th class="text-center">Action</th>

</tr>

</thead>


<tbody>

@if($destination_dts->isEmpty())

<tr>
<td colspan="6" class="text-center">No records</td>
</tr>

@else

@foreach ($destination_dts as $row)

<tr>

<td class="text-center">
{{ $row->destination_name }}
</td>


<td class="text-center">
{{ $row->place }}
</td>


<td class="text-center">

<img
src="{{ asset($row->destination_pic) }}"
alt="Thumbnail"
style="max-width:100px;max-height:100px;object-fit:cover;">

</td>


<!-- TIMESTAMP -->
<td class="text-center">
{{ $row->created_at ? $row->created_at->format('d-m-Y h:i A') : '-' }}
</td>


@php

$disp_status='In Active';
$actTitle='Click to activate';
$mode=1;
$btnColr='btn-hold';

if(isset($row->status) && $row->status=='1'){
$disp_status='Active';
$mode=0;
$btnColr='btn-live';
$actTitle='Click to deactivate';
}

@endphp


<td class="text-center">

<a
data-toggle="tooltip"
title="Edit"
data-csrf_token="{{ csrf_token() }}"
data-original-title="{{ $actTitle }}"
class="stsconfirm"
href="javascript:void(0);"
data-row_id="{{ $row->id }}"
data-act_url="{{ route('admin.destination_status') }}"
data-stsmode="{{ $mode }}">

<button type="button" class="btn {{ $btnColr }} px-5">

{{ $disp_status }}

</button>

</a>

</td>


<td class="text-center" style="width:20%;">


<a href="{{ route('admin.destination_edit_form',$row->id) }}"
class="table-edit-link">

<span class="fa-stack">

<i class="fa fa-square fa-stack-2x"></i>

<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>

</span>

</a>


<a href="javascript:void(0);"
class="table-link danger delconfirm"
title="Delete"
data-row_id="{{ $row->id }}"
data-act_url="{{ route('admin.destination_delete') }}"
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


<!-- PAGINATION -->

<div class="pagination-sec">

<ul class="pagination justify-content-center">


@if ($destination_dts->onFirstPage())

<li class="page-item disabled">
<a class="page-link rounded-circle text-dark fw-bold">
&laquo;
</a>
</li>

@else

<li class="page-item">
<a class="page-link rounded-circle text-dark fw-bold"
href="{{ $destination_dts->previousPageUrl() }}">
&laquo;
</a>
</li>

@endif


@foreach ($destination_dts->links()->elements[0] as $page => $url)

<li class="page-item {{ $page==$destination_dts->currentPage() ? 'active':'' }}">

<a class="page-link rounded-circle text-dark fw-bold px-3 ms-2"
href="{{ $url }}">

{{ $page }}

</a>

</li>

@endforeach


@if ($destination_dts->hasMorePages())

<li class="page-item">
<a class="page-link rounded-circle text-dark fw-bold ms-2"
href="{{ $destination_dts->nextPageUrl() }}">
&raquo;
</a>
</li>

@else

<li class="page-item disabled">
<a class="page-link rounded-circle text-dark fw-bold ms-2">
&raquo;
</a>
</li>

@endif

</ul>

</div>

</div>

</div>

</div>

@endsection