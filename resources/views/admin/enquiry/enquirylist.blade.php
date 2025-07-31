@extends('layouts.app')
@section('content')
<style>
    a:hover {
        color: rgb(27, 108, 138);
    }

    a {
        color: rgb(37, 150, 190);
    }

    .enquiry {
        color: rgb(27, 108, 138);
    }

    .modal {
        width: 100% !important;
        padding-top: 10% !important;
    }

    .btn {
        border-radius: 6px !important;
        color: #FFF !important;
        font-size: 15px !important;
    }

    .custom-message-modal {
        width: 100% !important;
        background: #29292960;
    }
</style>

<div class="row body-sec py-5 px-5 justify-content-around">
    <div class="col-lg-6">
        <b><a href="/dashboard">Dashboard</a> > <a class="enquiry" href="">Booking</a></b>
        <br><br>
        <h3 class="fw-bold">{{$title}}</h3>
    </div>
    <div class="col-lg-6">

        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.enquiry_add_form') }}">
                <button class="btn btn-add px-5" type="button">Add Booking</button>
            </a>
        </div>
    </div>
</div>

<div class="row body-sec px-5">
    <div class="col-lg-12">
        <div class="table-sec rounded-bottom-4 mb-5">
            <!-- Button to Download Excel -->
            <button id="downloadExcel" class="btn btn-success mb-3">Download List</button>

            <table id="cityTable" class="table pt-2 " style="width: 100%;">
                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-center"><span>S.No</span></th>
                        <th class="text-center"><span>Name</span></th>
                        <th class="text-center"><span>Email</span></th>
                        <th class="text-center"><span>Phone</span></th>
                        <th class="text-center"><span>Budget</span></th>
                        <th class="text-center"><span>Program Name</span></th>
                        <th class="text-center"><span>Refered By</span></th>

                        <th class="text-center"><span>Action</span></th>
                    </tr>
                </thead>
                <tbody>
                    @if($enquiry_dts->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">No records</td>
                    </tr>
                    @else
                    @foreach ($enquiry_dts as $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $row->name }}</td>
                        <td class="text-center">{{ $row->email }}</td>
                        <td class="text-center">{{ $row->phone }}</td>
                        <td class="text-center">{{ $row->pricing }}</td>
                        <td class="text-center">{{ $row->program_title ?? 'null' }}</td>
                        <td class="text-center">{{$row->reference_id ?? '-'}}</td>

                        <td class="text-center d-flex gap-1">
                            <a class="btn btn-warning view-btn" href="{{ route('admin.enquiry_view', $row->id) }}">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="{{ route('admin.enquiry.enquiryfollowups', $row->id) }}" class="btn btn-primary"><i class="bi bi-list-check"></i></a>
                            <a href="javascript:void(0);" class="table-link danger delconfirm" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.enquiry_delete') }}" data-csrf_token="{{ csrf_token() }}">
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

<!-- Modal -->
<div class="modal fade custom-message-modal" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Enquiry Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="modalName"></span></p>
                <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                <p><strong>Phone:</strong> <span id="modalPhone"></span></p>
                <p><strong>Location:</strong> <span id="modalLocation"></span></p>
                <p><strong>Days:</strong> <span id="modalDays"></span></p>
                <p><strong>No.of.child:</strong> <span id="modelChild"></span></p>
                <p><strong>Travel Destination:</strong> <span id="modalTravelDestination"></span></p>
                <p><strong>Budget:</strong> <span id="modalBudgetPerHead"></span></p>
                <p><strong>Cab Need:</strong> <span id="modalCabNeed"></span></p>
                <p><strong>Total Count:</strong> <span id="modalTotalCount"></span></p>
                <p><strong>Male Count:</strong> <span id="modalMaleCount"></span></p>
                <p><strong>Female Count:</strong> <span id="modalFemaleCount"></span></p>
                <p><strong>Travel Date:</strong> <span id="modalTravelDate"></span></p>
                <p><strong>Rooms Count:</strong> <span id="modalRoomsCount"></span></p>
                <p><strong>Comments:</strong> <span id="modalComments"></span></p>
                <p><strong>Date & Time:</strong> <span id="modalDate"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#cityTable').DataTable({
            "pageLength": 10,
            "lengthChange": true,
            "ordering": true,
            "searching": true,
            "language": {
                "emptyTable": "No records found",
            },
            "columnDefs": [{
                    "orderable": true,
                    "targets": [0, 6]
                } // Disable ordering on specific columns
            ]
        });

        // Populate modal with data
        $(document).on('click', '.view-btn', function() {
            // $('.view-btn').on('click', function() {
            $('#modalName').text($(this).data('name'));
            $('#modalEmail').text($(this).data('email'));
            $('#modalPhone').text($(this).data('phone'));
            $('#modalComments').text($(this).data('comments'));
            $('#modalLocation').text($(this).data('location'));
            $('#modalDays').text($(this).data('days'));
            $('#modalTravelDestination').text($(this).data('travel_destination'));
            $('#modalBudgetPerHead').text($(this).data('pricing'));
            $('#modalCabNeed').text($(this).data('cab_need'));
            $('#modalTotalCount').text($(this).data('total_count'));
            $('#modelChild').text($(this).data('child_count'));
            $('#modalMaleCount').text($(this).data('male_count'));
            $('#modalFemaleCount').text($(this).data('female_count'));
            $('#modalTravelDate').text($(this).data('travel_date'));
            $('#modalRoomsCount').text($(this).data('rooms_count'));
            $('#modalDate').text($(this).data('date'));

        });

        // Download Excel
        $('#downloadExcel').on('click', function() {
            // First, capture the main table data
            var wb = XLSX.utils.table_to_book(document.getElementById('cityTable'), {
                sheet: "Enquiries"
            });

            // Now, capture modal data for all rows
            var modalData = [
                ["Name", "Email", "Phone", "Location", "Days", "Travel Destination", "Budget", "Cab Need", "Total Count", "Male Count", "Female Count", "Travel Date", "Rooms Count", "Comments", "Date & Time"]
            ];

            // Loop through all rows to get their modal data
            $('#cityTable tbody tr').each(function() {
                var row = $(this);
                var modalRow = [
                    row.find('.view-btn').data('name'),
                    row.find('.view-btn').data('email'),
                    row.find('.view-btn').data('phone'),
                    row.find('.view-btn').data('location'),
                    row.find('.view-btn').data('days'),
                    row.find('.view-btn').data('child_count'),
                    row.find('.view-btn').data('travel_destination'),
                    row.find('.view-btn').data('pricing'),
                    row.find('.view-btn').data('cab_need'),
                    row.find('.view-btn').data('total_count'),
                    row.find('.view-btn').data('male_count'),
                    row.find('.view-btn').data('female_count'),
                    row.find('.view-btn').data('travel_date'),
                    row.find('.view-btn').data('rooms_count'),
                    row.find('.view-btn').data('comments'),
                    row.find('.view-btn').data('date')
                ];
                modalData.push(modalRow);
            });

            // Create a new sheet for modal data and append it to the workbook
            var ws = XLSX.utils.aoa_to_sheet(modalData);
            XLSX.utils.book_append_sheet(wb, ws, "Modal Data");

            // Trigger Excel file download
            XLSX.writeFile(wb, 'Enquiries_Data_With_All_Modal.xlsx');
        });
    });
</script>
@endsection