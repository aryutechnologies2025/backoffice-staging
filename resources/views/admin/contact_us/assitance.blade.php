@extends('layouts.app')
@section('content')
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

    .contact {
        font-family: 'Poppins', sans-serif;
        font-weight:600;
        color:#282833;
        font-size:13px;
    }
</style>

<div>
  
<div class="row body-sec py-3 px-5 justify-content-around">
        <div class="text-start col-lg-6 ">
            <h3 class="admin-title fw-bold">{{$title}}</h3>
        </div>
        <div class="text-end col-lg-6 ">
            <b><a href="/dashboard">Dashboard</a> > <a class="contact" href="">Assitance Form   </a></b>
        </div>
    </div>

    <div class="row body-sec px-5">
        <div class="bg-white pt-3 col-lg-12">
            <div class="table-sec rounded-bottom-4 mb-5">
                <table id="cityTable" class="table table-bordered pt-2">
                    <thead>
                        <tr class="rounded-top-4">
                            <th class="text-start"><span>S.No</span></th>
                            <th class="text-start"><span>Name</span></th>
                            <th class="text-start"><span>Email</span></th>
                            <th class="text-start"><span>Phone</span></th>
                            <th class="text-start"><span>Comments</span></th>
                            <th class="text-start"><span>Date</span></th>
                            <th class="text-start"><span>Action</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($assitance as $row)
                        <tr>
                            <td class="text-start">{{ $loop->iteration }}</td>
                            <td class="text-start">{{$row->name}}</td>
                            <td class="text-start">{{ $row->email }}</td>
                            <td class="text-start">{{ $row->phone }}</td>
                            <td class="text-start">{{$row->comments}}</td>
                            <td class="text-start">{{ \App\Helpers\DateHelper::formatDate($row->created_at) }}</td>
                            <td class="text-start">
                                <a href="javascript:void(0);" class="table-link danger delconfirm" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.assistance_delete') }}" data-csrf_token="{{ csrf_token() }}">
                                    <span class="fa-stack">
                                        <!-- <i class="fa fa-square fa-stack-2x"></i> -->
                                        <i class="fa fa-trash-o fa-stack-1x fa-inverse" style="color:red !important;"></i>
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

    <!-- Modal placed outside of navbar -->

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
    });
</script>

@endsection