@extends('layouts.app')
@section('content')
<style>
    a:hover {
        color: red;
    }

    a {
        color: rgb(37, 150, 190);
    }

    .contact {
        color: blue;
    }
    .custom-message-modal{
        width: 100%!important;
    }

</style>

<div>
<div class="row body-sec py-5 px-5 justify-content-around">
    <div class="col-lg-12">
        <b><a href="/dashboard">Dashboard</a> > <a class="contact" href="/contact-us">Contact-Us</a></b>
        <br>
        <br>
        <h3 class="fw-bold">{{$title}}</h3>
    </div>
</div>

    
<div class="row body-sec px-5">
    <div class="col-lg-12">
        <div class="table-sec rounded-bottom-4 mb-5">
            <table id="cityTable" class="table pt-2">
                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-center"><span>S.No</span></th>
                        <th class="text-center"><span>Name</span></th>
                        <th class="text-center"><span>Email</span></th>
                        <th class="text-center"><span>Phone</span></th>
                        <th class="text-center"><span>Time&Date</span></th>
                        <th class="text-center"><span>Message</span></th>
                    </tr>
                </thead>
                <tbody>
                    @if($contact_dts->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">No records</td>
                    </tr>
                    @else
                    @foreach ($contact_dts as $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $row->first_name }} {{ $row->last_name }}</td>
                        <td class="text-center">{{ $row->email }}</td>
                        <td class="text-center">{{ $row->phone }}</td>
                        <td class="text-center">{{ $row->created_at }}</td>
                        <td class="text-center">
                            <button class="btn-add btn-warning view-message-btn" data-message="{{ $row->message }}" data-bs-toggle="modal" data-bs-target="#customMessageModal">
                                View Message
                            </button>
                        </td>
                    </tr>
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
    $(document).ready(function () {
        $('#cityTable').DataTable({
            pageLength: 10,
            lengthChange: true,
            ordering: true,
            searching: true,
            language: {
                emptyTable: 'No records found',
            },
            columnDefs: [
                {
                    orderable: false,
                    targets: [3],
                },
            ],
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
