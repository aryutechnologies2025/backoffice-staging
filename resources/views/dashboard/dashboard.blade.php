@extends('layouts.app')
@Section('content')

<div class="row body-sec px-5 py-5 justify-content-around ">

    <h3>{{$title}}</h3>
    <p>There are many variations of passages of Lorem Ipsum</p>

    <div class="col-lg-2  d-flex bg-white  py-3 rounded dash-detail">
        <div class="mt-2 px-3">
            <img src="/assets/image/dashboard/innerpece_earning_icon.svg" alt="">
        </div>
        <div>
            <p>Total Earning </p>
            <h4>43216$</h4>
        </div>
    </div>

    <div class="col-lg-2 d-flex bg-white  py-3 rounded">
        <div class="mt-2 px-3">
            <img src="/assets/image/dashboard/innerpece_dashboard_Wishlist_icon.svg" alt="">
        </div>
        <div>
            <p>My Wishlist</p>
            <h4>2351$</h4>
        </div>
    </div>

    <div class="col-lg-2 d-flex bg-white  py-3 rounded">
        <div class="mt-2 px-3">
            <img src="/assets/image/dashboard/innerpece_dashboard_listing_icon.svg" alt="">
        </div>
        <div>
            <p>Total Listing </p>
            <h4>43216</h4>
        </div>
    </div>

    <div class="col-lg-2 d-flex bg-white  py-3 rounded">
        <div class="mt-2 px-3">
            <img src="/assets/image/dashboard/innerpece_dashboard_review_icon.svg" alt="">
        </div>
        <div>
            <p>Total Review</p>
            <h4>2351+</h4>
        </div>
    </div>
</div>
@endsection