@extends('layouts.app')
@section('content')

<style>
    a:hover {
        color: rgb(27, 108, 138);
    }

    a{
        font-family: 'Poppins', sans-serif;
        font-weight:500;
        color:#8B7eff;
        font-size:13px;
    }

    .city{
        font-family: 'Poppins', sans-serif;
        font-weight:600;
        color:#282833;
        font-size:13px;
    }

    #cityTable thead th {
        color: #000 !important;
    }

    .th{
        color:black
    }
</style>


<div class="row body-sec py-3 px-5 justify-content-around">

    <div class="text-start col-lg-6">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>

    <div class="text-end col-lg-6">
        <b>
            <a href="/dashboard">Dashboard</a> >
            <a class="city border-bottom" href="/role">Role</a>
        </b>
    </div>


    <!-- Success Message -->
    @if(session('success'))
    <div class="col-lg-12 mt-2">
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    </div>
    @endif


    <!-- Error Message -->
    @if ($errors->any())
    <div class="col-lg-12 mt-2">
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif


    <div class="mt-2 mb-2 col-lg-12">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.role_add_form') }}">
                <button class="btn-add px-4" type="button">Add Role</button>
            </a>
        </div>
    </div>

</div>


<!-- ROLE LIST TABLE -->

<div class="row body-sec px-5">

    <div class="bg-white pt-3 col-lg-12">

        <div class="table-sec rounded-bottom-4 mb-5">

            <table id="cityTable" class="table table-bordered pt-2">

                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-start">S.No</th>
                        <th class="text-start">Name</th>
                        <th class="text-start">Created Date & Time</th>
                        <th class="text-start">Status</th>
                        <th class="text-start">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($roles as $row)

                    <tr>

                        <td class="text-start">{{ $loop->iteration }}</td>

                        <td class="text-start">{{ $row->role_name }}</td>

                        <!-- FIXED TIMESTAMP -->
                        <td class="text-start">
                            {{ $row->created_at ? $row->created_at->format('d-m-Y h:i A') : '-' }}
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


                        <td class="text-start">

                            <a data-toggle="tooltip"
                               data-csrf_token="{{ csrf_token() }}"
                               data-original-title="{{ $actTitle }}"
                               class="stsconfirm"
                               href="javascript:void(0);"
                               data-row_id="{{ $row->id }}"
                               data-act_url="{{ route('admin.role_status') }}"
                               data-stsmode="{{ $mode }}">

                               <button type="button" class="btn {{ $btnColr }} px-5">
                                   {{ $disp_status }}
                               </button>

                            </a>

                        </td>


                        <td class="text-start" style="width:20%;">

                            <a href="{{ route('admin.role_edit_form',$row->id) }}"
                               title="Edit"
                               class="table-edit-link">

                                <span class="fa-stack">
                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                </span>

                            </a>


                            <a href="javascript:void(0);"
                               class="table-link danger delconfirm"
                               title="Delete"
                               data-row_id="{{ $row->id }}"
                               data-act_url="{{ route('admin.role_delete') }}"
                               data-csrf_token="{{ csrf_token() }}">

                                <span class="fa-stack">
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"
                                       style="color:red !important;"></i>
                                </span>

                            </a>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection


@section('scripts')

<script>

$(document).ready(function() {

    $('#cityTable').DataTable({

        pageLength:10,
        lengthChange:true,
        ordering:false,
        searching:true,

        language:{
            emptyTable:"No records found",
            searchPlaceholder:"Search roles...",
            search:""
        }

    });

});

</script>

@endsection