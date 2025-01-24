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
</style>
<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-6">
    <b><a href="/dashboard" >Dashboard</a> > <a class="user" href="/client_review" >Client Review</a></b>
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
                        <th class="text-center"><span> S.No </span></th>
                        <th class="text-center"><span> Client Pic </span></th>
                        <th class="text-center px-18"><span> Client Name </span></th>
                        <th class="text-center"><span> Program Name </span></th>
                        <th class="text-center"><span> Rating </span></th>
                        <th class='text-center'><span>Date&Time</span></th>
                        <th class="text-center"><span> Comment </span></th>
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
                    <td class="text-center">{{ $loop->iteration }}</td>

                    <td class="text-center"><img src="{{ asset($row->user->profile_image ?? 'path/to/default/image.jpg') }}" alt="{{ $row->user->first_name }}" style="max-width: 100px; max-height: 100px; object-fit: cover;"></td>
                        <td class="text-center">{{ $row->user->first_name }}</td>
                        <td class="text-center">{{ $row->package->title }}</td>
                        <td class="text-center">{{ $row->rating }}</td>
                        
                        <td class="text-center">{{ $row->created_at }}</td>
                        <td class="text-center">
                            <button class="btn-add btn-warning view-message-btn" data-message="{{ $row->comment }}" data-bs-toggle="modal" data-bs-target="#customMessageModal">
                                View Comment
                            </button>
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