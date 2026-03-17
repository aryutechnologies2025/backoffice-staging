@extends('layouts.app')
@section('content')

<style>
    a:hover { color: red; }
    a { color: rgb(37, 150, 190); }
    .add { color: blue; }
</style>

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6">
        <b>
            <a href="/dashboard">Dashboard</a> > 
            <a href="/user">User</a> > 
            <a class="add">Add</a>
        </b>
    </div>
</div>

<!-- 🔥 ERROR DISPLAY -->
@if ($errors->any())
    <div class="alert alert-danger mx-5">
        @foreach ($errors->all() as $error)
            <p class="mb-0">{{ $error }}</p>
        @endforeach
    </div>
@endif

<!-- FORM -->
<div class="row mb-5">
<div class="col-lg-12">

<div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">

<form action="{{ route('admin.user_insert') }}" method="POST" autocomplete="off">
@csrf

<div class="mb-3">

<!-- FIRST + LAST -->
<div class="row g-2 mb-4">
    <div class="col-lg-6 pe-4">
        <label class="fw-bold mb-2">First Name *</label>
        <input type="text" name="first_name" value="{{old('first_name')}}" class="form-control" required>
    </div>

    <div class="col-lg-6">
        <label class="fw-bold mb-2">Last Name *</label>
        <input type="text" name="last_name" value="{{old('last_name')}}" class="form-control" required>
    </div>
</div>

<!-- EMAIL + PASSWORD -->
<div class="row g-2 mb-4">
    <div class="col-lg-6 pe-4">
        <label class="fw-bold mb-2">Email *</label>
        <input type="email" name="email" value="{{old('email')}}" class="form-control" required>
    </div>

    <div class="col-lg-6">
        <label class="fw-bold mb-2">Password *</label>
        <input type="password" name="password" class="form-control" required>
    </div>
</div>

<!-- CONFIRM + DOB -->
<div class="row g-2 mb-4">
    <div class="col-lg-6 pe-4">
        <label class="fw-bold mb-2">Confirm Password *</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <div class="col-lg-6">
        <label class="fw-bold mb-2">DOB *</label>
        <input type="date" name="dob" value="{{old('dob')}}" class="form-control" required>
    </div>
</div>

<!-- PHONE + STREET -->
<div class="row g-2 mb-4">
    <div class="col-lg-6 pe-4">
        <label class="fw-bold mb-2">Phone *</label>
        <input type="text" name="phone" value="{{old('phone')}}" class="form-control" required>
    </div>

    <div class="col-lg-6">
        <label class="fw-bold mb-2">Street *</label>
        <input type="text" name="street" value="{{old('street')}}" class="form-control" required>
    </div>
</div>

<!-- CITY + STATE -->
<div class="row g-2 mb-4">
    <div class="col-lg-6 pe-4">
        <label class="fw-bold mb-2">City *</label>
        <input type="text" name="city" value="{{old('city')}}" class="form-control" required>
    </div>

    <div class="col-lg-6">
        <label class="fw-bold mb-2">State *</label>
        <input type="text" name="state" value="{{old('state')}}" class="form-control" required>
    </div>
</div>

<!-- ZIP + COUNTRY -->
<div class="row g-2 mb-4">
    <div class="col-lg-6 pe-4">
        <label class="fw-bold mb-2">Zip Code *</label>
        <input type="text" name="zip_province_code" value="{{old('zip_province_code')}}" class="form-control" required>
    </div>

    <div class="col-lg-6">
        <label class="fw-bold mb-2">Country *</label>
        <input type="text" name="country" value="{{old('country')}}" class="form-control" required>
    </div>
</div>

<!-- LANG + CHECKBOX -->
<div class="row g-2 mb-4">
    <div class="col-lg-6 pe-4">
        <label class="fw-bold mb-2">Preferred Language *</label>
        <input type="text" name="preferred_lang" value="{{old('preferred_lang')}}" class="form-control" required>
    </div>

    <div class="col-lg-6 d-flex align-items-center gap-4">

        <div>
            <label>Newsletter</label><br>
            <input type="checkbox" name="newsletter_sub" value="1">
        </div>

        <div>
            <label>Terms *</label><br>
            <!-- 🔥 FIX -->
            <input type="checkbox" name="terms_condition" value="1" {{ old('terms_condition') ? 'checked' : '' }}>
        </div>

        <div>
            <label>Status</label><br>
            <input type="checkbox" name="status" value="1">
        </div>

    </div>
</div>

</div>

<!-- BUTTON -->
<div class="text-center mt-4">
    <a href="{{ route('admin.user_list') }}" class="btn btn-secondary">Cancel</a>

    <!-- 🔥 FIX -->
    <button type="submit" class="btn btn-primary ms-3">
        Submit
    </button>
</div>

</form>
</div>
</div>
</div>

@endsection