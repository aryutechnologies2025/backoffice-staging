@extends('layouts.app')
@section('content')

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

    .city {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        color: #282833;
        font-size: 13px;
    }

    #cityTable thead th {
        color: #000 !important;
    }

    .th {
        color: black
    }
</style>

<div class="row body-sec py-3 px-5 justify-content-around">

    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>

    <div class="text-end col-lg-6 ">
        <b>
            <a href="/dashboard">Dashboard</a> >
            <a class="city border-bottom " href="/role">Admin User</a>
        </b>
    </div>

    <div class="mt-2 mb-2 col-lg-12">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.admin_user_add_form') }}">
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
                        <th class="text-start">Profile Image</th>
                        <th class="text-start">First Name</th>
                        <th class="text-start">Last Name</th>
                        <th class="text-start">Email</th>
                        <th class="text-start">Phone</th>
                        <th class="text-start">Created By</th>
                        <th class="text-start">Created Date</th>
                        <th class="text-start">Status</th>
                        <th class="text-start">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($admins as $row)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>
                            @if($row->profile_pic && file_exists(public_path($row->profile_pic)))

                            <img src="{{ asset($row->profile_pic) }}"
                                style="max-width:70px;max-height:70px;object-fit:cover;">

                            @else

                            <img src="{{ asset('uploads/user_profile/default.png') }}"
                                style="max-width:70px;max-height:70px;object-fit:cover;">

                            @endif
                        </td>

                        <td>{{ ucfirst($row->first_name) }}</td>

                        <td>{{ ucfirst($row->last_name) }}</td>

                        <td>{{ $row->email }}</td>

                        <td>{{ $row->phone }}</td>

                        <!-- CREATED BY ADMIN -->
                        <td>
                            {{ $row->created_by ?? '-' }}
                        </td>

                        <!-- CREATED DATE + TIME -->
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
                            <a
                                data-toggle="tooltip"
                                data-csrf_token="{{ csrf_token() }}"
                                data-original-title="{{ $actTitle }}"
                                class="stsconfirm"
                                href="javascript:void(0);"
                                data-row_id="{{ $row->id }}"
                                data-act_url="{{ route('admin.admin_user_status') }}"
                                data-stsmode="{{ $mode }}">

                                <button type="button" class="btn {{ $btnColr }} px-4">
                                    {{ $disp_status }}
                                </button>

                            </a>
                        </td>

                        <td style="width:20%;">

                            <a href="{{ route('admin.admin_user_edit_form',$row->id) }}"
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
                                data-act_url="{{ route('admin.admin_user_delete') }}"
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