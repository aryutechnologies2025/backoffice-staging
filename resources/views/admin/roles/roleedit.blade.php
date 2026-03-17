@extends('layouts.app')
@Section('content')

<style>
    a:hover {
        color: red;
    }

    a {
        color: rgb(37, 150, 190);
    }

    .edit {
        color: blue;
    }

    /* Align the form with the title */
    .container-wrapper {
        padding-left: 30px;
        /* Adjust as per your layout */
        padding-right: 30px;
        /* Consistent padding for both sides */
    }


</style>

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
         <b><a href="/dashboard">Dashboard</a> > <a href="/role">Role</a> > <a class="edit">Edit</a></b>
    </div>

</div>
<!-- FORM -->
<div class="row mb-5">
    <div class="col-lg-12">
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form id="form_valid" action="{{ route('admin.role_update', ['id'=>$role_details->id]) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="row g-2 mb-4">
                    <div class="add_form col-md-4 pe-3">
                        <label class="fw-bold mb-3"> Role Name </label>
                        <input type="text" placeholder="Role Name" id="role_name" name="role_name" class="form-control py-2 rounded-3 shadow-sm" value="{{ $role_details->role_name }}">
                        @error('role_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
        </div>


        <div class="row g-2 mb-4">
            <div class="add_form col">
                <label class="fw-bold ">Status</label>
                <div class="form-check form-switch">
                    <input class="form-check-input check_bx" type="checkbox" id="status" name="status" {{ $role_details->status ? 'checked' : '' }}>
                </div>
            </div>
        </div>


        <div class="text-center mt-4">
            <a href="{{ route('admin.role_list') }}">
                <button type="button" class="cancel-btn"> Cancel </button>
            </a>
            <button class="submit-btn sbmtBtn ms-4"> Submit </button>
        </div>
        </form>
    </div>
</div>
</div>
@endsection

