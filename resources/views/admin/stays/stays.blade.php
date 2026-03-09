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

    .img {
        background-color: #ddd !important;
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
        <h3 class="admin-title fw-bold pb-2">Stays List</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a class="city" href="/stay_list">Stays</a></b>
    </div>
    <div class="mt-2 mb-2 col-lg-12">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.stays_add_form') }}">
                <button class="btn btn-add px-4" type="button">Add Stays</button>
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
                        <th class="text-start"><span> S.No</span></th>
                        <th class="text-start"><span> Destination </span></th>
                        <th class="text-start"><span> Title </span></th>
                        <th class="text-start"><span> Tag Line </span></th>
                        <!-- <th class="text-center"><span> Description </span></th>  -->
                         <th class="text-start"><span>Date</span></th>
                        <th class="text-start"><span> Stay URL </span></th>
                        <th class="text-start"><span> Status </span></th>
                        <th class="text-start"><span> Action </span></th>
                    </tr>
                </thead>

                <tbody>
                    @if($stay_details->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">No records</td>
                    </tr>
                    @else
                    @foreach ($stay_details as $row)

                    <tr>
                        <td class="text-start">{{ $loop->iteration }}</td>

                       <td class="text-start">{{ $row->city ? $row->city->city_name : 'N/A' }}</td>
                        <td class="text-start">{{ $row->stay_title }}</td>
                        <td class="text-start">{{ $row->tag_line }}</td>
                        <!-- <td class="text-center">{{ $row->stay_description }}</td> -->
                         <td class="text-start">{{ $row->created_at->format('d-m-Y H:i:s') }}</td>

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

                        <td class="text-start text-primary">
                            @if(isset($row->status) && $row->status == '1')

                            <a href="#"
                                class="copy-link text-dark"
                                data-link="{{ env('APP_URL_FRONTEND') }}staysdetails/{{ $row->id }}"
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

                        <td class="text-start"><a data-toggle="tooltip" data-csrf_token="{{ csrf_token() }}" data-original-title="{{ $actTitle }}" class="stsconfirm" href="javascript:void(0);" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.stay_change_status') }}" data-stsmode="{{ $mode }}"><button type="button" class="btn {{ $btnColr }} px-5">{{ $disp_status }}</button></a></td>
                        <td class="text-start" style="width: 20%;">
                            <a href="{{ route('admin.stay_details_edit_form',$row->id) }}" title="Edit" class="table-edit-link">
                                <span class="fa-stack">
                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>

                            <a href="javascript:void(0);" class="table-link danger delconfirm" title="Delete" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.stay_details_delete') }}" data-csrf_token="{{ csrf_token() }}">
                                <span class="fa-stack">
                                    <!-- <i class="fa fa-square fa-stack-2x"></i> -->
                                    <i class="fa fa-trash" style="color: red !important;"></i>
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
<button class="sticky-btn" id="myBtn"><a href="#watch"><i class="bi bi-caret-up-square text-white"></i></button>
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
    });
</script>
@endsection