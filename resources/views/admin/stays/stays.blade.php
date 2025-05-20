@extends('layouts.app')
@Section('content')
<style>
    a:hover {
        color: rgb(27, 108, 138);
    }
    a{
        color:rgb(37, 150, 190);
    }
    

    .city{
      color: rgb(27, 108, 138);
    }
    .img{
        background-color: #ddd !important;
    }
</style>
<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-6">
    <b><a href="/dashboard" >Dashboard</a> > <a class="city" href="/stay_list" >Stays</a></b>
        <br>
        <br>
      
    </div>
    <div class="col-lg-6">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.themes_add_form') }}">
                <button class="btn btn-add px-5" type="button">Add Stay</button>
            </a>
        </div>
    </div>
</div>

<!-- EVENT LIST -->
<div class="row body-sec px-5">
    <div class="col-lg-12">
        <div class="table-sec rounded-bottom-4 mb-5">
        <table id="cityTable" class="table pt-2">                
            <thead>
                    <tr class="rounded-top-4">
                        <th class="text-center"><span>S.No</span></th>
                        <th class="text-center"><span>  Image </span></th>
                        <th class="text-center"><span> Title </span></th>
                        <th class="text-center"><span>Time&Date </span></th>
                        <th class="text-center"><span> Status </span></th>
                        <th class="text-center"><span> Action </span></th>
                    </tr>
                </thead>
             
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
            },
            "columnDefs": [
                { "orderable": true, "targets": [0, 3] } // Disable ordering on Icon and Action columns
            ]
        });
    });
</script>
@endsection