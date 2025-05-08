@extends('layouts.app')
@Section('content')
<style>
    a:hover {
        color: red;
    }
    a{
        color:rgb(37, 150, 190);
    }
    .city{
        color:blue;
    }

</style>
<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-6">
    <b><a href="/dashboard" >Dashboard</a> > <a class="city" href="/address" >Address</a></b>
        <br>
        <br>
        <h3 class="fw-bold">{{$title}}</h3>
    </div>
    <div class="col-lg-6">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.address_add_form') }}">
                <button class="btn btn-add px-5" type="button">Add Address</button>
            </a>
        </div>
    </div>
</div>

<!-- EVENT LIST -->
<div class="row body-sec px-5">
    <div class="col-lg-12">
        <div class="table-sec rounded-bottom-4 mb-5">
        <table id="cityTable" class="table  pt-2">
        <thead>
                    <tr class="rounded-top-4">
                        <th class="text-center"><span>S.No</span></th>
                        <th class="text-center"><span> Title </span></th>
                        <th class="text-center"><span> Address </span></th>
                        <th class="text-center"><span> City </span></th>
                        <th class="text-center"><span> State </span></th>
                        <th class="text-center"><span> Country </span></th>
                        <th class="text-center"><span>Pincode</span></th>
                        <th class="text-center"><span>Date&Time</span></th>

                        <th class="text-center"><span>Action</span></th>
                    </tr>
                </thead>
                <tbody>

                   
                    @foreach ($user_dts as $row)
                    <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $row->title }}</td>
                        <td class="text-center">{!! $row->address !!}</td>
                        <td class="text-center">{{ $row->city }}</td>
                        <td class="text-center">{{ $row->state }}</td>
                        <td class="text-center">{{ $row->country }}</td>
                        <td class="text-center">{{ $row->pincode }}</td>
                        <td class="text-center">{{ $row->created_at }}</td>
                        <td class="text-center" style="width: 20%;">
                            <a href="{{ route('admin.address_edit_form',$row->id) }}" class="table-edit-link">
                                <span class="fa-stack">
                                    <!-- <i class="fa fa-square fa-stack-2x"></i> -->
                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                            <a href="javascript:void(0);" class="table-link danger delconfirm" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.address_delete') }}" data-csrf_token="{{ csrf_token() }}">
                                <span class="fa-stack">
                                    <!-- <i class="fa fa-square fa-stack-2x"></i> -->
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse" style="color: red !important;"></i>
                                </span>
                            </a>
                        </td>
                    @endforeach

                    @if($user_dts->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">No records</td>
                    </tr>
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
            },
            "columnDefs": [
                { "orderable": true, "targets": [0, 3] } // Disable ordering on Icon and Action columns
            ]
        });
    });
</script>
@endsection