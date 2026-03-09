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
        <b><a href="/dashboard">Dashboard</a> > <a class="enquiry" href="{{ route('admin.stay_home_enquiry_list') }}">Stay Enquiry</a></b>
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
                        <!-- <th class="text-center">Status</th> -->
                        <th class="text-start">Action</th>
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
                        <td class="text-start">{{ $loop->iteration }}</td>
                        <td class="text-start">{{ $row->name }}</td>
                        <td class="text-start">{{ $row->email }}</td>
                        <td class="text-start">{{ $row->phone }}</td>
                        @if($row->followUps->isNotEmpty())
                        <td class="text-start">{{ $row->followUps->last()->next_follow_up_date }}</td>
                        @else
                        <td class="text-start">N/A</td>
                        @endif
                     <td class="text-start">{{ $row->created_at->format('d-m-Y H:i:s') }}</td>
                        <!-- <td class="text-center">
                            <select
                                class="form-select followUpStatus"
                                data-enquiry-id="{{ $row->id }}"
                                data-previous-value="{{ $row->followup }}"
                                onchange="handleFollowUpChange(this)">
                                <option value="unfollowup" {{ $row->followup === 'unfollowup' ? 'selected' : '' }}>
                                    Unfollow-Up
                                </option>
                                <option value="followup" {{ $row->followup === 'followup' ? 'selected' : '' }}>
                                    Follow-Up
                                </option>
                            </select>



                        </td> -->
                        <td class="text-start d-flex gap-1">
                            <a class="btn view-btn" title="View" href="{{ route('admin.stay_enquiry_view', $row->id) }}">
                                <i class="bi bi-eye-fill" style="color:#000 !important;"></i>
                            </a>
                            <!-- <a href="{{ route('admin.enquiry.followups', $row->id) }}" class="btn btn-primary"><i class="bi bi-list-check"></i></a> -->
                            <a href="javascript:void(0);" title="Delete" class="table-link danger delconfirm" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.stay_enquiry_delete') }}" data-csrf_token="{{ csrf_token() }}">
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
                // Make a PUT request if user confirms
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
                            Swal.fire(
                                'Updated!',
                                'The follow-up status has been updated.',
                                'success'
                            );

                            // Save the current status as the new previous status
                            $(selectElement).data('previous-value', newStatus);
                        } else {
                            Swal.fire(
                                'Error',
                                'Failed to update the follow-up status.',
                                'error'
                            );

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

            // Show loading state
            var $downloadBtn = $(this);
            var originalText = $downloadBtn.html();
            $downloadBtn.html('<i class="fas fa-spinner fa-spin"></i> Downloading...').prop('disabled', true);

            // Get ALL data without pagination limits
            $.ajax({
                url: '/home-enquiry/download-stay-all',
                method: 'GET',
                data: {
                    search: searchValue || '',
                    length: -1 // Request all records
                },
                success: function(allData) {
                    // Reset button state
                    $downloadBtn.html(originalText).prop('disabled', false);

                    if (!allData || allData.length === 0) {
                        alert('No data found for the current search!');
                        return;
                    }

                    var wb = XLSX.utils.book_new();
                    var modalData = [
                        ["S.No", "Name", "Email", "Phone", "Comments", "Location", "Stay Title",
                            "Birth Date", "Engagement Date", "No of Days", "Total Count", "Male Count",
                            "Female Count", "Child Count", "Checkin Date", "Checkout Date", "Cab", "Price"
                        ]
                    ];

                    allData.forEach(function(d, index) {
                        modalData.push([
                            index + 1,
                            d.name || '',
                            d.email || '',
                            d.phone || '',
                            d.comments || '',
                            d.location || '',
                            d.stay_title || '',
                            d.birth_date || '',
                            d.engagement_date || '',
                            d.no_of_days || '',
                            d.total_count || '',
                            d.male_count || '',
                            d.female_count || '',
                            d.child_count || '',
                            d.checkin_date || '',
                            d.checkout_date || '',
                            d.cab || '',
                            d.price || ''
                        ]);
                    });

                    var ws = XLSX.utils.aoa_to_sheet(modalData);
                    XLSX.utils.book_append_sheet(wb, ws, "Stay Enquiries");

                    // Create dynamic filename
                    var timestamp = new Date().toISOString().split('T')[0];
                    var filename = searchValue ?
                        `Stay_Enquiries_${searchValue}_${timestamp}.xlsx` :
                        `All_Stay_Enquiries_${timestamp}.xlsx`;

                    XLSX.writeFile(wb, filename);
                },
                error: function(xhr, status, error) {
                    // Reset button state
                    $downloadBtn.html(originalText).prop('disabled', false);

                    console.error('Error:', error);
                    var errorMessage = 'Error fetching data from server';

                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }

                    alert(errorMessage);
                }
            });
        });
    });
</script>
@endsection