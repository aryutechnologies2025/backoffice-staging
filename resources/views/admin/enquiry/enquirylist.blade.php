@extends('layouts.app')
@Section('content')
<style>
    a:hover {
        color: red;
    }
    a{
        color:rgb(37, 150, 190);
    }
    .enquiry{
        color:blue;
    }
    .modal{
        width: 100%!important;
       padding-top: 10%!important;
      
        
    }
</style>

<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-12">
        <b><a href="/dashboard" >Dashboard</a> > <a class="enquiry" href="/enquiry" >Enquiry</a></b>
        <br><br>
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
                        <th class="text-center"><span>Action</span></th>
                    </tr>
                </thead>
                <tbody>
                    @if($enquiry_dts->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">No records</td>
                        </tr>
                    @else
                        @foreach ($enquiry_dts as $row)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $row->name }}</td>
                            <td class="text-center">{{ $row->email }}</td>
                            <td class="text-center">{{ $row->phone }}</td>
                            <td class="text-center">
                                <button class="btn btn-primary view-btn" 
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
                                    View
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

<!-- Modal -->
<div class="modal fade" id="viewModal"  tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true" >
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel" >Enquiry Details</h5>
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
                { "orderable": false, "targets": [0, 4] } // Disable ordering on specific columns
            ]
        });

        // Populate modal with data
        $('.view-btn').on('click', function() {
            const comments = $(this).data('comments');
            const date = $(this).data('date');
            $('#modalName').text($(this).data('name'));
            $('#modalEmail').text($(this).data('email'));
            $('#modalPhone').text($(this).data('phone'));
            $('#modalComments').text(comments);
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
            $('#modalDate').text(date);
        });
    });
</script>
@endsection