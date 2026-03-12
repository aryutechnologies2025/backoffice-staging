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
        <b><a href="/dashboard">Dashboard</a> > <a class="city border-bottom " href="/permission">Permission</a></b>
    </div>
    <div class="mt-2 mb-2 col-lg-12">
        @php
        $permissions = session('permissions', []);
        @endphp
        <div class="d-flex justify-content-end">
            @if(\App\Helpers\PermissionHelper::has($permissions, 'user_permission', 'create'))

            <a href="{{ route('admin.user_permission_add_form') }}">
                <button class="btn-add px-4" type="button">Add Permission</button>
            </a>
            @endif
        </div>
    </div>

</div>

<!-- EVENT LIST -->
<div class="row body-sec px-5">
    <div class="bg-white pt-3 col-lg-12">
        <div class="table-sec rounded-bottom-4 mb-5">
            <table id="cityTable" class="table table-bordered pt-2">
                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-start"><span>S.No</span></th>
                        <th class="text-start"><span>Role Name</span></th>
                        <th class="text-start"><span>Created Date</span></th>
                        <th class="text-start"><span>Status</span></th>
                        <th class="text-start"><span>Action</span></th>
                    </tr>
                </thead>
                <tbody>
                    @if(empty($users))
                    <tr>
                        <td colspan="5" class="text-center text-danger fw-bold">
                            No Record Found
                        </td>
                    </tr>
                    @else
                    @foreach ($users as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row->role->role_name ?? 'No Role' }}</td>
                        <td>{{ $row->created_date ?? '-' }}</td>
                        @php
                        $disp_status = 'In Active';
                        $actTitle = 'Click to activate';
                        $mode = 1;
                        $btnColr = 'btn-hold';

                        if (isset($row->status) && $row->status == '1') {
                        $disp_status = 'Active';
                        $mode = 0;
                        $btnColr = 'btn-live';
                        $actTitle = 'Click to deactivate';
                        }
                        @endphp
                        <td class="text-start"><a data-toggle="tooltip" data-csrf_token="{{ csrf_token() }}" data-original-title="{{ $actTitle }}" class="stsconfirm" href="javascript:void(0);" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.user_permission_status') }}" data-stsmode="{{ $mode }}"><button type="button" class="btn {{ $btnColr }} px-5">{{ $disp_status }}</button></a></td>
                        <td class="text-start" style="width: 20%;">
                            @if(\App\Helpers\PermissionHelper::has($permissions, 'user_permission', 'edit'))

                            <a href="{{ route('admin.user_permission_edit_form',$row->id) }}" title="Edit" class="table-edit-link">
                                <span class="fa-stack">
                                    <!-- <i class="fa fa-square fa-stack-2x"></i> -->
                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                            @endif
                            @if(\App\Helpers\PermissionHelper::has($permissions, 'user_permission', 'delete'))
                            <a href="javascript:void(0);" class="table-link danger delconfirm" title="Delete" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.user_permission_delete') }}" data-csrf_token="{{ csrf_token() }}">
                                <span class="fa-stack">
                                    <!-- <i class="fa fa-square fa-stack-2x"></i> -->
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse" style="color:red !important;"></i>
                                </span>
                            </a>
                            @endif
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
    $(document).ready(function() {
        $('#cityTable').DataTable({
            "pageLength": 10,
            "lengthChange": true,
            "ordering": true,
            "searching": true,
            "language": {
                "emptyTable": "No records found",
                "searchPlaceholder": "Search cities...", // 👈 Your placeholder text
                "search": "" // 👈 This removes the "Search:" label
            },
            "columnDefs": [{
                "orderable": true,
                "targets": [0, 3]
            }]
        });
    });
</script>
@endsection