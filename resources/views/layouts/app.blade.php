<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
    <title>{{ $settings->meta_title ?? 'Inner Pece' }}</title>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <!-- Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <!-- Bootstrap Multiselect JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@1.1.1/dist/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="icon" href="{{ $settings->fav_icon ? asset($settings->fav_icon) : '' }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="/assets/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="/assets/css/admin_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link rel="stylesheet" href="/assets/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- <link rel="stylesheet" href="/assets/css/bootstrap-icons.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"> -->
    <link rel="stylesheet" href="/assets/css/boxicons.min.css">
    <link rel="stylesheet" href="/assets/css/toastr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="/assets/css/summernote-bs4.min.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-lite.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    <!-- Include DataTables CSS and JS -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@1.1.0/dist/css/bootstrap-multiselect.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@1.1.0/dist/js/bootstrap-multiselect.min.js"></script>


    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->

    <!-- Bootstrap Multiselect CSS & JS -->
    <!--Multi Selector -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@1.1.1/dist/css/bootstrap-multiselect.css">


    @yield('scripts')
    @yield('style')
    @stack('js')
</head>

<body id="body-pd">

    <div class="row ">
        <header class="header py-2 ps-0 " id="header">
            <div class="col-lg-3 text-start" id="navbarNav">
                <div class="header_toggle mt-2">
                    <!-- <img id="header-toggle" src="/assets/image/dashboard/innerpece_toogle_icon.svg" alt=""> -->
                    <div id="header-toggle" class="text-dark px-3"><i class="bi bi-list"></i></div>
                </div>
            </div>

            <!-- <div class="col-lg-3 text-end pt-3">
                <a href="#"><i class="fa fa-bell text-white px-4 mt-3" style="font-size:30px"></i></a>
                <img class="" style="width: 20%;" src="/assets/image/dashboard/innerpece_admin_img.png" alt="">
            </div> -->
            <div class="d-flex gap-2   p-2 justify-content-center align-items-center ">
                <div id="liveTime" class=" text-dark"></div>
                <script>
                    function updateTime() {
                        const now = new Date();

                        const options = {
                            weekday: 'short', // Short day like "Mon", "Tue"
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                            hour: 'numeric',
                            minute: 'numeric',
                            hour12: true
                        };

                        const timeString = now.toLocaleString('en-US', options);
                        document.getElementById('liveTime').textContent = timeString;
                    }

                    setInterval(updateTime, 1000);
                    updateTime();
                </script>

                <style>
                    .stay-img,
                    .customer-package {
                        width: 20px;
                    }
                </style>
                <div class="bg-white rounded-5 py-2 px-4 d-none d-lg-flex gap-2  ">

                    <div>
                        <p class="fs-6 p-0 m-0 d-flex text-dark">Welcome👋 <span
                                class="fs-6 text-start fw-bold text-dark ms-1">Admin</span></p>
                        <b class="rounded-circle " style="width: 10%; height: auto;"
                            src="{{ session('admin_email') ? asset(session('admin_email')) : '/assets/image/dashboard/innerpece_admin_img.png' }}"
                            alt="admin_email"></b>
                        <span class="text-dark">{{ session('admin_email') }}</span>

                    </div>
                </div>

            </div>
            <!-- <div class="col-lg-3 text-end pt-3">
            <b class="rounded-circle" style="width: 10%; height: auto;" src="{{ session('admin_name') ? asset(session('admin_name')) : '/assets/image/dashboard/innerpece_admin_img.png' }}" alt="admin_name"></b>
            <span class="text-white">{{ session('admin_name') }}</span>
            <br>
            <b class="rounded-circle" style="width: 10%; height: auto;" src="{{ session('admin_email') ? asset(session('admin_email')) : '/assets/image/dashboard/innerpece_admin_img.png' }}" alt="admin_email"></b>
                <span class="text-white">{{ session('admin_email') }}</span>
            </div> -->
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    </header>
    </div>
    <div class="l-navbar" id="nav-bar">

        <nav class="nav w-100">
            <div>
                @php
                $permissions = session('permissions', []); // get permissions or empty array
                // helper function to check permission
                function hasPermission($permissions, $module, $action = null) {
                // if permissions array is empty, allow everything
                if(empty($permissions)) return true;

                if(!isset($permissions[$module])) return false;

                if($action) {
                return isset($permissions[$module][$action]) && $permissions[$module][$action];
                }

                // if no specific action, check any of create/edit/delete
                return $permissions[$module]['create'] ?? false
                || $permissions[$module]['edit'] ?? false
                || $permissions[$module]['delete'] ?? false 
                || $permissions[$module]['list'] ?? false 
                || $permissions[$module]['view'] ?? false;
                }
                @endphp

                <div class="nav_list ">
                    <img class="pt-3 px-2 " style="width:0%;"
                        src="{{ $settings->footer_logo ? asset($settings->footer_logo) : '/assets/image/login/inner_pece_logo.png' }}"
                        alt="">
                    <div>
                        <img src="{{ $settings->fav_icon }}" alt=""
                            class="px-4 mb-3 invisible navbar-toggle-icon" style="height: 30px;">
                    </div>



                    <p class="nav_link mb-0 text-white fw-bold">DASHBOARD</p>
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav_link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/Dashboard.svg" alt="">
                        <span class="nav_name">Dashboard</span>
                    </a>








  @if(empty($permissions) ||
    (isset($permissions['user_registration']) && ($permissions['user_registration']['create'] || $permissions['user_registration']['edit'] || $permissions['user_registration']['delete'] || $permissions['user_registration']['view'] || $permissions['user_registration']['list'])) ||
    (isset($permissions['admin_user']) && ($permissions['admin_user']['create'] || $permissions['admin_user']['edit'] || $permissions['admin_user']['delete'] || $permissions['admin_user']['view'] || $permissions['admin_user']['list'])) ||
    (isset($permissions['influencer']) && ($permissions['influencer']['create'] || $permissions['influencer']['edit'] || $permissions['influencer']['delete'] || $permissions['influencer']['view'] || $permissions['influencer']['list'])) ||
    (isset($permissions['slider']) && ($permissions['slider']['create'] || $permissions['slider']['edit'] || $permissions['slider']['delete'] || $permissions['slider']['view'] || $permissions['slider']['list'])) ||
    (isset($permissions['role']) && ($permissions['role']['create'] || $permissions['role']['edit'] || $permissions['role']['delete'] || $permissions['role']['view'] || $permissions['role']['list']))
)

