@extends('layouts.app')
@Section('content')
@if(!$user_details)
<div class="alert alert-danger" role="alert">
    Record not found.
</div>
@else
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

    .form-body {

        border-radius: 10px;

    }
</style>
<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a href="{{ route('admin.registereventslist') }}">Event Registation</a> > <a class="edit">View</a></b>
    </div>

</div>

<!-- FORM -->
<div class="row mb-5">
    <div class="col-lg-12">
        <!-- INFORMATION -->
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">

            <div class="mb-3">
                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Name:</label>
                        <input type="text" class="form-control "
                            name="name"
                            value="{{ $user_details->name ?? '' }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Email:</label>
                        <input type="email" class="form-control"
                            name="email"
                            value="{{ $user_details->email ?? '' }}"
                            readonly>
                    </div>
                </div>

                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Phone:</label>
                        <input type="number" class="form-control "
                            name="phone"
                            value="{{ $user_details->phone ?? '' }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">Location:</label>
                        <input type="text" class="form-control"
                            name="state"
                            value="{{ $user_details->state ?? '' }}"
                            readonly>
                    </div>
                </div>

                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col-lg-6 pe-3">
                        <label class="fw-bold mb-2">DOB:</label>
                        <input type="date" class="form-control "
                            name="dob"
                            value="{{ $user_details->dob ?? '' }}"
                            readonly>
                    </div>
                    <div class="add_form col">
                        <label class="fw-bold mb-2">Anniversary Date:</label>
                        <input type="text" class="form-control"
                            name="anniversary_date"
                            value="{{ $user_details->anniversary_date ?? '' }}"
                            readonly>
                    </div>
                </div>

                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Street:</label>
                        <input type="text" class="form-control "
                            name="street"
                            value="{{ $user_details->street ?? '' }}"
                            readonly>
                    </div>

                    <div class="add_form col">
                        <label class="fw-bold mb-2">City:</label>
                        <input type="text" class="form-control"
                            name="city"
                            value="{{ $user_details->city ?? '' }}"
                            readonly>
                    </div>
                </div>

                <div class="row g-2  mb-4 d-flex">
                    <div class="add_form col pe-4">
                        <label class="fw-bold mb-2">Country:</label>
                        <input type="text" class="form-control "
                            name="country"
                            value="{{ $user_details->country ?? '' }}"
                            readonly>
                    </div>
                    <div class="add_form col">
                        <label class="fw-bold mb-2">Date & Time:</label>
                        <input type="text" class="form-control"
                            name="created_at"
                            value="{{ $user_details->created_at ? $user_details->created_at->format('d/m/Y h:i:s') : '' }}"
                            readonly>
                    </div>

                </div>

                <div class="row g-2 mb-4 d-flex">
                    <div class="col-md-6">
                        <div class="add_form col">
                            <label class="fw-bold mb-2">Notes:</label>
                            <textarea class="form-control"
                                name="notes"
                                rows="4"
                                readonly>{{ htmlspecialchars($user_details->notes ?? '', ENT_QUOTES) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 text-center mt-5">
                <a href="{{ route('admin.registereventslist') }}">
                    <button type="button" class="cancel-btn"> Cancel </button>
                </a>
            </div>
        </div>
    </div>
</div>

</div>

@endif
@endsection