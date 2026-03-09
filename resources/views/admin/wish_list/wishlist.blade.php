@extends('layouts.app')
@Section('content')
<style>
    a:hover {
        color: rgb(27, 108, 138);
    }
    a{
        font-family: 'Poppins', sans-serif;
        font-weight:500;
        color:#8B7eff;
        font-size:13px;
    }

    .user{
        font-family: 'Poppins', sans-serif;
        font-weight:600;
        color:#282833;
        font-size:13px;
    }

</style>

 <div class="row body-sec py-3 px-5 justify-content-around">
        <div class="text-start col-lg-6 ">
            <h3 class="admin-title fw-bold">{{$title}}</h3>
        </div>
        <div class="text-end col-lg-6 ">
            <b><a href="/dashboard" >Dashboard</a> > <a class="user" href="/wish-list" >WishList</a></b>
        </div>
    </div>

<div class="row body-sec px-5">
    <div class="bg-white pt-3 col-lg-12">
        <div class="table-sec rounded-bottom-4 mb-5">
        <table id="cityTable" class="table table-bordered pt-2">
                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-start"><span>S.No</span></th>
                        <th class="text-start"><span> Program Name </span></th>
                        <th class="text-start"><span> User Name </span></th>
                        <th class="text-start"><span>User Email</span></th>
                        <th class="text-start"><span>User Phone</span></th>
                        <th class="text-start"><span>Created By</span></th>
                        <th class="text-start"><span>Date</span></th>

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
                    <td class="text-start">{{ $loop->iteration }}</td>

                    <td class="text-start">{{ $row->program_dts ? $row->program_dts->title : 'N/A' }}</td>
                    <td class="text-start">{{ $row->user ? $row->user->first_name : 'N/A' }}{{$row->user ? $row->user->last_name : 'N/A'}}</td>
               <td class="text-start">{{ $row->user ? $row->user->email : 'N/A' }}</td>
               <td class="text-start">{{ $row->user ? $row->user->phone : 'N/A' }}</td>
               <td class="text-start">{{ auth('admin')->user()->email ?? 'N/A' }}</td>
             <td class="text-start">
{{ $row->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i:s A') }}
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