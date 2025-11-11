@extends('layouts.app')
@Section('content')
<style>
    a:hover {
        color: red;
    }
    a{
        color:rgb(37, 150, 190);
    }
    .user{
        color:blue;
    }
    .custom-message-modal{
        width: 100%!important;
    }
    .btn-add {
        background-color: #f0ad4e;
        color: #f0ad4e;
    }
</style>
<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-6">
    <b><a href="/dashboard" >Dashboards</a> > <a class="user" href="/client_review" >Client Review</a></b>
        <br>
        <br>
        <h3 class="fw-bold">{{$title}}</h3>
    </div>
    <div class="col-lg-6">
        <div class="d-flex justify-content-end">
            <!-- <a href="{{ route('admin.client_review_add_form') }}">
                <button class="btn btn-add px-5" type="button">Add Review</button>
            </a> -->
        </div>
    </div>
</div>

<!-- EVENT LIST -->
<div class="row body-sec px-5">
    <div class="col-lg-12">
        <div class="table-sec rounded-bottom-4 mb-5">
            <table id="cityTable" class="table pt-2">
                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-start"><span> S.No </span></th>
                        <th class="text-start"><span> Client Pic </span></th>
                        <th class="text-start px-18"><span> Client Name </span></th>
                        <th class="text-start"><span> Program Name </span></th>
                        <th class="text-start"><span> Rating </span></th>
                        <th class="text-start"><span>Date&Time</span></th>
                        <th class="text-start"><span> Comment </span></th>
                        <th class="text-start"><span>Action</span></th>
                        <!-- <th class="text-center"><span> Status </span></th>
                        <th class="text-center"><span> Action </span></th> -->
                    </tr>
                </thead>
                <tbody>

                    @if($review_dts->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">No records</td>
                    </tr>
                    @else
                    @foreach ($review_dts as $row)
                    <tr>
                    <td class="text-start">{{ $loop->iteration }}</td>

                    <td class="text-start"><img src="{{ asset($row->user->profile_image ?? 'path/to/default/image.jpg') }}" alt="" style="max-width: 100px; max-height: 100px; object-fit: cover;"></td>
                        <td class="text-start">{{ $row->user->first_name ?? 'N/A' }} {{ $row->user->last_name ?? '' }}</td>
                        <td class="text-start">{{ $row->package->title ?? 'N/A' }}</td>
                        <td class="text-start">{{ $row->rating }}</td>
                        
                        <td class="text-start">{{ $row->created_at }}</td>
                        <td class="text-start">
                            <button class="btn-add  view-message-btn" data-message="{{ $row->comment }}" data-bs-toggle="modal" data-bs-target="#customMessageModal">
                                View Comment
                            </button>
                        </td>
                        <td class="text-start" style="width: 20%;">
                        <a href="javascript:void(0);" class="table-link danger delconfirm" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.review_delete') }}" data-csrf_token="{{ csrf_token() }}">
                                <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </td>
                        
                    @endforeach
                    @endif

                </tbody>
            </table>
          
        </div>
    </div>
</div>
<div class="row body-sec px-5">
    <div class="col-lg-12">
        <div class="table-sec rounded-bottom-4 mb-5">
            <table id="cityTable" class="table pt-2">
                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-start"><span> S.No </span></th>
                        <th class="text-start"><span> Client Pic </span></th>
                        <th class="text-start px-18"><span> Client Name </span></th>
                        <th class="text-start"><span> Program Name </span></th>
                        <th class="text-start"><span> Rating </span></th>
                        <th class="text-start"><span>Date&Time</span></th>
                        <th class="text-start"><span> Comment </span></th>
                        <th class="text-start"><span>Action</span></th>
                        <!-- <th class="text-center"><span> Status </span></th>
                        <th class="text-center"><span> Action </span></th> -->
                    </tr>
                </thead>
                <tbody>

                    @if($stay_reviews->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">No records</td>
                    </tr>
                    @else
                    @foreach ($stay_reviews as $row)
                    <tr>
                    <td class="text-start">{{ $loop->iteration }}</td>

                    <td class="text-start"><img src="{{ asset($row->user->profile_image ?? 'path/to/default/image.jpg') }}" alt="" style="max-width: 100px; max-height: 100px; object-fit: cover;"></td>
                        <td class="text-start">{{ $row->user->first_name ?? 'N/A' }} {{ $row->user->last_name ?? '' }}</td>
                        <td class="text-start">{{ $row->package->title ?? 'N/A' }}</td>
                        <td class="text-start">{{ $row->rating }}</td>
                        
                        <td class="text-start">{{ $row->created_at }}</td>
                        <td class="text-start">
                            <button class="btn-add  view-message-btn" data-message="{{ $row->comment }}" data-bs-toggle="modal" data-bs-target="#customMessageModal">
                                View Comment
                            </button>
                        </td>
                        <td class="text-start" style="width: 20%;">
                        <a href="javascript:void(0);" class="table-link danger delconfirm" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.review_delete') }}" data-csrf_token="{{ csrf_token() }}">
                                <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </td>
                        
                    @endforeach
                    @endif

                </tbody>
            </table>
          
        </div>
    </div>
</div>

<!-- Modal placed outside of navbar -->
<div class="custom-message-modal modal fade" id="customMessageModal" tabindex="-1" aria-labelledby="customMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customMessageModalLabel">Message Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="messageContent"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
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
    
    $('.view-message-btn').on('click', function () {
            const message = $(this).data('message');
            $('#messageContent').text(message);
            $('#customMessageModal').modal('show');
        });
    });
</script>
<script>
      $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
      });
    </script>
@endsection