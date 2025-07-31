@extends('layouts.app')
@section('content')
<style>
    a:hover {
        color: rgb(27, 108, 138);
    }

    a {
        color: rgb(37, 150, 190);
    }

    .contact {
        color: rgb(27, 108, 138);
    }
</style>

<div>
    <div class="row body-sec py-5 px-5 justify-content-around">
        <div class="col-lg-12">
            <b><a href="/dashboard">Dashboard</a> > <a class="contact" href="">Assitance Form</a></b>
            <br>
            <br>
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
                            <th class="text-center"><span>Comments</span></th>
                            <th class="text-center"><span>Date</span></th>
                            <th class="text-center"><span>Action</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($assitance->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center">No records</td>
                        </tr>
                        @else
                        @foreach ($assitance as $row)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{$row->name}}</td>
                            <td class="text-center">{{ $row->email }}</td>
                            <td class="text-center">{{ $row->phone }}</td>
                            <td class="text-center">{{$row->comments}}</td>
                            <td class="text-center">{{ \App\Helpers\DateHelper::formatDate($row->created_at) }}</td>
                            <td class="text-center">
                                <a href="javascript:void(0);" class="table-link danger delconfirm" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.assistance_delete') }}" data-csrf_token="{{ csrf_token() }}">
                                    <span class="fa-stack">
                                        <!-- <i class="fa fa-square fa-stack-2x"></i> -->
                                        <i class="fa fa-trash-o fa-stack-1x fa-inverse" style="color:red !important;"></i>
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

    <!-- Modal placed outside of navbar -->

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#cityTable').DataTable({
            pageLength: 10,
            lengthChange: true,
            ordering: true,
            searching: true,
            language: {
                emptyTable: 'No records found',
            },
            columnDefs: [{
                orderable: true,
                targets: [3],
            }, ],
        });


    });
</script>

@endsection