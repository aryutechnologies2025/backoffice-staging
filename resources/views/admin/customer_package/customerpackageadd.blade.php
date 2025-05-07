@extends('layouts.app')
@section('content')
<style>
    a:hover {
        color: red;
    }
    a {
        color:rgb(37, 150, 190);
    }
    .enquiry {
        color:blue;
    }
</style>
    <div class="container mt-5 mb-5">
    <div class="col-lg-12">
        <b><a href="/dashboard" >Dashboard</a> > <a class="" href="/enquiry" >Booking</a></b> > <a class="enquiry" href="" >Add Booking</a>
        <br><br>
        <h3 class="fw-bold">{{$title}}</h3>
    </div>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.CustomerPackage_insert') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row d-flex gap-5">
                <div class="col-md-5 mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input id="name" type="text" class="form-control" name="name" required>
                </div>

                <div class="col-md-5 mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input id="phone" type="text" class="form-control" name="phone_number" required>
                </div>

                <div class="col-md-5 mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control" name="email" required>
                </div>

                <!-- <div class="col-md-5 mb-3">
                    <label for="type"  class="form-label">Package Type</label>
                    <input id="type" type="text" class="form-control" name="package_type" required>
                    
                </div> -->
                <div class="col-md-5 mb-3">
                <label for="title_id" class="form-label">Select Package Type</label>
                <select name="package_type" id="title_id" class="form-control"  required>
                    <option disabled selected>Select Package Type</option>
                        @foreach($titles as $id => $name)
                            <option value="{{ json_encode(['id' => $id, 'name' => $name]) }}">{{ $name }}</option>
                         @endforeach
                    </select>
        </div>

            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary mb-5">Submit</button>
            </div>
        </form>
    </div>
@endsection
