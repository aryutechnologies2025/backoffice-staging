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

<div class="row body-sec py-3 px-5 justify-content-around" id="watch">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a class="enquiry" href="/home-enquiry">Enquiry</a></b>
    </div>
    <div class="mt-2 mb-2 col-lg-12">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.home_enquiry_add_form') }}">
                <button class="btn-add px-4" type="button">Add Enquiry Form</button>
            </a>
        </div>
    </div>

</div>


<div class="row body-sec px-5">
    <div class="bg-white pt-3 col-lg-12">
        <div class="table-sec rounded-bottom-4 mb-5">
            <button id="downloadExcel" class="btn btn-success mb-3">Download List</button>

            <table id="cityTable" class="table table-bordered pt-2">
                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-start">S.No</th>
                        <th class="text-start">Name</th>
                        <th class="text-start">Email</th>
                        <th class="text-start">Phone</th>
                        <th class="text-start">Next FollowUp</th>
                        <th class="text-start">Date</th>
                        <th class="text-start">Travel Date</th>
                        <th class="text-start w-25">Status</th>
                        <th class="text-start">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($enquiry_dts->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center">No records</td>
                    </tr>
                    @else
                    @foreach ($enquiry_dts as $row)
                    <tr>
                        <td class="text-start">{{ $loop->iteration }}</td>
                        <td class="text-start">{{ $row->name }}</td>
                        <td class="text-start">{{ $row->email }}</td>
                        <td class="text-start">{{ $row->phone }}</td>
                        @if($row->followUps->isNotEmpty())
                        <td class="text-start">{{ $row->followUps->last()->next_follow_up_date }}</td>
                        @else
                        <td class="text-start">N/A</td>
                        @endif
                        <td class="text-start">{{ \App\Helpers\DateHelper::formatDate($row->created_at) }}</td>
                        <td class="text-start">{{ \App\Helpers\DateHelper::formatDate($row->travel_date) }}</td>
                        <td class="text-start">
                            <select
                                class="form-select followUpStatus"
                                data-enquiry-id="{{ $row->id }}"
                                data-previous-value="{{ $row->followup }}"
                                onchange="handleFollowUpChange(this)">
                                <option value="unfollowup" {{ $row->followup === 'unfollowup' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="followup" {{ $row->followup === 'followup' ? 'selected' : '' }}>
                                    Booking Confirm
                                </option>
                                <option value="interested" {{ $row->followup === 'interested' ? 'selected' : '' }}>
                                    Interested
                                </option>
                                <option value="prospect" {{ $row->followup === 'prospect' ? 'selected' : '' }}>
                                    Prospect
                                </option>
                            </select>



                        </td>
                        <td class="text-start d-flex gap-1">
                            <a class="btn view-btn" title="View" href="{{ route('admin.home_enquiry_view', $row->id) }}">
                                <i class="bi bi-eye-fill" style="color:#000 !important;"></i>
                            </a>
                            <a href="{{ route('admin.enquiry.followups', $row->id) }}" title="List" class="btn"><i class="bi bi-list-check" style="color:blue !important;"></i></a>
                            <a href="javascript:void(0);" class="table-link danger delconfirm" title="Delete" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.home_enquiry_delete') }}" data-csrf_token="{{ csrf_token() }}">
                                <span class="fa-stack">
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
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
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
                <p><strong>Travel Destination:</strong> <span id="modalTravelDestination"></span></p>
                <p><strong>Budget Per Head:</strong> <span id="modalBudgetPerHead"></span></p>
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
<button class="sticky-btn" id="myBtn"><a href="#watch"><i class="bi bi-caret-up-square text-white"></i></button>
@endsection

@section('scripts')
<!-- Add jQuery if not already included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Add SheetJS (XLSX) library for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
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

    function handleFollowUpChange(selectElement) {
        let enquiryId = $(selectElement).data('enquiry-id');
        let newStatus = $(selectElement).val();
        let previousStatus = $(selectElement).data('previous-value');

        // Target the container where the message should appear
        let parentElement = $(selectElement).parent();

        // Remove existing messages (if any)
        parentElement.find('.unfollowup-message').remove();

        // Show message if "Unfollow-Up" is selected
        if (newStatus === 'unfollowup') {
            parentElement.append(
                `<p class="unfollowup-message" style="color: red; margin-top: 5px;">
                    Please note that you have chosen to unfollow this enquiry.
                </p>`
            );
        }

        // SweetAlert confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to change the follow-up status to "${newStatus}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'No, cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                // Make a POST request if user confirms
                fetch(`/mark-followup/${enquiryId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            followup: newStatus
                        }),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            // Show success and reload the page after user closes the alert
                            Swal.fire('Updated!', 'The follow-up status has been updated.', 'success')
                                .then(() => {
                                    location.reload();
                                });

                            // Save the current status as the new previous status
                            $(selectElement).data('previous-value', newStatus);
                        } else {
                            Swal.fire('Error', 'Failed to update the follow-up status.', 'error');
                            // Restore the previous option
                            $(selectElement).val(previousStatus);
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'An unexpected error occurred.', 'error');
                        // Restore the previous option
                        $(selectElement).val(previousStatus);
                    });
            } else {
                // Revert to the previous status if canceled
                $(selectElement).val(previousStatus);
            }
        });
    }

    $(document).ready(function() {
        $('#cityTable').DataTable();

        // Save the initial value for each dropdown on page load
        $('.followUpStatus').each(function() {
            let initialValue = $(this).val();
            $(this).data('previous-value', initialValue); // Store initial value
        });

        // Populate modal
        $('.view-btn').on('click', function() {
            $('#modalName').text($(this).data('name'));
            $('#modalEmail').text($(this).data('email'));
            $('#modalPhone').text($(this).data('phone'));
            $('#modalLocation').text($(this).data('location'));
            $('#modalDays').text($(this).data('days'));
            $('#modalTravelDestination').text($(this).data('travel_destination'));
            $('#modalBudgetPerHead').text($(this).data('budget_per_head'));
            $('#modalCabNeed').text($(this).data('cab_need'));
            $('#modalTotalCount').text($(this).data('total_count'));
            $('#modalMaleCount').text($(this).data('male_count'));
            $('#modalFemaleCount').text($(this).data('female_count'));
            $('#modalTravelDate').text($(this).data('travel_date'));
            $('#modalRoomsCount').text($(this).data('rooms_count'));
            $('#modalComments').text($(this).data('comments'));
            $('#modalDate').text($(this).data('date'));
        });

        // Move the downloadExcel click handler inside document ready
        // $('#downloadExcel').on('click', function() {
        //     let $table = $('#cityTable').clone();
        //     $table.find('tr').each(function() {
        //         $(this).find('th:last-child, td:last-child').remove();
        //     });
        //     let tempDiv = $('<div>').append($table);
        //     const wb = XLSX.utils.table_to_book(tempDiv.find('table')[0], {
        //         sheet: "Enquiries"
        //     });
        //     XLSX.writeFile(wb, 'Enquiries_Data.xlsx');
        // });

          $('#downloadExcel').on('click', function() {
            // Get the DataTable instance
            var table = $('#cityTable').DataTable();

            // Get the current search value from DataTables
            const searchValue = table.search();

            // Get other filter values if you have any
            const searchParams = {
                search: searchValue || '',
            };

            // Show loading state
            var $downloadBtn = $(this);
            var originalText = $downloadBtn.html();
            $downloadBtn.html('<i class="fas fa-spinner fa-spin"></i> Downloading...').prop('disabled', true);

            $.ajax({
                url: '{{ route('admin.download-enquiry-all') }}',
                method: 'GET',
                data: searchParams,
                success: function(allData) {
                    // Reset button state
                    $downloadBtn.html(originalText).prop('disabled', false);

                    if (!allData || allData.length === 0) {
                        alert('No data found for the current search!');
                        return;
                    }

                    // Create workbook
                    var wb = XLSX.utils.book_new();

                    // Prepare data for Excel
                    var excelData = [
                        ["S.No", "Name", "Email", "Phone", "Next FollowUp", "Date", "Status", "Location", "Days", "Travel Destination", "Budget Per Head", "Cab Need", "Total Count", "Male Count", "Female Count", "Travel Date", "Rooms Count", "Comments"]
                    ];

                    // Add all data to Excel
                    allData.forEach(function(item, index) {
                        var nextFollowUp = 'N/A';
                        if (item.follow_ups && item.follow_ups.length > 0) {
                            // Get the last follow up date (already ordered by desc)
                            var lastFollowUp = item.follow_ups[0]; // First item after ordering by desc
                            nextFollowUp = lastFollowUp.next_follow_up_date || 'N/A';
                        }

                        excelData.push([
                            index + 1,
                            item.name || '',
                            item.email || '',
                            item.phone || '',
                            nextFollowUp,
                            item.created_at ? formatExcelDate(item.created_at) : '',
                            item.followup || '',
                            item.location || '',
                            item.days || '',
                            item.travel_destination || '',
                            item.budget_per_head || '',
                            item.cab_need || '',
                            item.total_count || '',
                            item.male_count || '',
                            item.female_count || '',
                            item.travel_date || '',
                            item.rooms_count || '',
                            item.comments || ''
                        ]);
                    });

                    // Create worksheet and append to workbook
                    var ws = XLSX.utils.aoa_to_sheet(excelData);
                    XLSX.utils.book_append_sheet(wb, ws, "All Enquiries");

                    // Create filename with search term if available
                    var filename = 'All_Enquiries_' + new Date().toISOString().split('T')[0] + '.xlsx';
                    if (searchValue) {
                        filename = 'Enquiries_' + new Date().toISOString().split('T')[0] + '.xlsx';
                    }

                    // Download the file
                    XLSX.writeFile(wb, filename);
                },
                error: function(xhr, status, error) {
                    // Reset button state
                    $downloadBtn.html(originalText).prop('disabled', false);

                    console.error('Error:', error);
                    var errorMessage = 'Error downloading data. Please try again.';

                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }

                    alert(errorMessage);
                }
            });
        });

        // Helper function to format date for Excel
        function formatExcelDate(dateString) {
            if (!dateString) return '';
            var date = new Date(dateString);
            return date.toLocaleDateString('en-IN'); // Indian format DD/MM/YYYY
        }
    });
</script>

@endsection