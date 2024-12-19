@extends('layouts.app')
@Section('content')
<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-12">
        <h3 class="fw-bold"><span class="vr"></span>{{$title}}</h3>
    </div>

</div>

<div class="row body-sec px-5">
    <div class="col-lg-12">
        <div class="table-sec rounded-bottom-4 shadow-sm mb-5">
            <table class="table user-list">
                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-center"><span> Name</span></th>
                        <th class="text-center"><span>Email</span></th>
                        <th class="text-center"><span>Phone</span></th>
                        <th class="text-center"><span>Message</span></th>
                    </tr>
                </thead>
                <tbody>

                    @if($contact_dts->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">No records</td>
                    </tr>
                    @else
                    @foreach ($contact_dts as $row)
                    <tr>
                        <td class="text-center">{{ $row->first_name }} {{ $row->last_name }}</td>
                        <td class="text-center">{{ $row->email }}</td>
                        <td class="text-center">{{ $row->phone }}</td>
                        <td class="text-center">{{ $row->message }}</td>
                    </tr>
                    @endforeach
                    @endif

                </tbody>
            </table>
            <!-- Pagination -->
            <div class="pagination-sec">
                <ul class="pagination justify-content-center">
                    <!-- Previous Page Link -->
                    @if ($contact_dts->onFirstPage())
                    <li class="page-item disabled">
                        <a class="page-link rounded-circle text-dark fw-bold" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link rounded-circle text-dark fw-bold" href="{{ $contact_dts->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    @endif
                    <!-- Page Number Links -->
                    @foreach ($contact_dts->links()->elements[0] as $page => $url)
                    <li class="page-item {{ $page == $contact_dts->currentPage() ? 'active' : '' }}">
                        <a class="page-link rounded-circle text-dark fw-bold px-3 ms-2" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @endforeach

                    <!-- Next Page Link -->
                    @if ($contact_dts->hasMorePages())
                    <li class="page-item">
                        <a class="page-link rounded-circle text-dark fw-bold ms-2" href="{{ $contact_dts->nextPageUrl() }}" aria-label="Next">
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