<p class="nav_link mb-0 text-white fw-bold">USER MANAGEMENT</p>



                    @if(empty($permissions) || (isset($permissions['user_registration']) &&
                    ($permissions['user_registration']['create'] ||
                    $permissions['user_registration']['edit'] ||
                    $permissions['user_registration']['view'] ||
                    $permissions['user_registration']['list'] ||
                    $permissions['user_registration']['delete'])))
                    <a href="{{ route('admin.user_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.user_list', 'admin.user_add_form', 'admin.user_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/user.svg" alt="">
                        <span class="nav_name"> User Registration </span>
                    </a>
                    @endif


                    
                    @if(empty($permissions) || isset($permissions['admin_user']) && ($permissions['admin_user']['create'] || $permissions['admin_user']['edit'] || $permissions['admin_user']['view'] || $permissions['admin_user']['list'] || $permissions['admin_user']['delete']))
                    <a href="{{ route('admin.admin_user_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.admin_user_list', 'admin.admin_user_add_form', 'admin.admin_user_edit_form']) ? 'active' : '' }} mb-3">
                        <img src="/assets/image/dashboard/settings.svg" alt="">
                        <span class="nav_name" style="color: #fff;"> Admin User </span>
                    </a>
                    @endif


                     @if(empty($permissions) || isset($permissions['role']) && ($permissions['role']['create'] || $permissions['role']['edit'] || $permissions['role']['view'] || $permissions['role']['list'] || $permissions['role']['delete']))
                    <a href="{{ route('admin.role_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.role_list','admin.role_add_form', 'admin.role_edit_form']) ? 'active' : '' }} mb-3">
                        <img src="/assets/image/dashboard/settings.svg" alt="">
                        <span class="nav_name" style="color: #fff;"> Role </span>
                    </a>
                    @endif


                    @if(empty($permissions) ||isset($permissions['user_permission']) && ($permissions['user_permission']['create'] || $permissions['user_permission']['edit'] || $permissions['user_permission']['view'] || $permissions['user_permission']['list'] || $permissions['user_permission']['delete']))
                    <a href="{{ route('admin.user_permission_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.user_permission_list', 'admin.user_permission_add_form', 'admin.user_permission_edit_form']) ? 'active' : '' }} mb-3">
                        <img src="/assets/image/dashboard/settings.svg" alt="">
                        <span class="nav_name" style="color: #fff;"> User Permission </span>
                    </a>
                    @endif



                      @if(empty($permissions) || isset($permissions['influencer']) && ($permissions['influencer']['create'] || $permissions['influencer']['edit'] || $permissions['influencer']['view'] || $permissions['influencer']['list'] || $permissions['influencer']['delete']))
                       <a href="{{ route('admin.influencer_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.influencer_list', 'admin.influencer_add_form', 'admin.influencer_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/slider.svg" alt="">
                        <span class="nav_name"> Influencer </span>
                    </a>
                    @endif

                   
                    @if(empty($permissions) || isset($permissions['slider']) && ($permissions['slider']['create'] || $permissions['slider']['edit'] || $permissions['slider']['view'] || $permissions['slider']['list'] || $permissions['slider']['delete']))
                    <a href="{{ route('admin.slider_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.slider_list', 'admin.slider_add_form', 'admin.slider_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/slider.svg" alt="">
                        <span class="nav_name"> Slider </span>
                    </a>
                    @endif
                    @endif








