@extends('layouts.app')

@section('title', 'Page Expired')

@section('content')
<div style="text-align:center; padding:80px 20px;">
    <h1 style="font-size:80px; font-weight:700; color:#ff4d4d;">419</h1>
    <h2 style="font-size:30px; font-weight:600; margin-bottom:20px;">Page Expired</h2>
    <p style="font-size:18px; margin-bottom:30px;">
        Your session has expired or the form token is invalid.
        Please refresh the page and try again.
    </p>

    <button
        onclick="{{ route('admin.login') }}"
        style="padding:12px 25px; background:#007bff; color:white; border:none; border-radius:5px; cursor:pointer;">
        ⟵ Go Back
    </button>
</div>
@endsection