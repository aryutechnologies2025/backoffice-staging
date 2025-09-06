@extends('layouts.app')
@section('content')
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
    
    .city{
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
       <b><a href="/dashboard" >Dashboard</a> > <a class="city" href="{{ route('admin.programeventslist') }}" >Events</a></b>
    </div>
    <div class="mt-2 mb-2 col-lg-12">
        <div class="d-flex justify-content-end">
           <a href="{{ route('admin.programeventsadd') }}">
                <button class="btn btn-add px-4" type="button">Add Event</button>
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
                        <th class="text-start">S.No</th>
                        <th class="text-start">Title</th>
                        <th class="text-start">Action</th>
                    </tr>
                </thead>

                     <tbody>
                 
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