@if(empty($permissions) ||
    (isset($permissions['contact_us']) && ($permissions['contact_us']['create'] || $permissions['contact_us']['edit'] || $permissions['contact_us']['delete'] || $permissions['contact_us']['view'] || $permissions['contact_us']['list'])) ||
    (isset($permissions['booking']) && ($permissions['booking']['create'] || $permissions['booking']['edit'] || $permissions['booking']['delete'] || $permissions['booking']['view'] || $permissions['booking']['list'])) ||
    (isset($permissions['enquiry']) && ($permissions['enquiry']['create'] || $permissions['enquiry']['edit'] || $permissions['enquiry']['delete'] || $permissions['enquiry']['view'] || $permissions['enquiry']['list']))
)
<p class="nav_link mb-0 text-white fw-bold">ENQUIRIES AND LEADS</p>



                    @if(empty($permissions) || (isset($permissions['contact_us']) &&
                    ($permissions['contact_us']['create'] ||
                    $permissions['contact_us']['edit'] ||
                    $permissions['contact_us']['view'] ||
                    $permissions['contact_us']['list'] ||
                    $permissions['contact_us']['delete'])))
                    <a href="{{ route('admin.contact_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.contact_list']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/contact-us.svg" alt="">
                        <span class="nav_name">contact-Us</span>
                    </a>
                    @endif


                    @if(empty($permissions) || (isset($permissions['booking']) &&
                    ($permissions['booking']['create'] ||
                    $permissions['booking']['edit'] ||
                    $permissions['booking']['view'] ||
                    $permissions['booking']['list'] ||
                    $permissions['booking']['delete'])))
                    <a href="{{ route('admin.enquiry_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.enquiry_list']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/enquiry.svg" alt="">
                        <span class="nav_name"> Booking </span>
                    </a>
                    @endif

                    @if(empty($permissions) || (isset($permissions['enquiry']) &&
                    ($permissions['enquiry']['create'] ||
                    $permissions['enquiry']['edit'] ||
                    $permissions['enquiry']['view'] ||
                    $permissions['enquiry']['list'] ||
                    $permissions['enquiry']['delete'])))
                    <a href="{{ route('admin.home_enquiry_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.home_enquiry_list']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/enquiry.svg" alt="">
                        <span class="nav_name"> Enquiry </span>
                    </a>
                    @endif
                    @endif








    @if(empty($permissions) ||
    (isset($permissions['programs']) && ($permissions['programs']['create'] || $permissions['programs']['edit'] || $permissions['programs']['delete'] || $permissions['programs']['view'] || $permissions['programs']['list'])) ||
    (isset($permissions['packages_destination']) && ($permissions['packages_destination']['create'] || $permissions['packages_destination']['edit'] || $permissions['packages_destination']['delete'] || $permissions['packages_destination']['view'] || $permissions['packages_destination']['list'])) ||
    (isset($permissions['theme']) && ($permissions['theme']['create'] || $permissions['theme']['edit'] || $permissions['theme']['delete'] || $permissions['theme']['view'] || $permissions['theme']['list'])) ||
    (isset($permissions['customer_package']) && ($permissions['customer_package']['create'] || $permissions['customer_package']['edit'] || $permissions['customer_package']['delete'] || $permissions['customer_package']['view'] || $permissions['customer_package']['list'])))

                    <p class=" nav_link mb-0 text-white fw-bold">PROGRAM & PACKAGES</p>
                    @if(empty($permissions) || (isset($permissions['programs']) &&
                    ($permissions['programs']['create'] ||
                    $permissions['programs']['edit'] ||
                    $permissions['programs']['view'] ||
                    $permissions['programs']['list'] ||
                    $permissions['programs']['delete'])))
                    <a href="{{ route('admin.inclusive_package_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.inclusive_package_list', 'admin.inclusive_package_add_form', 'admin.inclusive_package_edit_form']) ? 'active' : '' }} mb-0 text-white">
                        <img src="/assets/image/dashboard/program.svg" alt="">
                        <span class="nav_name"> Programs </span>
                    </a>
                    @endif

                    @if(empty($permissions) || (isset($permissions['packages_destination']) &&
                    ($permissions['packages_destination']['create'] ||
                    $permissions['packages_destination']['edit'] ||
                    $permissions['packages_destination']['view'] ||
                    $permissions['packages_destination']['list'] ||
                    $permissions['packages_destination']['delete'])))
                    <a href="{{ route('admin.citylist') }}"
                        class="nav_link {{ request()->routeIs(['admin.citylist', 'admin.city_add_form', 'admin.city_edit_form']) ? 'active' : '' }} mb-0 text-white">
                        <img src="/assets/image/dashboard/location-pin.svg" alt="">
                        <span class="nav_name"> Packages Destionation </span>
                    </a>
                    @endif

                    @if(empty($permissions) || (isset($permissions['theme']) &&
                    ($permissions['theme']['create'] ||
                    $permissions['theme']['edit'] ||
                    $permissions['theme']['view'] ||
                    $permissions['theme']['list'] ||
                    $permissions['theme']['delete'])))
                    <a href="{{ route('admin.themes_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.themes_list', 'admin.themes_add_form', 'admin.themes_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/themes.svg" alt="">
                        <span class="nav_name">Theme</span>
                    </a>
                    @endif



                       @if(empty($permissions) || isset($permissions['customer_package']) && ($permissions['customer_package']['create'] || $permissions['customer_package']['edit'] || $permissions['customer_package']['view'] || $permissions['customer_package']['list'] || $permissions['customer_package']['delete']))
                       <a href="{{ route('admin.CustomerPackage_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.CustomerPackage_list', 'admin.CustomerPackage_insert']) ? 'active' : '' }} mb-3 text-white">
                        <img class="customer-package" src="/assets/image/dashboard/customer_package.png"
                            alt="">
                        <span class="nav_name"> Customer Package </span>
                    </a>
                    @endif
                    @endif



                    
                    @if(empty($permissions) ||
                    (isset($permissions['add_stays']) && ($permissions['add_stays']['create'] || $permissions['add_stays']['edit'] || $permissions['add_stays']['delete'] || $permissions['add_stays']['view'] || $permissions['add_stays']['list'])) ||
                    (isset($permissions['stay_enquiry']) && ($permissions['stay_enquiry']['create'] || $permissions['stay_enquiry']['edit'] || $permissions['stay_enquiry']['delete'] || $permissions['stay_enquiry']['view'] || $permissions['stay_enquiry']['list'])) ||
                    (isset($permissions['stays_district']) && ($permissions['stays_district']['create'] || $permissions['stays_district']['edit'] || $permissions['stays_district']['delete'] || $permissions['stays_district']['view'] || $permissions['stays_district']['list']))
                    )
                    <p class=" nav_link text-white fw-bold mb-0">STAYS</p>


                    @if(empty($permissions) || isset($permissions['add_stays']) &&
                    ($permissions['add_stays']['create'] ||
                    $permissions['add_stays']['edit'] ||
                    $permissions['add_stays']['view'] ||
                    $permissions['add_stays']['list'] ||
                    $permissions['add_stays']['delete']))
                    <a href="{{ route('admin.staylist') }}"
                        class="nav_link {{ request()->routeIs(['admin.staylist', 'admin.stays_add_form', 'admin.stay_details_edit_form']) ? 'active' : '' }} mb-0 text-white">
                        <img src="/assets/image/dashboard/stay.png" alt="" class="stay-img">
                        <span class="nav_name"> ADD Stays </span>
                    </a>
                    @endif

                    @if(empty($permissions) || isset($permissions['stay_enquiry']) &&
                    ($permissions['stay_enquiry']['create'] ||
                    $permissions['stay_enquiry']['edit'] ||
                    $permissions['stay_enquiry']['view'] ||
                    $permissions['stay_enquiry']['list'] ||
                    $permissions['stay_enquiry']['delete']))
                    <a href="{{ route('admin.stay_home_enquiry_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.stay_home_enquiry_list']) ? 'active' : '' }} mb-0 text-white">
                        <img src="/assets/image/dashboard/enquiry.svg" alt="">
                        <span class="nav_name"> Stay Enquiry </span>
                    </a>
                    @endif

                    @if(empty($permissions) || isset($permissions['stays_district']) &&
                    ($permissions['stays_district']['create'] ||
                    $permissions['stays_district']['edit'] ||
                    $permissions['stays_district']['view'] ||
                    $permissions['stays_district']['list'] ||
                    $permissions['stays_district']['delete']))
                    <a href="{{ route('admin.staydistrictlist') }}"
                        class="nav_link {{ request()->routeIs(['admin.staydistrictlist']) ? 'active' : '' }} mb-0 text-white">
                        <img src="/assets/image/dashboard/stay.png" alt="" class="stay-img">
                        <span class="nav_name"> Stays District </span>
                    </a>
                    @endif
                    @endif






                    @if(empty($permissions) ||
                    (isset($permissions['amenities']) && ($permissions['amenities']['create'] || $permissions['amenities']['edit'] || $permissions['amenities']['delete'] || $permissions['amenities']['view'] || $permissions['amenities']['list'])) ||
                    (isset($permissions['food_beverage']) && ($permissions['food_beverage']['create'] || $permissions['food_beverage']['edit'] || $permissions['food_beverage']['delete'] || $permissions['food_beverage']['view'] || $permissions['food_beverage']['list'])) ||
                    (isset($permissions['activities']) && ($permissions['activities']['create'] || $permissions['activities']['edit'] || $permissions['activities']['delete'] || $permissions['activities']['view'] || $permissions['activities']['list'])) ||
                    (isset($permissions['safety_features']) && ($permissions['safety_features']['create'] || $permissions['safety_features']['edit'] || $permissions['safety_features']['delete'] || $permissions['safety_features']['view'] || $permissions['safety_features']['list']))
                    )
                    <p class=" nav_link mb-0 text-white fw-bold">AMENITIES </p>

                   
                       @if(empty($permissions) || isset($permissions['amenities']) &&
                        ($permissions['amenities']['create'] ||
                        $permissions['amenities']['edit'] ||
                        $permissions['amenities']['view'] ||
                        $permissions['amenities']['list'] ||
                        $permissions['amenities']['delete']))
                        <a href="{{ route('admin.amenitieslist') }}"
                            class="nav_link {{ request()->routeIs(['admin.amenitieslist', 'admin.amenities_add_form', 'admin.amenities_edit_form']) ? 'active' : '' }} mb-0 text-white">
                            <img src="/assets/image/dashboard/amenities.svg" alt="">
                            <span class="nav_name">Amenities</span>
                        </a>
                        @endif 
                         @if(empty($permissions) ||(isset($permissions['food_beverage']) &&
                        ($permissions['food_beverage']['create'] ||
                        $permissions['food_beverage']['edit'] ||
                        $permissions['food_beverage']['view'] ||
                        $permissions['food_beverage']['list'] ||
                        $permissions['food_beverage']['delete'])))
                        <a href="{{ route('admin.food_beveragelist') }}"
                            class="nav_link {{ request()->routeIs(['admin.food_beveragelist', 'admin.food_beverage_add_form', 'admin.food_beverage_edit_form']) ? 'active' : '' }} mb-0 text-white">
                            <img src="/assets/image/dashboard/fast-food.svg" alt="">
                            <span class="nav_name">Food&Beverage</span>
                        </a>
                        @endif
                        @if(empty($permissions) ||(isset($permissions['activities']) &&
                        ($permissions['activities']['create'] ||
                        $permissions['activities']['edit'] ||
                        $permissions['activities']['view'] ||
                        $permissions['activities']['list'] ||
                        $permissions['activities']['delete'])))
                        <a href="{{ route('admin.activitieslist') }}"
                            class="nav_link {{ request()->routeIs(['admin.activitieslist', 'admin.activities_add_form', 'admin.activities_edit_form']) ? 'active' : '' }} mb-0 text-white">
                            <img src="/assets/image/dashboard/activities.svg" alt="">
                            <span class="nav_name">Activities</span>
                        </a>
                        @endif
                        @if(empty($permissions) ||(isset($permissions['safety_features']) &&
                        ($permissions['safety_features']['create'] ||
                        $permissions['safety_features']['edit'] ||
                        $permissions['safety_features']['view'] ||
                        $permissions['safety_features']['list'] ||
                        $permissions['safety_features']['delete'])))
                        <a href="{{ route('admin.safety_features_list') }}"
                            class="nav_link {{ request()->routeIs(['admin.safety_features_list', 'admin.safety_features_add_form', 'admin.safety_features_edit_form']) ? 'active' : '' }} mb-3 text-white">
                            <img src="/assets/image/dashboard/security.svg" alt="">
                            <span class="nav_name">Safety Features</span>
                        </a>
                    @endif
                    @endif






                    <!-- <a href="{{ route('admin.staydestinationlist') }}"
                        class="nav_link {{ request()->routeIs(['admin.staydestination_add_form', 'admin.staydestinationlist', 'admin.staydestination_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/stay.png" alt="" class="stay-img">
                        <span class="nav_name"> Stays Destination </span>
                    </a> -->

                    @if(empty($permissions) || (isset($permissions['stay_pricing_pc']) && ($permissions['stay_pricing_pc']['create'] || $permissions['stay_pricing_pc']['edit'] || $permissions['stay_pricing_pc']['delete'] || $permissions['stay_pricing_pc']['view'] || $permissions['stay_pricing_pc']['list'])) ||
                    (isset($permissions['cab_pc']) && ($permissions['cab_pc']['create'] || $permissions['cab_pc']['edit'] || $permissions['cab_pc']['delete'] || $permissions['cab_pc']['view'] || $permissions['cab_pc']['list'])) ||
                    (isset($permissions['activity_pc']) && ($permissions['activity_pc']['create'] || $permissions['activity_pc']['edit'] || $permissions['activity_pc']['delete'] || $permissions['activity_pc']['view'] || $permissions['activity_pc']['list'])) ||
                    (isset($permissions['pricing_calculator']) && ($permissions['pricing_calculator']['create'] || $permissions['pricing_calculator']['edit'] || $permissions['pricing_calculator']['delete'] || $permissions['pricing_calculator']['view'] || $permissions['pricing_calculator']['list']))
                    )
                    <p class=" nav_link mb-0 text-white fw-bold">PRICING CALCULATOR </p>
                    @if(empty($permissions) || isset($permissions['stay_pricing_pc']) &&
                    ($permissions['stay_pricing_pc']['create'] ||
                    $permissions['stay_pricing_pc']['edit'] ||
                    $permissions['stay_pricing_pc']['view'] ||
                    $permissions['stay_pricing_pc']['list'] ||
                    $permissions['stay_pricing_pc']['delete']))
                    <a href="{{ route('admin.staypricinglist') }}"
                        class="nav_link {{ request()->routeIs(['admin.staypricing_add_form', 'admin.staypricinglist', 'admin.staypricing_edit_form']) ? 'active' : '' }} mb-0 text-white">
                        <img src="/assets/image/dashboard/stay.png" alt="" class="stay-img">
                        <span class="nav_name"> Stay Pricing(PC) </span>
                    </a>
                    @endif
                    @if(empty($permissions) || isset($permissions['cab_pc']) &&
                    ($permissions['cab_pc']['create'] ||
                    $permissions['cab_pc']['edit'] ||
                    $permissions['cab_pc']['view'] ||
                    $permissions['cab_pc']['list'] ||
                    $permissions['cab_pc']['delete']))
                    <a href="{{ route('admin.cablist') }}"
                        class="nav_link {{ request()->routeIs(['admin.cab_add_form', 'admin.cablist', 'admin.cab_edit_form']) ? 'active' : '' }} mb-0 text-white">
                        <img src="/assets/image/dashboard/stay.png" alt="" class="stay-img">
                        <span class="nav_name"> Cab(PC) </span>
                    </a>
                    @endif
                    @if(empty($permissions) || isset($permissions['activity_pc']) &&
                    ($permissions['activity_pc']['create'] ||
                    $permissions['activity_pc']['edit'] ||
                    $permissions['activity_pc']['view'] ||
                    $permissions['activity_pc']['list'] ||
                    $permissions['activity_pc']['delete']))
                    <a href="{{ route('admin.activitylist') }}"
                        class="nav_link {{ request()->routeIs(['admin.activity_add_form', 'admin.activitylist', 'admin.activity_edit_form']) ? 'active' : '' }} mb-0 text-white">
                        <img src="/assets/image/dashboard/stay.png" alt="" class="stay-img">
                        <span class="nav_name"> Activity(PC) </span>
                    </a>
                    @endif
                    @if(empty($permissions) || isset($permissions['pricing_calculator']) &&
                    ($permissions['pricing_calculator']['create'] ||
                    $permissions['pricing_calculator']['edit'] ||
                    $permissions['pricing_calculator']['view'] ||
                    $permissions['pricing_calculator']['list'] ||
                    $permissions['pricing_calculator']['delete']))
                    <a href="{{ route('admin.pricinglist') }}"
                        class="nav_link {{ request()->routeIs(['admin.pricing_add_form', 'admin.pricinglist', 'admin.pricing_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/stay.png" alt="" class="stay-img">
                        <span class="nav_name"> Pricing Calculator </span>
                    </a>
                    @endif
                    @endif






                    @if(
                    (empty($permissions) || isset($permissions['event_list']) && ($permissions['event_list']['create'] || $permissions['event_list']['edit'] || $permissions['event_list']['delete'] || $permissions['event_list']['view'] || $permissions['event_list']['list'])) ||
                    (isset($permissions['event_registration']) && ($permissions['event_registration']['create'] || $permissions['event_registration']['edit'] || $permissions['event_registration']['delete'] || $permissions['event_registration']['view'] || $permissions['event_registration']['list']))
                    )
                    <p class=" nav_link text-white fw-bold mb-0">EVENTS</p>


                    @if(empty($permissions) || isset($permissions['event_list']) && ($permissions['event_list']['create'] || $permissions['event_list']['edit'] || $permissions['event_list']['view'] || $permissions['event_list']['list'] || $permissions['event_list']['delete']))

                    <a class="nav_link {{ request()->routeIs(['admin.programeventslist', 'admin.programeventsadd', 'admin.programeventedit']) ? 'active' : '' }} mb-0 text-white"
                        href="{{ route('admin.programeventslist') }}">
                        <img src="/assets/image/dashboard/themes.svg" alt="">
                        <span class="nav_name"> Event List </span>
                    </a>
                    @endif


                    @if(empty($permissions) || isset($permissions['event_registration']) && ($permissions['event_registration']['create'] || $permissions['event_registration']['edit'] || $permissions['event_registration']['delete'] || $permissions['event_registration']['view'] || $permissions['event_registration']['list']))
                    <a class="nav_link {{ request()->routeIs(['admin.registereventslist']) ? 'active' : '' }} mb-3 text-white"
                        href="{{ route('admin.registereventslist') }}">
                        <img src="/assets/image/dashboard/themes.svg" alt="">
                        <span class="nav_name"> Event Registration </span>
                    </a>
                    @endif
                    @endif




                    <!-- @if(empty($permissions) || isset($permissions['influencer']) && ($permissions['influencer']['create'] || $permissions['influencer']['edit'] || $permissions['influencer']['view'] || $permissions['influencer']['list'] || $permissions['influencer']['delete']))
                    <a href="{{ route('admin.influencer_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.influencer_list', 'admin.influencer_add_form', 'admin.influencer_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/slider.svg" alt="">
                        <span class="nav_name"> Influencer </span>
                    </a>
                    @endif -->

                    <!-- <a href="{{ route('admin.program_pdf_list') }}" class="nav_link {{ request()->routeIs(['admin.program_pdf_list', 'admin.program_pdf_add_form', 'admin.program_pdf_insert', 'admin.program_pdf_updates', 'admin.program_pdf_delete']) ? 'active' : '' }} mb-3 text-white">
                    <i class="bi bi-filetype-pdf"></i>
                        <span class="nav_name"> pdf </span>
                    </a> -->
                    <!-- @if(empty($permissions) || isset($permissions['slider']) && ($permissions['slider']['create'] || $permissions['slider']['edit'] || $permissions['slider']['view'] || $permissions['slider']['list'] || $permissions['slider']['delete']))
                    <a href="{{ route('admin.slider_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.slider_list', 'admin.slider_add_form', 'admin.slider_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/slider.svg" alt="">
                        <span class="nav_name"> Slider </span>
                    </a>
                    @endif -->

                    <!-- @if(empty($permissions) || isset($permissions['customer_package']) && ($permissions['customer_package']['create'] || $permissions['customer_package']['edit'] || $permissions['customer_package']['view'] || $permissions['customer_package']['list'] || $permissions['customer_package']['delete']))
                    <a href="{{ route('admin.CustomerPackage_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.CustomerPackage_list', 'admin.CustomerPackage_insert']) ? 'active' : '' }} mb-3 text-white">
                        <img class="customer-package" src="/assets/image/dashboard/customer_package.png"
                            alt="">
                        <span class="nav_name"> Customer Package </span>
                    </a>
                    @endif -->

                    {{-- <li class="nav-item dropdown">
                        <a class="nav_link dropdown-toggle {{ request()->routeIs(['admin.programeventslist', 'admin.programeventsadd']) ? 'active' : '' }} mb-3 text-white d-flex align-items-center"
                    href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img src="/assets/image/dashboard/program.svg" alt="Events" class="me-2"
                        width="20" height="20">
                    <span class="nav_name">Events</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs(['admin.programeventslist', 'admin.programeventsadd', 'admin.programeventedit']) ? 'active' : '' }}"
                                href="{{ route('admin.programeventslist') }}">
                                Event List
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs(['admin.registereventslist']) ? 'active' : '' }}"
                                href="{{ route('admin.registereventslist') }}">
                                Event Registration
                            </a>
                        </li>
                    </ul>
                    </li> --}}




@if(
    empty($permissions) ||
    (isset($permissions['review']) && ($permissions['review']['create'] || $permissions['review']['edit'] || $permissions['review']['delete'] || $permissions['review']['view'] || $permissions['review']['list'])) ||
    (isset($permissions['stay_review']) && ($permissions['stay_review']['create'] || $permissions['stay_review']['edit'] || $permissions['stay_review']['delete'] || $permissions['stay_review']['view'] || $permissions['stay_review']['list'])) ||
    (isset($permissions['wishlist']) && ($permissions['wishlist']['create'] || $permissions['wishlist']['edit'] || $permissions['wishlist']['delete'] || $permissions['wishlist']['view'] || $permissions['wishlist']['list']))
)
<p class="nav_link mb-0 text-white fw-bold">REVIEW & ENGAGEMENT</p>




                    @if(empty($permissions) || isset($permissions['review']) && ($permissions['review']['create'] || $permissions['review']['edit'] || $permissions['review']['view'] || $permissions['review']['list'] || $permissions['review']['delete']))
                    <a href="{{ route('admin.client_review_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.client_review_list', 'admin.client_review_add_form', 'admin.client_review_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/review.svg" alt="">
                        <span class="nav_name"> Review </span>
                    </a>
                    @endif


                    @if(empty($permissions) || isset($permissions['stay_review']) && ($permissions['stay_review']['create'] || $permissions['stay_review']['edit'] || $permissions['stay_review']['view'] || $permissions['stay_review']['list'] || $permissions['stay_review']['delete']))
                    <a href="{{ route('admin.stay_review_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.stay_review_list', 'admin.stay_review_add_form', 'admin.stay_review_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/review.svg" alt="">
                        <span class="nav_name"> Stay Review </span>
                    </a>
                    @endif


                    @if(empty($permissions) || isset($permissions['wishlist']) && ($permissions['wishlist']['create'] || $permissions['wishlist']['edit'] || $permissions['wishlist']['view'] || $permissions['wishlist']['list'] || $permissions['wishlist']['delete']))
                    <a href="{{ route('admin.wish_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.wish_list', 'admin.wishlist_add_form', 'admin.wishlist_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/wishlist.svg" alt="">
                        <span class="nav_name"> Wishlist</span>
                    </a>
                    @endif
                    @endif








@if(empty($permissions) ||
    (isset($permissions['faq']) && ($permissions['faq']['create'] || $permissions['faq']['edit'] || $permissions['faq']['delete'] || $permissions['faq']['view'] || $permissions['faq']['list'])) ||
    (isset($permissions['general_setting']) && ($permissions['general_setting']['create'] || $permissions['general_setting']['edit'] || $permissions['general_setting']['delete'] || $permissions['general_setting']['view'] || $permissions['general_setting']['list'])) ||
    (isset($permissions['mail_template']) && ($permissions['mail_template']['create'] || $permissions['mail_template']['edit'] || $permissions['mail_template']['delete'] || $permissions['mail_template']['view'] || $permissions['mail_template']['list'])))
<p class="nav_link mb-0 text-white fw-bold">SYSTEM SETTINGS</p>


                    @if(empty($permissions) || isset($permissions['faq']) && ($permissions['faq']['create'] || $permissions['faq']['edit'] || $permissions['faq']['view'] || $permissions['faq']['list'] || $permissions['faq']['delete']))
                    <a href="{{ route('admin.faqlist') }}"
                        class="nav_link {{ request()->routeIs(['admin.faqlist', 'admin.faq_add_form', 'admin.faq_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/faq.svg" alt="">
                        <span class="nav_name">FAQ</span>
                    </a>
                    @endif


                    
                    @if(empty($permissions) || isset($permissions['general_setting']) && ($permissions['general_setting']['create'] || $permissions['general_setting']['edit'] || $permissions['general_setting']['view'] || $permissions['general_setting']['list'] || $permissions['general_setting']['delete']))
                    <a href="{{ route('admin.settings_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.settings_list']) ? 'active' : '' }} mb-3">
                        <img src="/assets/image/dashboard/settings.svg" alt="">
                        <span class="nav_name" style="color: #fff;"> General Setting </span>
                    </a>
                    @endif


                    @if(empty($permissions) || isset($permissions['mail_template']) && ($permissions['mail_template']['create'] || $permissions['mail_template']['edit'] || $permissions['mail_template']['view'] || $permissions['mail_template']['list'] || $permissions['mail_template']['delete']))
                    <a href="{{ route('admin.mailtemplatelist') }}"
                        class="nav_link {{ request()->routeIs(['admin.mailtemplatelist', 'admin.mailtemplateadd', 'admin.mailtemplateedit']) ? 'active' : '' }} mb-3">
                        <img src="/assets/image/dashboard/settings.svg" alt="">
                        <span class="nav_name" style="color: #fff;"> Mail Template </span>
                    </a>
                    @endif
                    @endif


<!-- @if(empty($permissions) ||
    (isset($permissions['faq']) && ($permissions['faq']['create'] || $permissions['faq']['edit'] || $permissions['faq']['delete'] || $permissions['faq']['view'] || $permissions['faq']['list'])) ||
    (isset($permissions['general_setting']) && ($permissions['general_setting']['create'] || $permissions['general_setting']['edit'] || $permissions['general_setting']['delete'] || $permissions['general_setting']['view'] || $permissions['general_setting']['list'])) ||
    (isset($permissions['mail_template']) && ($permissions['mail_template']['create'] || $permissions['mail_template']['edit'] || $permissions['mail_template']['delete'] || $permissions['mail_template']['view'] || $permissions['mail_template']['list'])) ||
    (isset($permissions['google_analytics']) && ($permissions['google_analytics']['create'] || $permissions['google_analytics']['edit'] || $permissions['google_analytics']['delete'] || $permissions['google_analytics']['view'] || $permissions['google_analytics']['list']))
)

<p class="nav_link mb-0 text-white fw-bold">SYSTEM SETTINGS</p>

@endif -->


                    {{--
                    <a href="{{ route('admin.group_tour_list') }}" class="nav_link {{ request()->routeIs(['admin.group_tour_list', 'admin.group_tour_add_form', 'admin.group_tour_edit_form']) ? 'active' : '' }} mb-3 text-white">
                    <img src="/assets/image/dashboard/program.svg" alt="">
                    <span class="nav_name"> Group Tour Packages </span>
                    </a>
                    <a href="{{ route('admin.destination_cat_list') }}" class="nav_link {{ request()->routeIs(['admin.destination_cat_list', 'admin.destination_cat_add_form', 'admin.destination_cat_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/destination_cat.svg" alt="">
                        <span class="nav_name"> Destination Category</span>
                    </a>
                    <a href="{{ route('admin.themes_cat_list') }}" class="nav_link {{ request()->routeIs(['admin.themes_cat_list', 'admin.themes_cat_add_form', 'admin.themes_cat_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/theme_cat.svg" alt="">
                        <span class="nav_name">Theme Category</span>
                    </a>
                    <a href="{{ route('admin.podcastlist') }}" class="nav_link {{ request()->routeIs(['admin.podcastlist', 'admin.podcast_add_form', 'admin.podcast_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/microphone.svg" alt="">
                        <span class="nav_name"> Podcast</span>
                    </a>
                    <a href="{{ route('admin.geo_feature_list') }}" class="nav_link {{ request()->routeIs(['admin.geo_feature_list', 'admin.geo_feature_add_form', 'admin.geo_feature_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/earth-globe.svg" alt="">
                        <span class="nav_name">Geo Features</span>
                    </a>
                    <a href="{{ route('admin.blog_list') }}" class="nav_link {{ request()->routeIs(['admin.blog_list', 'admin.blog_add_form', 'admin.blog_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/blog.svg" alt="">
                        <span class="nav_name"> Blog </span>
                    </a>
                    <a href="{{ route('admin.destinationlist') }}" class="nav_link {{ request()->routeIs(['admin.destinationlist', 'admin.destination_add_form', 'admin.destination_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/location-pin.svg" alt="">
                        <span class="nav_name"> Destination-Old</span>
                    </a>
                    <a href="{{ route('admin.inclusive_package_list') }}" class="nav_link {{ request()->routeIs(['admin.inclusive_package_list', 'admin.inclusive_package_add_form', 'admin.inclusive_package_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/innerpece_eventlist_icon.svg" alt="">
                        <span class="nav_name"> All-Inclusive Package </span>
                    </a>
                    <a href="{{ route('admin.property_list') }}" class="nav_link {{ request()->routeIs(['admin.property_list', 'admin.property_add_form', 'admin.property_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/innerpece_eventlist_icon.svg" alt="">
                        <span class="nav_name"> Property </span>
                    </a>
                    <a href="{{ route('admin.inclusive_package_list') }}" class="nav_link {{ request()->routeIs(['admin.inclusive_package_list', 'admin.inclusive_package_add_form', 'admin.inclusive_package_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/innerpece_eventlist_icon.svg" alt="">
                        <span class="nav_name"> Packages </span>
                    </a>
                    <a href="{{ route('admin.eventList') }}" class="nav_link {{ request()->routeIs(['admin.eventList', 'admin.event_add_form', 'admin.company_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/innerpece_eventlist_icon.svg" alt="">
                        <span class="nav_name"> Events </span>
                    </a>
                    <a href="{{ route('admin.eventList') }}" class="nav_link {{ request()->routeIs('admin.eventList') ? 'active' : '' }} mb-3">
                        <img src="/assets/image/dashboard/innerpece_eventlist_icon.svg" alt="">
                        <span class="nav_name">Event Listing</span>
                    </a>
                    <a href="{{ route('admin.event_add_form') }}" class="nav_link {{ request()->routeIs('admin.event_add_form') ? 'active' : '' }} mb-3">
                        <img src="/assets/image/dashboard/innerpece_addevent_icon.svg" alt="">
                        <span class="nav_name">Add Event</span>
                    </a>
                    <a href="{{ route('admin.packageList') }}" class="nav_link {{ request()->routeIs('admin.packageList') ? 'active' : '' }} mb-3">
                        <img src="/assets/image/dashboard/innerpece_package_icon.svg" alt="">
                        <span class="nav_name">Package Listing</span>
                    </a>
                    <a href="{{ route('admin.package_add_form') }}" class="nav_link {{ request()->routeIs('admin.package_add_form') ? 'active' : '' }} mb-3">
                        <img src="/assets/image/dashboard/innerpece_addpackage_icon.svg" alt="">
                        <span class="nav_name">Add Package</span>
                    </a> <a href="innerpeace_bulkbooking.html" class="nav_link mb-3">
                        <img src="/assets/image/dashboard/innerpece_bulkbooking_icon.svg" alt="">
                        <span class="nav_name">Bulk Booking Listing</span>
                    </a>
                    <a href="innerpeace_addblog.html" class="nav_link mb-3">
                        <img src="/assets/image/dashboard/innerpece_addblog_icon.svg" alt="">
                        <span class="nav_name">Add Blog</span>
                    </a>
                    <a href="{{ route('admin.program_list') }}" class="nav_link {{ request()->routeIs(['admin.program_list', 'admin.program_add_form', 'admin.program_edit_form']) ? 'active' : '' }} mb-3">
                        <img src="/assets/image/dashboard/innerpece_addevent_icon.svg" alt="">
                        <span class="nav_name"> Programs </span>
                    </a>
                    --}}

                    <!-- <a href="{{ route('admin.user_list') }}" class="nav_link {{ request()->routeIs(['admin.user_list', 'admin.user_add_form', 'admin.user_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/user.svg" alt="">
                        <span class="nav_name"> User Registration </span>
                    </a>
                    <a href="{{ route('admin.contact_list') }}" class="nav_link {{ request()->routeIs(['admin.contact_list']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/contact-us.svg" alt="">
                        <span class="nav_name">contact-Us</span>
                    </a>
                    <a href="{{ route('admin.enquiry_list') }}" class="nav_link {{ request()->routeIs(['admin.enquiry_list']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/enquiry.svg" alt="">
                        <span class="nav_name"> Enquiries </span>
                    </a> -->
                    <!-- <a href="{{ route('admin.profile_list') }}" class="nav_link {{ request()->routeIs(['admin.profile_list', 'admin.profile_add_form', 'admin.profile_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/myprofile.svg" alt="">
                        <span class="nav_name">My Profile</span>
                    </a> -->



                    <!-- @if(empty($permissions) || isset($permissions['google_analytics']) && ($permissions['google_analytics']['list']))
                    <a href="{{ route('analytics.index') }}"
                        class="nav_link {{ request()->routeIs(['analytics.index']) ? 'active' : '' }} mb-3">
                        <img src="/assets/image/dashboard/settings.svg" alt="">
                        <span class="nav_name" style="color: #fff;"> Google Analytic </span>
                    </a>
                    @endif -->


                    <!-- @if(empty($permissions) || isset($permissions['role']) && ($permissions['role']['create'] || $permissions['role']['edit'] || $permissions['role']['view'] || $permissions['role']['list'] || $permissions['role']['delete']))
                    <a href="{{ route('admin.role_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.role_list','admin.role_add_form', 'admin.role_edit_form']) ? 'active' : '' }} mb-3">
                        <img src="/assets/image/dashboard/settings.svg" alt="">
                        <span class="nav_name" style="color: #fff;"> Role </span>
                    </a>
                    @endif -->



                    <!-- @if(empty($permissions) || isset($permissions['admin_user']) && ($permissions['admin_user']['create'] || $permissions['admin_user']['edit'] || $permissions['admin_user']['view'] || $permissions['admin_user']['list'] || $permissions['admin_user']['delete']))
                    <a href="{{ route('admin.admin_user_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.admin_user_list', 'admin.admin_user_add_form', 'admin.admin_user_edit_form']) ? 'active' : '' }} mb-3">
                        <img src="/assets/image/dashboard/settings.svg" alt="">
                        <span class="nav_name" style="color: #fff;"> Admin User </span>
                    </a>
                    @endif -->

                    <!-- @if(empty($permissions) ||isset($permissions['user_permission']) && ($permissions['user_permission']['create'] || $permissions['user_permission']['edit'] || $permissions['user_permission']['view'] || $permissions['user_permission']['list'] || $permissions['user_permission']['delete']))
                    <a href="{{ route('admin.user_permission_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.user_permission_list', 'admin.user_permission_add_form', 'admin.user_permission_edit_form']) ? 'active' : '' }} mb-3">
                        <img src="/assets/image/dashboard/settings.svg" alt="">
                        <span class="nav_name" style="color: #fff;"> User Permission </span>
                    </a>
                    @endif -->



                      <!-- @if(empty($permissions) || isset($permissions['influencer']) && ($permissions['influencer']['create'] || $permissions['influencer']['edit'] || $permissions['influencer']['view'] || $permissions['influencer']['list'] || $permissions['influencer']['delete']))
                    <a href="{{ route('admin.influencer_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.influencer_list', 'admin.influencer_add_form', 'admin.influencer_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/slider.svg" alt="">
                        <span class="nav_name"> Influencer </span>
                    </a>
                    @endif -->

                    <!-- <a href="{{ route('admin.program_pdf_list') }}" class="nav_link {{ request()->routeIs(['admin.program_pdf_list', 'admin.program_pdf_add_form', 'admin.program_pdf_insert', 'admin.program_pdf_updates', 'admin.program_pdf_delete']) ? 'active' : '' }} mb-3 text-white">
                    <i class="bi bi-filetype-pdf"></i>
                        <span class="nav_name"> pdf </span>
                    </a> -->
                    <!-- @if(empty($permissions) || isset($permissions['slider']) && ($permissions['slider']['create'] || $permissions['slider']['edit'] || $permissions['slider']['view'] || $permissions['slider']['list'] || $permissions['slider']['delete']))
                    <a href="{{ route('admin.slider_list') }}"
                        class="nav_link {{ request()->routeIs(['admin.slider_list', 'admin.slider_add_form', 'admin.slider_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/slider.svg" alt="">
                        <span class="nav_name"> Slider </span>
                    </a>
                    @endif -->



                    <div class="profile-content mb-4">
                        <a href="{{ route('admin.logout') }}" class="nav_link mb-5 ">
                            <img class="" src="/assets/image/dashboard/turn-off.svg" alt="">
                            <span class="nav_name logout-menu">Logout</span>
                        </a>
                    </div>


                    {{-- <a class="nav_link {{ request()->routeIs(['admin.faqlist', 'admin.faq_add_form', 'admin.faq_edit_form']) ? 'active' : '' }} mb-3">
                    <span class="nav_name"></span>
                    </a> --}}
                </div>
            </div>
            <hr>

        </nav>
    </div>



    <!-- NAVBAR SEC END -->
    @yield('content')
    @yield('modal')
    
    <footer>


        <div class="row">
            <div class="footer">

                <!-- <ul class="d-flex justify-content-end">
                <li class="nav-item px-3">
                    <a class="nav-link text-white" aria-current="page" href="#">Privacy & Policy</a>
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link  text-white" href="#">Licensing</a>
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link  text-white" href="#">Instruction</a>
                </li>
            </ul> -->
            </div>
        </div>
    </footer>

    @push('scripts')
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
        </script> -->
    <script src="/assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> -->
    <script src="/assets/js/toastr.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script> -->
    <script src="/assets/js/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <!-- <script src="/assets/js/summernote-bs4.min.js"></script> -->
    <script src="/assets/js/developer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        const tableBody = document.getElementById('tableBody');
        // Function to filter rows by status
        function filterTable(status) {
            const rows = Array.from(tableBody.querySelectorAll('tr'));

            // Show/hide rows based on status
            rows.forEach(row => {
                if (row.getAttribute('status') === status || status === 'All') {
                    row.style.display = ''; // Show row
                } else {
                    row.style.display = 'none'; // Hide row
                }
            });
        }

        // Add event listeners for the filter arrows
        document.getElementById('filterActive').addEventListener('click', () => {
            filterTable('Active');
        });

        document.getElementById('filterInactive').addEventListener('click', () => {
            filterTable('Inactive');
        });

        //     function updateTime() {
        //     const now = new Date();

        //     const options = {
        //         weekday: 'short',   // Short day like "Mon", "Tue"
        //         year: 'numeric',
        //         month: 'short',
        //         day: 'numeric',
        //         hour: 'numeric',
        //         minute: 'numeric',
        //         hour12: true
        //     };

        //     const timeString = now.toLocaleString('en-US', options);
        //     document.getElementById('liveTime').textContent = timeString;
        // }

        // setInterval(updateTime, 1000);
        // updateTime();
    </script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    @endpush
    @stack('scripts')

</body>
</html>