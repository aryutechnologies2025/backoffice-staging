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

    .contact 
    {
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
        underline: none;
        /* z-index: 999; */
    }
</style>

<div>

    <div class="row body-sec py-3 px-5 justify-content-around" id="watch">
        <div class="text-start col-lg-6 ">
            <h3 class="admin-title fw-bold">{{$title}}</h3>
        </div>
        <div class="text-end col-lg-6 ">
            <b><a href="/dashboard">Dashboard</a> > <a class="contact" href="/contact-us">Contact-Us</a></b>
        </div>
    </div>


    <div class="row body-sec px-5">
        <div class="bg-white pt-3 col-lg-12">
            <div class="table-sec rounded-bottom-4 mb-5">
                <table id="cityTable" class="table table-bordered pt-2">
                    <thead>
                        <tr class="rounded-top-4">
                            <th class="text-start"><span>S.No</span></th>
                            <th class="text-start"><span>Name</span></th>
                            <th class="text-start"><span>Email</span></th>
                            <th class="text-start"><span>Phone</span></th>
                            <th class="text-start"><span>Date</span></th>
                            <th class="text-start"><span>Message</span></th>
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
                            <td class="text-start">{{ $loop->iteration }}</td>
                            <td class="text-start">{{ $row->first_name }} {{ $row->last_name }}</td>
                            <td class="text-start">{{ $row->email }}</td>
                            <td class="text-start">{{ $row->phone }}</td>
                            <td class="text-start">{{ \App\Helpers\DateHelper::formatDate($row->created_at) }}</td>
                            <td class="text-start">
                                <a href="#"
                                    class="btn btn-sm view-message-btn"
                                    title="View"
                                    role="button"
                                    data-message="{{ htmlspecialchars($row->message, ENT_QUOTES) }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#customMessageModal"
                                    aria-label="View message">
                                    <i class="fa fa-eye" style="color: #0d6efd;"></i>
                                </a>

                                <a href="javascript:void(0);" class="table-link danger delconfirm" title="Delete" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.contact_delete') }}" data-csrf_token="{{ csrf_token() }}">
                                    <span class="fa-stack">
                                        <!-- <i class="fa fa-square fa-stack-2x"></i> -->
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

    <!-- Modal placed outside of navbar -->
    <div class="custom-message-modal modal fade " id="customMessageModal" tabindex="-1" aria-labelledby="customMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="customMessageModalLabel">Message Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="" id="messageContent"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<button class="sticky-btn" id="myBtn"><a href="#watch"><i class="bi bi-caret-up-square text-white"></i></button>
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

        $('.view-message-btn').on('click', function() {
            const message = $(this).data('message');
            $('#messageContent').text(message);
            $('#customMessageModal').modal('show');
        });
    });
</script>
<script>
    $('#myModal').on('shown.bs.modal', function() {
        $('#myInput').trigger('focus')
    });
</script>
@endsection