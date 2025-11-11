@extends('layouts.app')
@Section('content')

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
</style>

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a class="city" href="">Customer Package</a></b>
    </div>
    <div class="mt-2 mb-2 col-lg-12">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.CustomerPackage_form') }}">
                <button class="btn btn-add px-5" type="button"> Create Customer </button>
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
                        <th class="text-start "><span> Name </span></th>
                        <th class="text-start"><span> Phone Number </span></th>
                        <th class="text-start"><span> Email </span></th>
                        <th class="text-start"><span> Package Type </span></th>
                        <th class="text-start"><span> Status </span></th>
                        <th class="text-start"><span> Package URL </span></th>
                        <th class="text-start"><span> Package Duplicate </span></th>
                        <th class="text-start"><span> Action </span></th>
                    </tr>
                </thead>
                <tbody>
                    @if($customer_package_list->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">No records</td>
                    </tr>
                    @else
                    @foreach ($customer_package_list as $row)

                    <tr>
                        <td class="text-start">{{ $loop->iteration }}</td>


                        <!-- <td class="text-center"><img src="{{ $row->cover_img ? asset($row->cover_img) : asset($settings->footer_logo) }}" alt="{{ $row->alternate_name ?? 'Default Alt Text' }}" style="max-width: 100px; max-height: 100px; object-fit: cover;"></td> -->
                        <td class="text-start">{{ $row->name }}</td>
                        <td class="text-start">{{ $row->phone_number }}</td>
                        <td class="text-start">{{ $row->email }}</td>
                        <td class="text-start">{{ $row->package_type  }}</td>
                        <!-- <td class="text-center">{{ $row->created_at }}</td> -->
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
                        <td class="text-start"><a data-toggle="tooltip" data-csrf_token="{{ csrf_token() }}" data-original-title="{{ $actTitle }}" class="stsconfirm" href="javascript:void(0);" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.CustomerPackage_status') }}" data-stsmode="{{ $mode }}"><button type="button" class="btn {{ $btnColr }} px-5">{{ $disp_status }}</button></a></td>

                        <!-- <td class="text-center"><a href="https://innerpece.com/{{ $row->package_type }}/{{$row->package_type }}#{{$row->name}}">copy</a></td> -->
                        <td class="text-start text-primary">
                            @if(isset($row->status) && $row->status == '1')

                            <a href="#"
                                class="copy-link text-dark"
                                data-link="{{ env('APP_URL_FRONTEND') }}{{ $row->package_id }}/{{ str_replace(' ', '-', $row->package_type) }}#{{ $row->id }}"
                                title="Click to copy link">
                                <i class="fa fa-clone" aria-hidden="true"></i> copy
                            </a>

                            <span class="copy-feedback text-success small ms-2" style="display:none">Copied!</span>
                            @else
                            <span class="text-muted" title="Inactive package">
                                <i class="fa fa-clone" aria-hidden="true"></i> copy
                            </span>
                            @endif
                        </td>
                        <td class="text-start">
                            <button type="button" class="btn text-dark duplicate_package" data-package_id="{{ $row->id }}">
                                <i class="fa fa-copy" style="color:blue !important;"></i> Duplicate
                            </button>
                        </td>
                        <td class="text-start">
                            <a href="{{ route('admin.CustomerPackage_edit_form',$row->id) }}" title="Edit" class="table-edit-link">
                                <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>

                            <a href="javascript:void(0);" class="table-link danger delconfirm" title="Delete" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.CustomerPackage_delete') }}" data-csrf_token="{{ csrf_token() }}">
                                <span class="fa-stack">
                                    <!-- <i class="fa fa-square fa-stack-2x"></i> -->
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse text-danger" style="color: red !important;"></i>
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
                "searchPlaceholder": "Search cities...", // 👈 Your placeholder text
                "search": "" // 👈 This removes the "Search:" label
            },
            "columnDefs": [{
                "orderable": true,
                "targets": [0, 3]
            }]
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Copy link functionality
        document.querySelectorAll('.copy-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('data-link');

                // Copy to clipboard
                navigator.clipboard.writeText(url).then(() => {
                    // Show feedback
                    const feedback = this.nextElementSibling;
                    feedback.style.display = 'inline';
                    link.style.display = 'none';
                    setTimeout(() => {
                        feedback.style.display = 'none';
                        link.style.display = 'inline';
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                });
            });
        });

        $(document).on('click', '.duplicate_package', function() {
            const packageId = $(this).data('package_id');
            const $button = $(this);

            // Show loading state
            $button.html('<i class="fas fa-spinner fa-spin"></i> Duplicating...');
            $.ajax({
                url: '/customer-package/duplicate-entry-details',
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