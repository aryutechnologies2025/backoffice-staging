@extends('layouts.app')
@Section('content')

 <style>
   a:hover {
        color: red;
    }
    a{
        color:rgb(37, 150, 190);
    }
    .city{
        color:blue;
    }
 </style>

<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-6">
    <b><a href="/dashboard" >Dashboard</a> > <a class="city" href="" >Program</a></b>
        <br>
        <br>
        <h3 class="fw-bold">{{$title}}</h3>
    </div>
    <div class="col-lg-6">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.inclusive_package_add_form') }}">
                <button class="btn btn-add px-5" type="button"> Add Program </button>
            </a>
        </div>
    </div>
</div>

<!-- EVENT LIST -->
<div class="row body-sec px-5">
    <div class="col-lg-12">
        <div class="table-sec rounded-bottom-4 mb-5">
        <table id="cityTable" class="table pt-2">
        <thead>
                     <tr class="rounded-top-4">
                        <th class="text-center"><span>S.No</span></th>
            <th class="text-center "><span> Title </span></th>
            <th class="text-center "><span> Image </span></th>
            <th class="text-center "><span> Category </span></th>
            <th class="text-center "><span>Date&Time </span></th>

            <th class="text-center "><i class="bi bi-caret-up-fill activearrow"   ></i>
                <span> Status </span>
                <i class="bi bi-caret-down-fill inactivearrow"></i>

            </th>
            <th class="text-center"><span> Action </span></th>
        </tr>
                </thead>
                <tbody>
                    @if($inclusive_packages->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">No records</td>
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
                    <td class="text-center">{{ $loop->iteration }}</td>

                        <td class="text-center">{{ $row->title }}</td>
                        <td class="text-center"><img src="{{asset($row->cover_img) }}"  alt="{{ $row->alternate_name ?? 'Default Alt Text' }}" style="max-width: 100px; max-height: 100px; object-fit: cover;"></td>

                        <td class="text-center">{{ $formattedCategories }}</td>
                        <td class="text-center">{{ $row->created_at }}</td>
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
                        <td class="text-center"><a data-toggle="tooltip" data-csrf_token="{{ csrf_token() }}" data-original-title="{{ $actTitle }}" class="stsconfirm" href="javascript:void(0);" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.inclusive_package_status') }}" data-stsmode="{{ $mode }}"><button type="button" class="btn {{ $btnColr }} px-5">{{ $disp_status }}</button></a></td>
                        <td class="text-center" style="width: 20%;">
                            <a href="{{ route('admin.inclusive_package_edit_form',$row->id) }}" class="table-edit-link">
                                <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>

                            <a href="javascript:void(0);" class="table-link danger delconfirm" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.inclusive_package_delete') }}" data-csrf_token="{{ csrf_token() }}">
                                <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
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
            },
            "columnDefs": [
                { "orderable": false, "targets": [0, 3] } // Disable ordering on Icon and Action columns
            ]
        });
    });
</script>
@endsection