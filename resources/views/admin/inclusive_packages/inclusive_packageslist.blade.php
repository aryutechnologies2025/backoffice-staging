@extends('layouts.app')
@Section('content')

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


    .city {
        font-family: 'Poppins', sans-serif;
        font-weight:600;
        color:#282833;
        font-size:13px;
    }
</style>

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
          <b><a href="/dashboard">Dashboard</a> > <a class="city" href="">Program</a></b>
    </div>
    <div class="mt-2 mb-2 col-lg-12">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.inclusive_package_add_form') }}">
                <button class="btn btn-add px-4" type="button"> Add Program</button>
            </a>
        </div>
    </div>

</div>  

<!-- EVENT LIST -->
<div class="row body-sec px-5">
    <div class="bg-white pt-3 col-lg-12">
        <div class="table-sec rounded-bottom-4 mb-5">
            <table id="cityTable" class="table table-bordered pt-2">
                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-start"><span>S.No</span></th>
                        <th class="text-start"><span> Title </span></th>
                        <th class="text-start"><span> Image </span></th>
                        <th class="text-start"><span> Category </span></th>
                        <th class="text-start"><span>Date </span></th>
                        <th class="text-start"><span> Package Duplicate </span></th>
                        <th class="text-start">
                            <span> Status </span>
                        </th>
                        <th class="text-start"><span> Action </span></th>
                    </tr>
                </thead>
                <tbody>
                    @if($inclusive_packages->isEmpty())
                    <tr>
                        <td colspan="9" class="text-start">No records</td>
                    </tr>
                    @else
                    @foreach ($inclusive_packages as $row)
                    @php
                    // Decode the JSON if it's a string, otherwise use the array directly
                    $eventsPackageImages = is_array($row->events_package_images)
                    ? $row->events_package_images
                    : json_decode($row->events_package_images, true);

                    // Get the first image path
                    $firstImage = isset($eventsPackageImages[0]) ? $eventsPackageImages[0] : null;

                    // Process category
                    $categories = is_array($row->category)
                    ? $row->category
                    : json_decode($row->category, true);

                    // Format categories for display
                    $formattedCategories = is_array($categories)
                    ? implode(', ', array_map(fn($cat) => str_replace('_', ' ', $cat), $categories))
                    : str_replace('_', ' ', $categories);


                    @endphp
                    <tr>
                        <td class="text-start">{{ $loop->iteration }}</td>

                        <td class="text-start">{{ $row->title }}</td>
                        <td class="text-start"><img src="{{ $row->cover_img ? asset($row->cover_img) : asset($settings->footer_logo) }}" alt="{{ $row->alternate_name ?? 'Default Alt Text' }}" style="max-width: 100px; max-height: 100px; object-fit: cover;"></td>

                        <td class="text-start">{{ $formattedCategories }}</td>
                        <td class="text-start">{{ \App\Helpers\DateHelper::formatDate($row->created_at) }}</td>
                        <td class="text-start">
                            <button type="button" class="btn  text-dark duplicate_package" data-package_id="{{ $row->id }}">
                                <i class="fa fa-copy" style="color:blue !important"></i> Duplicate
                            </button>
                        </td>
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
                        <td class="text-start"><a data-toggle="tooltip" data-csrf_token="{{ csrf_token() }}" data-original-title="{{ $actTitle }}" class="stsconfirm" href="javascript:void(0);" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.inclusive_package_status') }}" data-stsmode="{{ $mode }}"><button type="button" class="btn {{ $btnColr }} px-5">{{ $disp_status }}</button></a></td>
                        <td class="text-start" style="width: 20%;">
                            <a href="{{ route('admin.inclusive_package_edit_form',$row->id) }}" class="table-edit-link">
                                <span class="fa-stack">
                                    <!-- <i class="fa fa-square fa-stack-2x"></i> -->
                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>

                            <a href="javascript:void(0);" class="table-link danger delconfirm" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.inclusive_package_delete') }}" data-csrf_token="{{ csrf_token() }}">
                                <span class="fa-stack">
                                    <!-- <i class="fa fa-square fa-stack-2x"></i> -->
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse" style="color: red !important;"></i>
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
                "searchPlaceholder": "Search cities...",  // 👈 Your placeholder text
                "search": ""  // 👈 This removes the "Search:" label
            },
            "columnDefs": [
                { "orderable": true, "targets": [0, 3] }
            ]
        });


         $(document).on('click', '.duplicate_package', function() {
            const packageId = $(this).data('package_id');
            const $button = $(this);

            // Show loading state
            $button.html('<i class="fas fa-spinner fa-spin"></i> Duplicating...');
            $.ajax({
                url: "{{ route('admin.ProgramPackage_dupdetails') }}",
                type: 'POST',
                data: {
                    id: packageId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Package duplicated successfully!');
                        // Refresh the table or add the new row
                        // if ($.fn.DataTable.isDataTable('#cityTable')) {
                        //     $('#cityTable').DataTable().ajax.reload(null, false);
                        // }
                        location.reload();
                    } else {
                        toastr.error(response.message || 'Failed to duplicate package');
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred while duplicating the package');
                    console.error('Error:', xhr.responseText);
                }
            });
        });
    });
</script>
@endsection