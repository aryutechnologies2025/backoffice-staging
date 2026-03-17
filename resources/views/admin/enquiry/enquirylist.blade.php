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

    .enquiry {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        color: #282833;
        font-size: 13px;
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


    .action-btns{
    display:flex;
    align-items:center;
    gap:6px;
    white-space:nowrap;
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

    /* .table-responsive {
    overflow-x: auto;
}

#cityTable th,
#cityTable td {
    white-space: nowrap;
} */

    @media (max-width: 768px) {
        .sticky-btn {
            top: 20px;
        }
    }
</style>

<div class="row body-sec py-3 px-5 justify-content-around" id="watch">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold ">{{ $title }}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a class="enquiry" href="">Booking</a></b>
    </div>

       @php
    $permissions = session('permissions', []);
    @endphp
    <!-- Success Message -->
    <div class="mt-2 mb-2 col-lg-12">
        <div class="d-flex justify-content-end">
            @if(\App\Helpers\PermissionHelper::has($permissions, 'booking', 'create'))
            <a href="{{ route('admin.enquiry_add_form') }}">
                <button class="btn-add px-4" type="button">Add Booking</button>
            </a>
            @endif
        </div>
    </div>

</div>

<div class="row body-sec px-5">
    <form method="get" action="{{ route('admin.enquiry_list') }}">
        <div class="row">
            <div class="col-md-2 mb-3">
                <label for="from_date" class="form-label">From Date</label>
                <input type="date" name="from_date" id="from_date" class="form-control"
                    value="{{ request('from_date') }}" placeholder="From Date">
            </div>
            <div class="col-md-2 mb-3">
                <label for="to_date" class="form-label">To Date</label>
                <input type="date" name="to_date" id="to_date" class="form-control"
                    value="{{ request('to_date') }}" placeholder="To Date">
            </div>
            <div class="col-md-3 mb-3 mt-3">
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
        <!-- <div class="table-sec rounded-bottom-4 mb-5"> -->

        <div class="table-sec rounded-bottom-4 mb-5 table-responsive">
            <!-- Button to Download Excel -->
            <button id="downloadExcel" class="btn btn-success mb-3">Download List</button>

            <table id="cityTable" class="table table-bordered pt-2 " style="width: 100%;">
                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-start"><span>S.No</span></th>
                        <th class="text-start"><span>Name</span></th>
                        <!-- <th class="text-start"><span>Email</span></th> -->
                        <th class="text-start"><span>Phone</span></th>
                        <th class="text-start"><span>Budget</span></th>
                        <th class="text-start"><span>Program Name</span></th>
                        <th class="text-start"><span>Date</span></th>
                        <th class="text-start"><span>Created By</span></th>
                        <!-- <th class="text-start"><span>Trip Date</span></th> -->
                        <!-- <th class="text-start"><span>Refered By</span></th> -->
                        <th class="text-start"><span>Mail Processing</span></th>
                        <th class="text-start"><span>Action</span></th>
                    </tr>
                </thead>
                <tbody>
                    @if ($enquiry_dts->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">No records</td>
                    </tr>
                    @else
                    @foreach ($enquiry_dts as $row)
                    <tr>
                        <td class="text-start">{{ $loop->iteration }}</td>
                        <td class="text-start">{{ $row->name }}</td>
                        <!-- <td class="text-start">{{ $row->email }}</td> -->
                        <td class="text-start">{{ $row->phone }}</td>
                        <td class="text-start">{{ $row->pricing }}</td>
                        <td class="text-start">{{ $row->program_title ?? 'null' }}</td>
                        <td class="text-start">{{ $row->travel_date ?? 'null' }}</td>
                        <td class="text-start">{{ auth('admin')->user()->email ?? 'admin' }}</td>
                        <!-- <td class="text-start"> {{ $row->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i:s A') }}</td> -->
                        {{-- <td class="text-start">
                            <select name="status"
                                class="form-select statuschange"
                                data-id="{{ $row->id }}"
                        data-current="{{ $row->status }}">
                        <option value="pending" {{ $row->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $row->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ $row->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="rejected" {{ $row->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        </td> --}}

                        <!-- <td class="text-start">{{ $row->reference_id ?? '-' }}</td> -->


                        <td class="text-start">
                            <select name="email_template" class="form-select mailtemplate"
                                data-id="{{ $row->id }}"
                                data-current-template="{{ $row->email_template ?? '' }}">
                                <option value="" disabled selected>Select a mail template</option>
                                <option value="send_booking_process"
                                    {{ ($row->email_template ?? '') == 'send_booking_process' ? 'selected' : '' }}>
                                    Send booking process</option>
                                <option value="advance_payment_completed"
                                    {{ ($row->email_template ?? '') == 'advance_payment_completed' ? 'selected' : '' }}>
                                    Advance payment completed</option>
                                <option value="final_payment_completed"
                                    {{ ($row->email_template ?? '') == 'final_payment_completed' ? 'selected' : '' }}>
                                    Final payment completed</option>
                                <option value="trip_completed"
                                    {{ ($row->email_template ?? '') == 'trip_completed' ? 'selected' : '' }}>
                                    Trip completed</option>
                                <option value="trip_cancelled"
                                    {{ ($row->email_template ?? '') == 'trip_cancelled' ? 'selected' : '' }}>
                                    Trip cancelled</option>
                            </select>
                        </td>

                        <!-- <td class="text-start d-flex gap-1">
                            <a class="btn view-btn" title="View"
                                href="{{ route('admin.enquiry_view', $row->id) }}">
                                <i class="bi bi-eye-fill" style="color:#000 !important;"></i>
                            </a>
                            <a href="{{ route('admin.enquiry.enquiryfollowups', $row->id) }}" title="List"
                                class="btn "><i class="bi bi-list-check"
                                    style="color:blue !important;"></i>
                                </a>
                            <a href="javascript:void(0);" class="table-link danger delconfirm" title="Delete"
                                data-row_id="{{ $row->id }}"
                                data-act_url="{{ route('admin.enquiry_delete') }}"
                                data-csrf_token="{{ csrf_token() }}">
                                <span class="fa-stack">
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"
                                        style="color:red !important;"></i>
                                </span>     
                            </a>
                        </td> -->


                        <!-- <td class="text-start">
                            <div class="action-btns">

                                <a class="btn view-btn" title="View"
                                    href="{{ route('admin.enquiry_view', $row->id) }}"
                                    data-name="{{ $row->name }}"
                                    data-email="{{ $row->email }}"
                                    data-phone="{{ $row->phone }}"
                                    data-location="{{ $row->location }}"
                                    data-days="{{ $row->days }}"
                                    data-child_count="{{ $row->child_count }}"
                                    data-travel_destination="{{ $row->travel_destination }}"
                                    data-pricing="{{ $row->pricing }}"
                                    data-cab_need="{{ $row->cab_need }}"
                                    data-total_count="{{ $row->total_count }}"
                                    data-male_count="{{ $row->male_count }}"
                                    data-female_count="{{ $row->female_count }}"
                                    data-travel_date="{{ $row->travel_date }}"
                                    data-rooms_count="{{ $row->rooms_count }}"
                                    data-comments="{{ $row->comments }}"
                                    data-date="{{ $row->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i:s A') }}">
                                    <i class="bi bi-eye-fill" style="color:#000 !important;"></i>
                                </a>

                                <a href="{{ route('admin.enquiry.enquiryfollowups', $row->id) }}"
                                    title="List" class="btn">
                                    <i class="bi bi-list-check" style="color:blue !important;"></i>
                                </a>

                                <a href="javascript:void(0);"
                                    class="table-link danger delconfirm"
                                    title="Delete"
                                    data-row_id="{{ $row->id }}"
                                    data-act_url="{{ route('admin.enquiry_delete') }}"
                                    data-csrf_token="{{ csrf_token() }}">

                                    <span class="fa-stack">
                                        <i class="fa fa-trash-o fa-stack-1x fa-inverse"
                                            style="color:red !important;"></i>
                                    </span>
                                </a>
                            </div>
                        </td> -->


                        <td class="text-start">
<div class="action-btns d-flex align-items-center gap-2">

<a class="btn view-btn" title="View"
href="{{ route('admin.enquiry_view', $row->id) }}">
<i class="bi bi-eye-fill" style="color:#000 !important;"></i>
</a>

<a href="{{ route('admin.enquiry.enquiryfollowups', $row->id) }}"
title="List" class="btn">
<i class="bi bi-list-check" style="color:blue !important;"></i>
</a>

<a href="javascript:void(0);" 
class="table-link danger delconfirm"
title="Delete"
data-row_id="{{ $row->id }}"
data-act_url="{{ route('admin.enquiry_delete') }}"
data-csrf_token="{{ csrf_token() }}">

<i class="fa fa-trash" style="color:red;"></i>

</a>

</div>
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
<div class="modal fade custom-message-modal" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel"
    aria-hidden="true">
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

<button class="sticky-btn" id="myBtn"><a href="#watch"><i
            class="bi bi-caret-up-square text-white"></i></button>
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


        $('.statuschange').on('change', function() {
            let select = $(this);
            let newStatus = select.val();
            let enquiryId = select.data('id');
            let oldStatus = select.data('current');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to change this status?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, change it!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ✅ Send AJAX request
                    $.ajax({
                        url: "{{ route('admin.followupstatuschange') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            enquiry_id: enquiryId,
                            status: newStatus
                        },
                        success: function(response) {
                            if (response.status == 1) {
                                Swal.fire('Updated!', response.response, 'success');
                                // update the current status
                                select.data('current', newStatus);
                            } else {
                                Swal.fire('Error!', response.response, 'error');
                                // reset dropdown to old value
                                select.val(oldStatus);
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                            // reset dropdown to old value
                            select.val(oldStatus);
                        }
                    });
                } else {
                    // ❌ Cancelled → reset dropdown
                    select.val(oldStatus);
                }
            });
        });

        $('.mailtemplate').on('change', function() {
            let select = $(this);
            let newStatus = select.val();
            let enquiryId = select.data('id');
            let oldStatus = select.data('current');

            // Prevent action if no value selected
            if (!newStatus) {
                return;
            }

            // Prevent action if same as current status
            if (newStatus === oldStatus) {
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to send this mail template?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, send it!',
                cancelButtonText: 'No, cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    select.prop('disabled', true);

                    // ✅ Send AJAX request
                    $.ajax({
                        url: "{{ route('admin.mailtemplate') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            enquiry_id: enquiryId,
                            status: newStatus
                        },
                        success: function(response) {
                            if (response.status == 1) {
                                Swal.fire('Sent!', response.response, 'success');
                                // update the current status
                                select.data('current', newStatus);
                                // Optional: Disable dropdown after successful send
                                // select.prop('disabled', true);
                            } else {
                                Swal.fire('Error!', response.response, 'error');
                                // reset dropdown to old value
                                select.val(oldStatus);
                            }
                        },
                        error: function(xhr, status, error) {
                            let errorMessage = 'Something went wrong.';
                            if (xhr.responseJSON && xhr.responseJSON.response) {
                                errorMessage = xhr.responseJSON.response;
                            }
                            Swal.fire('Error!', errorMessage, 'error');
                            // reset dropdown to old value
                            select.val(oldStatus);
                        },
                        complete: function() {
                            // Re-enable dropdown
                            select.prop('disabled', false);
                        }
                    });
                } else {
                    // ❌ Cancelled → reset dropdown to old value
                    select.val(oldStatus);
                }
            });
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
        // $('#downloadExcel').on('click', function() {
        //     // First, capture the main table data
        //     var wb = XLSX.utils.table_to_book(document.getElementById('cityTable'), {
        //         sheet: "Enquiries"
        //     });

        //     // Now, capture modal data for all rows
        //     var modalData = [
        //         ["Name", "Email", "Phone", "Location", "Days", "Travel Destination", "Budget", "Cab Need", "Total Count", "Male Count", "Female Count", "Travel Date", "Rooms Count", "Comments", "Date & Time"]
        //     ];

        //     // Loop through all rows to get their modal data
        //     $('#cityTable tbody tr').each(function() {
        //         var row = $(this);
        //         var modalRow = [
        //             row.find('.view-btn').data('name'),
        //             row.find('.view-btn').data('email'),
        //             row.find('.view-btn').data('phone'),
        //             row.find('.view-btn').data('location'),
        //             row.find('.view-btn').data('days'),
        //             row.find('.view-btn').data('child_count'),
        //             row.find('.view-btn').data('travel_destination'),
        //             row.find('.view-btn').data('pricing'),
        //             row.find('.view-btn').data('cab_need'),
        //             row.find('.view-btn').data('total_count'),
        //             row.find('.view-btn').data('male_count'),
        //             row.find('.view-btn').data('female_count'),
        //             row.find('.view-btn').data('travel_date'),
        //             row.find('.view-btn').data('rooms_count'),
        //             row.find('.view-btn').data('comments'),
        //             row.find('.view-btn').data('date')
        //         ];
        //         modalData.push(modalRow);
        //     });

        //     // Create a new sheet for modal data and append it to the workbook
        //     var ws = XLSX.utils.aoa_to_sheet(modalData);
        //     XLSX.utils.book_append_sheet(wb, ws, "Modal Data");

        //     // Trigger Excel file download
        //     XLSX.writeFile(wb, 'Enquiries_Data_With_All_Modal.xlsx');
        // });

        $('#downloadExcel').on('click', function() {
            // Get the DataTable instance
            var table = $('#cityTable').DataTable();

            // Get the current search value from DataTables
            const searchValue = table.search();

            // Get date filter values from your date inputs
            const fromDate = $('#from_date').val() || '';
            const toDate = $('#to_date').val() || '';

            const searchParams = {
                search: searchValue || '',
                from_date: fromDate,
                to_date: toDate,
                // Add any other custom filters you might have
                // status: $('#statusFilter').val() || '',
            };

            // Show loading state
            $(this).html('<i class="fas fa-spinner fa-spin"></i> Downloading...').prop('disabled', true);

            $.ajax({
                url: '/enquiry/download-all',
                method: 'GET',
                data: searchParams,
                success: function(allData) {
                    // Reset button state
                    $('#downloadExcel').html('Download List').prop('disabled', false);

                    if (!allData || allData.length === 0) {
                        alert('No data found for the current filters!');
                        return;
                    }

                    var wb = XLSX.utils.book_new();
                    var modalData = [
                        ["S.No", "Name", "Email", "Phone", "Budget", "Program Name", "Trip Date"]
                    ];

                    allData.forEach(function(d, index) {
                        modalData.push([
                            index + 1,
                            d.name || '',
                            d.email || '',
                            d.phone || '',
                            d.pricing || '',
                            d.program_title || '',
                            d.travel_date || ''
                        ]);
                    });

                    var ws = XLSX.utils.aoa_to_sheet(modalData);
                    XLSX.utils.book_append_sheet(wb, ws, "Filtered Enquiries");

                    // Add filename with date range if exists
                    const timestamp = new Date().toISOString().slice(0, 10);
                    let fileName = `Enquiries_${timestamp}`;

                    if (fromDate && toDate) {
                        fileName += `_${fromDate}_to_${toDate}`;
                    } else if (searchValue) {
                        fileName += `_search_${searchValue}`;
                    }

                    fileName += '.xlsx';

                    XLSX.writeFile(wb, fileName);
                },
                error: function(xhr, status, error) {
                    $('#downloadExcel').html('Download List').prop('disabled', false);
                    alert('Error fetching data from server: ' + error);
                }
            });
        });

    });
</script>
@endsection