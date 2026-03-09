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
        <b>
            <a href="/dashboard">Dashboard</a> >
            <a class="city" href="">Pricing Calculator</a>
        </b>
    </div>

    <div class="mt-2 mb-2 col-lg-12">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.pricing_add_form') }}">
                <button class="btn btn-add px-4" type="button">Add Pricing</button>
            </a>
        </div>
    </div>
</div>


<!-- TABLE -->
<div class="row body-sec px-5">
    <div class="bg-white pt-3 col-lg-12">

        <div class="table-sec rounded-bottom-4 mb-5">

            <table id="cityTable" class="table table-bordered pt-2">

                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-start">S.No</th>
                        <th class="text-start">Title</th>
                        <th class="text-start">Destination</th>

                        <!-- NEW COLUMN -->
                        <th class="text-start">Created By</th>

                        <th class="text-start">Total</th>
                        <th class="text-start">Date</th>
                        <th class="text-start">Status</th>
                        <th class="text-start">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($pricingCalculators as $row)

                    <tr>

                        <td class="text-start">{{ $loop->iteration }}</td>

                        <td class="text-start">
                            {{ ucfirst($row->title) }}
                        </td>

                        <td class="text-start">
                            {{ $row->destination_names_string }}
                        </td>

                        <!-- SHOW ADMIN EMAIL -->
                        <td class="text-start">
                            {{ $row->created_by ?? 'N/A' }}
                        </td>

                        <td class="text-start">
                            {{ $row->total_pricing }}
                        </td>

                        <td class="text-start">
                            {{ $row->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i:s A') }}
                        </td>

                        @php
                        $disp_status = 'In Active';
                        $actTitle = 'Click to activate';
                        $mode = 1;
                        $btnColr = 'btn-hold';

                        if(isset($row->status) && $row->status == '1'){
                            $disp_status = 'Active';
                            $mode = 0;
                            $btnColr = 'btn-live';
                            $actTitle = 'Click to deactivate';
                        }
                        @endphp

                        <td class="text-start">

                            <a data-toggle="tooltip"
                               data-csrf_token="{{ csrf_token() }}"
                               data-original-title="{{ $actTitle }}"
                               class="stsconfirm"
                               href="javascript:void(0);"
                               data-row_id="{{ $row->id }}"
                               data-act_url="{{ route('admin.pricing_change_status') }}"
                               data-stsmode="{{ $mode }}">

                                <button type="button" class="btn {{ $btnColr }} px-5">
                                    {{ $disp_status }}
                                </button>

                            </a>

                        </td>

                        <td class="text-start" style="width:20%;">

                            <a href="{{ route('admin.pricing_edit_form',$row->id) }}"
                               title="Edit"
                               class="table-edit-link">

                                <span class="fa-stack">
                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                </span>

                            </a>


                            <a href="javascript:void(0);"
                               class="table-link danger delconfirm"
                               title="Delete"
                               data-row_id="{{ $row->id }}"
                               data-act_url="{{ route('admin.pricingdelete') }}"
                               data-csrf_token="{{ csrf_token() }}">

                                <span class="fa-stack">
                                    <i class="fa fa-trash" style="color:red !important;"></i>
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

$(document).ready(function(){

    $('#cityTable').DataTable({

        "pageLength":10,
        "lengthChange":true,
        "ordering":true,
        "searching":true,

        // NEWEST RECORD FIRST
        "order": [[0, "desc"]],

        "language":{
            "emptyTable":"No records found",
            "searchPlaceholder":"Search cities...",
            "search":""
        },

        "columnDefs":[
            {
                "orderable":true,
                "targets":[0,3]
            }
        ]

    });

});

</script>

@endsection