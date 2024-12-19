@extends('layouts.app')
@Section('content')
<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-6">
        <h3 class="fw-bold"><span class="vr"></span>{{$title}}</h3>
    </div>
    <div class="col-lg-6">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.activities_add_form') }}">
                <button class="btn btn-add px-5" type="button">Add Activities</button>
            </a>
        </div>
    </div>
</div>

<!-- EVENT LIST -->
<div class="row body-sec px-5">
    <div class="col-lg-12">
        <div class="table-sec rounded-bottom-4 shadow-sm mb-5">
            <table class="table user-list">
                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-center"><span> Activities </span></th>
                        <th class="text-center"><span> Activities Logo</span></th>
                        <th class="text-center"><span> Status </span></th>
                        <th class="text-center"><span> Action </span></th>
                    </tr>
                </thead>
                <tbody>

                    @if($activities->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">No records</td>
                    </tr>
                    @else
                    @foreach ($activities as $row)
                    <tr>
                    <td class="text-center"><img src="{{ asset($row->activities_pic) }}" alt="Thumbnail" style="max-width: 100px; max-height: 100px; object-fit: cover;"></td>
                        <td class="text-center">{{ $row->activities }}</td>
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
                        <td class="text-center"><a data-toggle="tooltip" data-csrf_token="{{ csrf_token() }}" data-original-title="{{ $actTitle }}" class="stsconfirm" href="javascript:void(0);" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.activities_status') }}" data-stsmode="{{ $mode }}"><button type="button" class="btn {{ $btnColr }} px-5">{{ $disp_status }}</button></a></td>
                        <td class="text-center" style="width: 20%;">
                            <a href="{{ route('admin.activities_edit_form',$row->id) }}" class="table-edit-link">
                                <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>

                            <a href="javascript:void(0);" class="table-link danger delconfirm" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.activities_delete') }}" data-csrf_token="{{ csrf_token() }}">
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
            <!-- Pagination -->
            <div class="pagination-sec">
                <ul class="pagination justify-content-center">
                    <!-- Previous Page Link -->
                    @if ($activities->onFirstPage())
                    <li class="page-item disabled">
                        <a class="page-link rounded-circle text-dark fw-bold" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link rounded-circle text-dark fw-bold" href="{{ $activities->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    @endif
                    <!-- Page Number Links -->
                    @foreach ($activities->links()->elements[0] as $page => $url)
                    <li class="page-item {{ $page == $activities->currentPage() ? 'active' : '' }}">
                        <a class="page-link rounded-circle text-dark fw-bold px-3 ms-2" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @endforeach

                    <!-- Next Page Link -->
                    @if ($activities->hasMorePages())
                    <li class="page-item">
                        <a class="page-link rounded-circle text-dark fw-bold ms-2" href="{{ $activities->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <a class="page-link rounded-circle text-dark fw-bold ms-2" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection