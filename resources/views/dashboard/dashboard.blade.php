@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 py-5">
    <div class="row justify-content-between">

        <!-- Dashboard Title Section -->
        <div class="col-12 mb-4">
            <h3>{{ $title }}</h3>
            <h5 class="mt-4">Welcome, <b>{{ Session::get('admin_name') }}</b></h5>
            <p>More Travel, More Peace</p>
        </div>
        
        <!-- Dashboard Cards -->
        <div class="row">
        

            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <div class="card shadow bg-white rounded d-flex align-items-center h-100">
                    <div class="card-body d-flex align-items-center w-100">
                        <!-- Icon on the left -->
                        <div class="px-2">
                            <a href="/user"> <img src="/assets/image/dashboard/innerpece_dashboard_Wishlist_icon.svg" alt="" class="img-fluid" style="height: 60px;" />
                            </a>
                            <!-- <img src="/assets/image/dashboard/innerpece_dashboard_Wishlist_icon.svg" alt="" class="img-fluid" style="height: 60px;" /> -->
                        </div>
                        <!-- Text on the right -->
                        <div class="text-center">
                            <p class="mb-0 text-muted">Total User</p>
                            <a href="/user">
                                <h4 class="font-weight-bold text-dark">{{ $userRegister }}</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Cards (for reviews, listings, etc.) -->
            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <div class="card shadow bg-white rounded d-flex align-items-center h-100">
                    <div class="card-body d-flex align-items-center w-100">
                        <!-- Icon on the left -->
                        <div class="px-2">
                            <a href="/enquiry"> <img src="/assets/image/dashboard/innerpece_dashboard_listing_icon.svg" alt="" class="img-fluid" style="height: 60px;" />
                            </a>
                            <!-- <img src="/assets/image/dashboard/innerpece_dashboard_listing_icon.svg" alt="" class="img-fluid" style="height: 60px;" /> -->
                        </div>
                        <!-- Text on the right -->
                        <div class="text-center">
                            <p class="mb-0 text-muted">Total Enquiry</p>
                            <a href="/enquiry">
                                <h4 class="font-weight-bold text-dark">{{$enquiryCount}}</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <div class="card shadow bg-white rounded d-flex align-items-center h-100">
                    <div class="card-body d-flex align-items-center w-100">
                        <!-- Icon on the left -->
                        <div class="px-2">
                            <a href="/client_review"> <img src="/assets/image/dashboard/innerpece_dashboard_review_icon.svg" alt="" class="img-fluid" style="height: 60px;" />
                            </a>
                            <!-- <img src="/assets/image/dashboard/innerpece_dashboard_review_icon.svg" alt="" class="img-fluid" style="height: 60px;" /> -->
                        </div>
                        <!-- Text on the right -->
                        <div class="text-center">
                            <p class="mb-0 text-muted">Total Reviews</p>
                            <a href="/client_review">
                                <h4 class="font-weight-bold text-body">{{$clientReview}}</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection