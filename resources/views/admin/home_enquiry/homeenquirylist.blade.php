@extends('layouts.app')
@section('content')
<style>
    a:hover {
        color: red;
    }

    a {
        color: rgb(37, 150, 190);
    }

    .enquiry {
        color: blue;
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
</style>

<div class="row body-sec py-5 px-5 justify-content-around">
    <div class="col-lg-6">
        <b><a href="/dashboard">Dashboard</a> > <a class="enquiry" href="/enquiry">Enquiry</a></b>
        <br><br>
        <h3 class="fw-bold">{{$title}}</h3>
    </div>
    <div class="col-lg-6">

        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.home_enquiry_add_form') }}">
                <button class="btn btn-add px-5" type="button">Add Booking</button>
            </a>
        </div>
    </div>
</div>

<div class="row body-sec px-5">
    <div class="col-lg-12">
        <div class="table-sec rounded-bottom-4 mb-5">
            <button id="downloadExcel" class="btn btn-success mb-3">Download List</button>

            <table id="cityTable" class="table pt-2">
                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-center">S.No</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Phone</th>
                        <th class="text-center">Next FollowUp</th>
                        <th class="text-center">Time & Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
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
                        @if($row->followUps->isNotEmpty())
                        <td class="text-center">{{ $row->followUps->last()->next_follow_up_date }}</td>
                        @else
                        <td class="text-center">N/A</td>
                        @endif
                        <td class="text-center">{{ $row->created_at }}</td>
                        <td class="text-center">
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



                        </td>
                        <td class="text-center">
                            <button class="btn btn-warning view-btn"
                                data-name="{{ $row->name }}"
                                data-email="{{ $row->email }}"
                                data-phone="{{ $row->phone }}"
                                data-comments="{{ $row->comments }}"
                                data-location="{{ $row->location }}"
                                data-days="{{ $row->days }}"
                                data-travel_destination="{{ $row->travel_destination }}"
                                data-budget_per_head="{{ $row->budget_per_head }}"
                                data-cab_need="{{ $row->cab_need }}"
                                data-total_count="{{ $row->total_count }}"
                                data-male_count="{{$row->male_count}}"
                                data-female_count="{{$row->female_count}}"
                                data-travel_date="{{ $row->travel_date }}"
                                data-rooms_count="{{ $row->rooms_count }}"
                                data-date="{{ $row->created_at->format('d/m/Y h:i:s') }}"
                                data-bs-toggle="modal"
                                data-bs-target="#viewModal">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                            <a href="{{ route('admin.enquiry.followups', $row->id) }}" class="btn btn-primary"><i class="bi bi-list-check"></i></a>
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

@endsection

@section('scripts')
<script>
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
    });


    $('#downloadExcel').on('click', function() {
        const wb = XLSX.utils.table_to_book(document.getElementById('cityTable'), {
            sheet: "Enquiries"
        });
        XLSX.writeFile(wb, 'Enquiries_Data.xlsx');
    });
</script>
@endsection