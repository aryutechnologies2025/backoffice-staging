@extends('layouts.app')
@Section('content')
<style>
    a:hover {
        color: red;
    }
    a{
        color:rgb(37, 150, 190);
    }
    .user{
        color:blue;
    }

</style>
<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-12">
    <b><a href="/dashboard" >Dashboard</a> > <a class="user" href="/wish-list" >WishList</a></b>
        <br>
        <br>
        <h3 class="fw-bold">{{$title}}</h3>
    </div>

</div>

<div class="row body-sec px-5">
    <div class="col-lg-12">
        <div class="table-sec rounded-bottom-4 mb-5">
        <table id="cityTable" class="table  pt-2">
                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-center"><span>S.No</span></th>
                        <th class="text-center"><span> Program Name </span></th>
                        <th class="text-center"><span> User Name </span></th>
                        <th class="text-center"><span>User Email</span></th>
                        <th class="text-center"><span>User Phone</span></th>
                    </tr>
                </thead>
                <tbody>

                    @if($wishlist_dts->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">No records</td>
                    </tr>
                    @else
                    @foreach ($wishlist_dts as $row)
                    <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>

                    <td class="text-center">{{ $row->program_dts ? $row->program_dts->title : 'N/A' }}</td>
                    <td class="text-center">{{ $row->user ? $row->user->first_name : 'N/A' }}{{$row->user ? $row->user->last_name : 'N/A'}}</td>
               <td class="text-center">{{ $row->user ? $row->user->email : 'N/A' }}</td>
               <td class="text-center">{{ $row->user ? $row->user->phone : 'N/A' }}</td>   
                    </tr>
                    @endforeach
                    @endif

                </tbody>
            </table>
           
        </div>
    </div>
</div>

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 200,
            callbacks: {
                onChange: function(contents) {
                    $('#client_review').val(contents); // Sync content to hidden input
                }
            }
        });
    });
</script>
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