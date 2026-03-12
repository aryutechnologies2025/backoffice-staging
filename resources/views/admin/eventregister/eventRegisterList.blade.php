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

    /* Force modal to be positioned relative to viewport */

    /* Force modal to be positioned relative to viewport */
    .modal {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        z-index: 99999 !important;
        width: 100% !important;
        height: 100% !important;
    }



    /* .modal-dialog {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        margin: 0 !important;
        z-index: 99999 !important;
    } */

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;

    }

    .is-invalid~.invalid-feedback {
        display: block;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Toast styling */
    .toast-success {
        background-color: #28a745 !important;
    }

    .toast-error {
        background-color: #dc3545 !important;
    }
</style>

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a class="city" href="{{ route('admin.programeventslist') }}">Events Registration</a></b>
    </div>
</div>

<!-- EVENT LIST -->
<div>

    <div class="row body-sec px-5">
        <form method="get" action="{{ route('admin.registereventslist') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="pt-3">
                        <select name="event_name" class="form-select">
                            <option value="all">All Events</option>
                            @foreach($eventNames as $id => $name)
                            <option value="{{ $id }}"
                                {{ $event_name == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="pt-3">
                        <input type="date" name="from_date" class="form-control"
                            value="{{ $fromdate }}" placeholder="From Date">
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="pt-3">
                        <input type="date" name="to_date" class="form-control"
                            value="{{ $todate }}" placeholder="To Date">
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="pt-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="row body-sec px-5">
        <div class="bg-white pt-3 col-lg-12">
            <div class="table-sec rounded-bottom-4 mb-5">
                <table id="cityTable" class="table table-bordered pt-2">
                    <thead>
                        <tr class="rounded-top-4">
                            <th class="text-start">S.No</th>
                            <th class="text-start">Event Name</th>
                            <th class="text-start">Name</th>
                            <th class="text-start">Email</th>
                            <th class="text-start">Phone Number</th>
                            <th class="text-start">Date</th>
                            <th class="text-start">Status</th>
                            <th class="text-start">Notes</th>
                            <th class="text-start">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($programdetails as $row)
 <td class="text-start">
{{ $row->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i:s A') }}
</td>
                        <tr>
                            <td class="text-start">{{ $loop->iteration }}</td>
                            <td class="text-start">{{ ucfirst(optional($row->event)->event_name) }}</td>
                            @php
                            $fullName = ucfirst($row->first_name) . ' ' . ucfirst($row->last_name);
                            @endphp
                            <td class="text-start">{{ $fullName }}</td>
                            <td class="text-start">{{ $row->email }}</td>
                            <td class="text-start">{{ $row->phone }}</td>
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

                            <td class="text-start"><a data-toggle="tooltip" data-csrf_token="{{ csrf_token() }}" data-original-title="{{ $actTitle }}" class="stsconfirm" href="javascript:void(0);" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.registereventstatus') }}" data-stsmode="{{ $mode }}"><button type="button" class="btn {{ $btnColr }} px-5">{{ $disp_status }}</button></a></td>
                            <td>
                                <a href="#"
                                   class="btn btn-sm view-notes-btn"
                                   role="button"
                                   data-userid="{{ $row->id }}"
                                   data-bs-toggle="modal"
                                   data-bs-target="#customMessageModal"
                                   aria-label="View message">
                                    <i class="bi bi-journal-text" style="color: {{ !empty(trim($row->notes ?? '')) ? 'red' : '#0d6efd' }};"></i>
                                </a>
                                <!-- <a href="#"
                                    class="btn btn-sm view-message-btn"
                                    role="button"
                                    data-message="{{ htmlspecialchars($row->notes, ENT_QUOTES) }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#customNotesModal"
                                    aria-label="View message">
                                    <i class="fa fa-eye" style="color: #0d6efd;"></i>
                                </a> -->
                            </td>
                            <td class="text-start" style="width: 20%;">

                                {{-- <a href="#"
                                    class="btn btn-sm view-message-btn"
                                    role="button"
                                    data-message="{{ htmlspecialchars($row->notes, ENT_QUOTES) }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#customNotesModal"
                                    aria-label="View message">
                                    <i class="fa fa-eye" style="color: #0d6efd;"></i>
                                </a> --}}
                                <a class="btn view-btn" title="View" href="{{ route('admin.registereventsview', $row->id) }}">
                                <i class="bi bi-eye-fill" style="color:#000 !important;"></i>
                            </a>
                                <a href="javascript:void(0);" class="table-link danger delconfirm" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.registereventdelete') }}" data-csrf_token="{{ csrf_token() }}">
                                    <span class="fa-stack">
                                        <!-- <i class="fa fa-square fa-stack-2x"></i> -->
                                        <i class="fa fa-trash" style="color: red !important;"></i>
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


</div>
@endsection

@section('modal')
<!-- Modal -->
<div class="modal fade" id="customMessageModal" tabindex="-1" aria-labelledby="customMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content shadow-sm ">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="customMessageModalLabel">Notes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="notesForm" method="POST" action="{{ route('admin.updateEventNotes') }}">
                    @csrf
                    <input type="hidden" id="eventuserid" name="eventuserid">
                    <div class="mb-3">
                        <label for="notesTextarea" class="form-label fw-semibold">Notes <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="notesTextarea" name="notes" rows="6"
                            placeholder="Add or edit notes for this event registration..."
                            required></textarea>
                        <div class="invalid-feedback">
                            Please enter some notes.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveNotesBtn">Save Notes</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal placed outside of navbar -->
<div class="custom-message-modal modal fade " id="customNotesModal" tabindex="-1" aria-labelledby="customNotesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="customNotesModalLabel">Message Details</h5>
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

        // Handle view notes button click - populate existing notes
        $('.view-notes-btn').on('click', function() {
            var userId = $(this).data('userid');
            var currentNotes = $(this).closest('tr').find('td:eq(6)').text().trim();

            // Set the user ID in hidden field
            $('#eventuserid').val(userId);

            // Populate textarea with current notes (if any)
            $('#notesTextarea').val(currentNotes === 'No notes' ? '' : currentNotes);

            // Clear validation
            $('#notesTextarea').removeClass('is-invalid');
            $('.invalid-feedback').hide();

            // Focus on textarea
            setTimeout(function() {
                $('#notesTextarea').focus();
            }, 500);
        });

        // Handle save notes button
        $('#saveNotesBtn').on('click', function() {
            var notes = $('#notesTextarea').val().trim();
            var eventUserId = $('#eventuserid').val();

            // Validation
            if (!eventUserId) {
                toastr.error('User ID is required!');
                return;
            }

            if (!notes) {
                $('#notesTextarea').addClass('is-invalid');
                toastr.error('Please enter some notes!');
                return;
            }

            // Remove any previous validation
            $('#notesTextarea').removeClass('is-invalid');

            var formData = {
                _token: $('input[name="_token"]').val(),
                eventuserid: eventUserId,
                notes: notes
            };

            // Show loading state
            var $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Saving...');

            $.ajax({
                url: $('#notesForm').attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#customMessageModal').modal('hide');
                        toastr.success(response.message || 'Notes saved successfully!');
                    } else {
                        toastr.error(response.message || 'Error saving notes!');
                    }
                },
                error: function(xhr) {
                    var errorMessage = 'Error saving notes!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                },
                complete: function() {
                    // Reset button
                    $btn.prop('disabled', false).html('Save Notes');
                }
            });
        });

        $('.view-message-btn').on('click', function() {
            const message = $(this).data('message');
            $('#messageContent').text(message);
            $('#customNotesModal').modal('show');
        });
    });
</script>
@endsection