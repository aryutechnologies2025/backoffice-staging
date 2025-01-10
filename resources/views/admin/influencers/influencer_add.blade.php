@extends('layouts.app')
@Section('content')

<style>
    .py-5 {
        margin-bottom: 0rem !important;
    }

    a:hover {
        color: red;
    }

    a {
        color: rgb(37, 150, 190);
    }

    .add {
        color: blue;
    }
    .h5{
        font-size: 500%;
        font-weight: bolder;
    }
</style>

<div class="container-fluid mt-4 py-5">
    <b><a href="/dashboard">Dashboard</a> > <a href="/influencer">Influencer</a> > <a class="add">Add</a></b>
    <br>
    <br>
    <h4 class="mb-4">{{ $title }}</h4>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.influencer_insert') }}" method="POST">
        @csrf
        <!-- Personal Details -->
        <div class="mb-4">
            <div class="card-header">
                <h5>Personal Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control  rounded-3 shadow-sm" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control  rounded-3 shadow-sm" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control  rounded-3 shadow-sm" id="phone" name="phone" value="{{ old('phone') }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="whatsapp" class="form-label">WhatsApp</label>
                        <input type="text" class="form-control  rounded-3 shadow-sm" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="gender" class="form-label ">Gender</label>
                        <select class="form-select  rounded-3 shadow-sm" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" class="form-control  rounded-3 shadow-sm" id="age" name="age" value="{{ old('age') }}" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control  rounded-3 shadow-sm" id="city" name="city" value="{{ old('city') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" class="form-control  rounded-3 shadow-sm" id="state" name="state" value="{{ old('state') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control  rounded-3 shadow-sm" id="country" name="country" value="{{ old('country') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Media Details -->
        @foreach(['instagram', 'linkedin', 'youtube', 'facebook', 'twitter'] as $platform)
        <div class="mb-4">
            <div class="card-header">
                <h5>{{ ucfirst($platform) }} Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="{{ $platform }}_name" class="form-label">{{ ucfirst($platform) }} Name</label>
                        <input type="text" class="form-control  rounded-3 shadow-sm" id="{{ $platform }}_name" name="{{ $platform }}_name" value="{{ old($platform.'_name') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="{{ $platform }}_profile_link" class="form-label">{{ ucfirst($platform) }} Profile Link</label>
                        <input type="text" class="form-control  rounded-3 shadow-sm" id="{{ $platform }}_profile_link" name="{{ $platform }}_profile_link" value="{{ old($platform.'_profile_link') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="{{ $platform }}_followers_count" class="form-label">{{ ucfirst($platform) }} Followers Count</label>
                        <input type="number" class="form-control  rounded-3 shadow-sm" id="{{ $platform }}_followers_count" name="{{ $platform }}_followers_count" value="{{ old($platform.'_followers_count') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="{{ $platform }}_category" class="form-label">{{ ucfirst($platform) }} Category</label>
                        <input type="text" class="form-control  rounded-3 shadow-sm" id="{{ $platform }}_category" name="{{ $platform }}_category" value="{{ old($platform.'_category') }}">
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="row g-2">
            <div class="col">
                <label class="fw-bold ">Status</label>
                <div class="form-check form-switch">
                    <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-end mt-4 py-5">
            <a href="{{ route('admin.influencer_list') }}">
                <button type="button" class="cancel-btn"> Cancel </button>
            </a>
            <button class="submit-btn sbmtBtn ms-4"> Submit </button>
        </div>
    </form>
</div>
@endsection