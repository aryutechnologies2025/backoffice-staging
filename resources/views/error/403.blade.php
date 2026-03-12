@extends('layouts.app')

@section('title', 'Access Denied')

@section('content')
    <div style="text-align:center; padding:80px 20px;">
        <h1 style="font-size:80px; font-weight:700; color:#ff4d4d;">403</h1>
        <h2 style="font-size:30px; font-weight:600; margin-bottom:20px;">Access Denied</h2>
        <p style="font-size:18px; margin-bottom:30px;">
            You don't have permission to access this page.
        </p>

        <button 
            onclick="window.history.back()" 
            style="padding:12px 25px; background:#007bff; color:white; border:none; border-radius:5px; cursor:pointer;"
        >
            ⟵ Go Back
        </button>
    </div>
@endsection