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
    .flex{
        display: flex;
        align-items: center;
        gap: 5px;
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
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="mb-2">Theme</label>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center" type="button" id="filterThemeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="filterThemeText">Select Themes</span>
                    </button>
                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="filterThemeDropdown" style="max-height: 200px; overflow-y: auto;">
                        @foreach ($themes as $id => $name)
                        <li>
                            <div class="form-check flex">
                                <input class="form-check-input filter-theme box" type="checkbox" id="filter-theme-{{ $id }}" value="{{ $id }}">
                                <label class="form-check-label w-100" for="filter-theme-{{ $id }}">{{ $name }}</label>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <label class="mb-2">Destination</label>
                <select id="filterDestination" class="form-select py-2 rounded-3 shadow-sm">
                    <option value="">Select Destination</option>
                    @foreach ($cities as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="mb-2">Location</label>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center" type="button" id="filterLocationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="filterLocationText">Select Locations</span>
                    </button>
                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="filterLocationDropdown" style="max-height: 200px; overflow-y: auto;" id="filter-districts-checkboxes">
                    </ul>
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-add px-4 w-100" type="button" id="filterBtn">Filter</button>
            </div>
        </div>
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
                        <th class="text-start"><span>Created By</span></th>
                        <th class="text-start"><span>Date </span></th>
                        <th class="text-start"><span> Package Duplicate </span></th>
                        <th class="text-start"> <span> Status </span> </th>
                        <th class="text-start"><span> Action </span></th>
                    </tr>
                </thead>
                <!-- <tbody>
                    @if($inclusive_packages->isEmpty())
                    <tr>
                        <td colspan="9" class="text-start">No records</td>
                    </tr>
                    @else
                    @foreach ($inclusive_packages as $row)
                    @php
                    $eventsPackageImages = is_array($row->events_package_images)
                    ? $row->events_package_images
                    : json_decode($row->events_package_images, true);

                    $firstImage = isset($eventsPackageImages[0]) ? $eventsPackageImages[0] : null;

                    $categories = is_array($row->category)
                    ? $row->category
                    : json_decode($row->category, true);

                    $formattedCategories = is_array($categories)
                    ? implode(', ', array_map(fn($cat) => str_replace('_', ' ', $cat), $categories))
                    : str_replace('_', ' ', $categories);
                    @endphp
                    <tr>
                        <td class="text-start">{{ $loop->iteration }}</td>

                        <td class="text-start">{{ $row->title }}</td>
                        <td class="text-start"><img src="{{ $row->cover_img ? asset($row->cover_img) : asset($settings->footer_logo) }}" alt="{{ $row->alternate_name ?? 'Default Alt Text' }}" style="max-width: 100px; max-height: 100px; object-fit: cover;"></td>

                        <td class="text-start">{{ $formattedCategories }}</td>
                        <td class="text-start   ">{{ auth('admin')->user()->email ?? 'N/A' }}</td>  
                        <td class="text-start">
{{ $row->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i:s A') }}
</td>
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
                            <a href="{{ route('admin.inclusive_package_edit_form',$row->id) }}" title="Edit" class="table-edit-link">
                                <span class="fa-stack">
                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>

                            <a href="javascript:void(0);" class="table-link danger delconfirm" title="Delete" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.inclusive_package_delete') }}" data-csrf_token="{{ csrf_token() }}">
                                <span class="fa-stack">
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse" style="color: red !important;"></i>
                                </span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @endif

                </tbody> -->


                <tbody>
    @foreach ($inclusive_packages as $row)
        @php
            $eventsPackageImages = is_array($row->events_package_images)
                ? $row->events_package_images
                : json_decode($row->events_package_images, true);

            $categories = is_array($row->category)
                ? $row->category
                : json_decode($row->category, true);

            $formattedCategories = is_array($categories)
                ? implode(', ', array_map(fn($cat) => str_replace('_', ' ', $cat), $categories))
                : str_replace('_', ' ', $categories);
        @endphp

        <tr>
            <td class="text-start">{{ $loop->iteration }}</td>
            <td class="text-start">{{ $row->title }}</td>
            <td class="text-start">
                <img src="{{ $row->cover_img ? asset($row->cover_img) : asset($settings->footer_logo) }}"
                     alt="{{ $row->alternate_name ?? 'Default Alt Text' }}"
                     style="max-width: 100px; max-height: 100px; object-fit: cover;">
            </td>
            <td class="text-start">{{ $formattedCategories }}</td>
            <td class="text-start">{{ auth('admin')->user()->email ?? 'N/A' }}</td>
            <td class="text-start">
                {{ $row->created_at ? $row->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i:s A') : 'N/A' }}
            </td>
            <td class="text-start">
                <button type="button" class="btn text-dark duplicate_package" data-package_id="{{ $row->id }}">
                    <i class="fa fa-copy" style="color:blue !important"></i> Duplicate
                </button>
            </td>
            <td class="text-start">
                <a data-toggle="tooltip"
                   data-csrf_token="{{ csrf_token() }}"
                   class="stsconfirm"
                   href="javascript:void(0);"
                   data-row_id="{{ $row->id }}"
                   data-act_url="{{ route('admin.inclusive_package_status') }}"
                   data-stsmode="{{ $row->status == 1 ? 0 : 1 }}">
                    <button type="button" class="btn {{ $row->status == 1 ? 'btn-live' : 'btn-hold' }} px-5">
                        {{ $row->status == 1 ? 'Active' : 'In Active' }}
                    </button>
                </a>
            </td>
            <td class="text-start" style="width: 20%;">
                <a href="{{ route('admin.inclusive_package_edit_form',$row->id) }}" title="Edit" class="table-edit-link">
                    <span class="fa-stack">
                        <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                    </span>
                </a>

                <a href="javascript:void(0);" class="table-link danger delconfirm" title="Delete"
                   data-row_id="{{ $row->id }}"
                   data-act_url="{{ route('admin.inclusive_package_delete') }}"
                   data-csrf_token="{{ csrf_token() }}">
                    <span class="fa-stack">
                        <i class="fa fa-trash-o fa-stack-1x fa-inverse" style="color: red !important;"></i>
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

@endsection











@section('scripts')
<script>
    $(document).ready(function() {
        // Read URL params to restore filter state after page reload
        const urlParams = new URLSearchParams(window.location.search);
        const selectedThemes = urlParams.get('themes') ? urlParams.get('themes').split(',').filter(Boolean) : [];
        const selectedDestination = urlParams.get('destination') || '';
        const selectedLocations = urlParams.get('locations') ? urlParams.get('locations').split(',').filter(Boolean) : [];

        // Restore theme checkboxes
        if (selectedThemes.length) {
            selectedThemes.forEach(function(id) {
                $('#filter-theme-' + id).prop('checked', true);
            });
            updateFilterThemeText();
        }

        // Restore destination dropdown
        if (selectedDestination) {
            $('#filterDestination').val(selectedDestination);
            loadDistricts(selectedDestination, selectedLocations);
        }

        function updateFilterThemeText() {
            const checked = $('.filter-theme:checked').length;
            $('#filterThemeText').text(checked === 0 ? 'Select Themes' : (checked === 1 ? $('.filter-theme:checked').next('label').text().trim() : checked + ' themes selected'));
        }
        $('.filter-theme').change(updateFilterThemeText);
        $('#filterThemeDropdown ~ .dropdown-menu').click(e => e.target.type === 'checkbox' && e.stopPropagation());

        $('#filterDestination').change(function() {
            loadDistricts($(this).val(), []);
        });

        function loadDistricts(destination, restoreLocations) {
            const container = $('#filter-districts-checkboxes');
            container.empty();
            $('#filterLocationText').text('Select Locations');
            if (!destination) {
                container.html('<li class="text-muted p-2">Please select a destination first</li>');
                return;
            }
            $.ajax({
                url: '{{ route("get-multi-districts") }}',
                type: 'GET',
                data: { destination: destination },
                success: function(data) {
                    if (data && data.length > 0) {
                        $.each(data, function(index, district) {
                            container.append('<li><div class="form-check"><input class="form-check-input filter-location" type="checkbox" value="' + district.name + '" id="filter-district-' + district.id + '"><label class="form-check-label w-100" for="filter-district-' + district.id + '">' + district.name + '</label></div></li>');
                        });
                        // Restore previously selected locations
                        if (restoreLocations.length) {
                            restoreLocations.forEach(function(loc) {
                                $('.filter-location[value="' + loc + '"]').prop('checked', true);
                            });
                        }
                        $('.filter-location').change(updateFilterLocationText);
                        updateFilterLocationText();
                    }
                },
                error: function(xhr) {
                    console.error('Error loading districts:', xhr);
                }
            });
        }

        function updateFilterLocationText() {
            const checked = $('.filter-location:checked').length;
            $('#filterLocationText').text(checked === 0 ? 'Select Locations' : (checked === 1 ? $('.filter-location:checked').next('label').text().trim() : checked + ' locations selected'));
        }
        $('#filterLocationDropdown ~ .dropdown-menu').click(e => e.target.type === 'checkbox' && e.stopPropagation());

        $('#filterBtn').click(function() {
            const themes = $('.filter-theme:checked').map(function() {
                return $(this).val();
            }).get();
            const destination = $('#filterDestination').val();
            const locations = $('.filter-location:checked').map(function() {
                return $(this).val();
            }).get();
            window.location.href = '{{ route("admin.inclusive_package_list") }}?themes=' + themes.join(',') + '&destination=' + destination + '&locations=' + locations.join(',');
        });

        $('#cityTable').DataTable({
            "pageLength": 10,
            "lengthChange": true,
            "ordering": true,
            "searching": true,
            "language": {
                "emptyTable": "No records found",
                "searchPlaceholder": "Search cities...",
                "search": ""
            },
            "columnDefs": [{
                "orderable": true,
                "targets": [0, 3]
            }]
        });

        $(document).on('click', '.duplicate_package', function() {
            const packageId = $(this).data('package_id');
            const $button = $(this);

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


