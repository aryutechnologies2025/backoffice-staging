<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
    <title>{{ $settings->meta_title ?? 'Inner Pece' }}</title>
    <link rel="icon" href="{{ $settings->fav_icon ? asset($settings->fav_icon) : '' }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="/assets/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="/assets/css/admin_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
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
  <!-- Include DataTables CSS and JS -->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

   
    @yield('scripts')
    @yield('style')
    @stack('js')
</head>

<body id="body-pd">
    
    <div class="row ">
        <header class="header py-5" id="header">
            <div class="col-lg-3 text-start" id="navbarNav">
                <div class="header_toggle mt-2">
                    <img id="header-toggle" src="/assets/image/dashboard/innerpece_toogle_icon.svg" alt="">
                </div>
            </div>
           
            <!-- <div class="col-lg-3 text-end pt-3">
                <a href="#"><i class="fa fa-bell text-white px-4 mt-3" style="font-size:30px"></i></a>
                <img class="" style="width: 20%;" src="/assets/image/dashboard/innerpece_admin_img.png" alt="">
            </div> -->
            <div class="col-lg-3 text-end pt-3">
            <b class="rounded-circle" style="width: 10%; height: auto;" src="{{ session('admin_name') ? asset(session('admin_name')) : '/assets/image/dashboard/innerpece_admin_img.png' }}" alt="admin_name"></b>
            <span class="text-white">{{ session('admin_name') }}</span> 
            <br>   
            <b class="rounded-circle" style="width: 10%; height: auto;" src="{{ session('admin_email') ? asset(session('admin_email')) : '/assets/image/dashboard/innerpece_admin_img.png' }}" alt="admin_email"></b>
                <span class="text-white">{{ session('admin_email') }}</span>
            </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <div class="nav_list ">
                    <img class="pt-4 px-2 mb-5" style="width:80%;" src="{{ $settings->footer_logo ? asset($settings->footer_logo) : '/assets/image/login/inner_pece_logo.png' }}" alt="">
                    <a href="{{ route('admin.dashboard') }}" class="nav_link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/Dashboard.svg" alt="">
                        <span class="nav_name">Dashboard</span>
                    </a>
                    
                    <a href="{{ route('admin.user_list') }}" class="nav_link {{ request()->routeIs(['admin.user_list', 'admin.user_add_form', 'admin.user_edit_form']) ? 'active' : ''}} mb-3 text-white">
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
                    </a>
                    <a href="{{ route('admin.slider_list') }}" class="nav_link {{ request()->routeIs(['admin.slider_list', 'admin.slider_add_form', 'admin.slider_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/slider.svg" alt="">
                        <span class="nav_name"> Slider </span>
                    </a>
                    <a href="{{ route('admin.themes_list') }}" class="nav_link {{ request()->routeIs(['admin.themes_list', 'admin.themes_add_form', 'admin.themes_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/themes.svg" alt="">
                        <span class="nav_name">Theme</span>
                    </a>
                    <a href="{{ route('admin.citylist') }}" class="nav_link {{ request()->routeIs(['admin.citylist', 'admin.city_add_form', 'admin.city_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/location-pin.svg" alt="">
                        <span class="nav_name"> Destination </span>
                    </a>
                    <a href="{{ route('admin.inclusive_package_list') }}" class="nav_link {{ request()->routeIs(['admin.inclusive_package_list', 'admin.inclusive_package_add_form', 'admin.inclusive_package_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/program.svg" alt="">
                        <span class="nav_name"> Programs </span>
                    </a>
                    <a href="{{ route('admin.address_list') }}" class="nav_link {{ request()->routeIs(['admin.address_list', 'admin.address_add_form', 'admin.address_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/program.svg" alt="">
                        <span class="nav_name"> Address </span>
                    </a>
                    <a href="{{ route('admin.client_review_list') }}" class="nav_link {{ request()->routeIs(['admin.client_review_list', 'admin.client_review_add_form', 'admin.client_review_edit_form']) ? 'active' : ''}} mb-3 text-white">
                        <img src="/assets/image/dashboard/review.svg" alt="">
                        <span class="nav_name"> Client Review </span>
                    </a>
                    <a href="{{ route('admin.wish_list') }}" class="nav_link {{ request()->routeIs(['admin.wish_list', 'admin.wishlist_add_form', 'admin.wishlist_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/wishlist.svg" alt="">
                        <span class="nav_name"> Wishlist</span>
                    </a>
                 
                    <a href="{{ route('admin.faqlist') }}" class="nav_link {{ request()->routeIs(['admin.faqlist', 'admin.faq_add_form', 'admin.faq_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/faq.svg" alt="">
                        <span class="nav_name">FAQ</span>
                    </a>
                   
                    <a href="{{ route('admin.amenitieslist') }}" class="nav_link {{ request()->routeIs(['admin.amenitieslist', 'admin.amenities_add_form', 'admin.amenities_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/amenities.svg" alt="">
                        <span class="nav_name">Amenities</span>
                    </a>
                    <a href="{{ route('admin.food_beveragelist') }}" class="nav_link {{ request()->routeIs(['admin.food_beveragelist', 'admin.food_beverage_add_form', 'admin.food_beverage_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/fast-food.svg" alt="">
                        <span class="nav_name">Food&Beverage</span>
                    </a>
                    <a href="{{ route('admin.activitieslist') }}" class="nav_link {{ request()->routeIs(['admin.activitieslist', 'admin.activities_add_form', 'admin.activities_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/activities.svg" alt="">
                        <span class="nav_name">Activities</span>
                    </a>
                    <a href="{{ route('admin.safety_features_list') }}" class="nav_link {{ request()->routeIs(['admin.safety_features_list', 'admin.safety_features_add_form', 'admin.safety_features_edit_form']) ? 'active' : '' }} mb-3 text-white">
                        <img src="/assets/image/dashboard/security.svg" alt="">
                        <span class="nav_name">Safety Features</span>
                    </a>
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

                    <!-- <a href="{{ route('admin.user_list') }}" class="nav_link {{ request()->routeIs(['admin.user_list', 'admin.user_add_form', 'admin.user_edit_form']) ? 'active' : ''}} mb-3 text-white">
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

                    <a href="{{ route('admin.settings_list') }}" class="nav_link {{ request()->routeIs(['admin.settings_list']) ? 'active' : '' }} mb-3">
                        <img src="/assets/image/dashboard/settings.svg" alt="">
                        <span class="nav_name" style="color: #fff;"> General Setting </span>
                    </a>
                    <div class="profile-content">
                    <a href="{{ route('admin.logout') }}" class="nav_link mb-3 ">
                        <img src="/assets/image/dashboard/turn-off.svg" alt="">
                        <span class="nav_name logout-menu"   >Logout</span>
                    </a>
                </div>
                    

                    {{-- <a class="nav_link {{ request()->routeIs(['admin.faqlist', 'admin.faq_add_form', 'admin.faq_edit_form']) ? 'active' : '' }} mb-3">
                        <span class="nav_name"></span>
                    </a> --}}
                </div>
            </div>
            <hr>
            <!-- <div class="profile-details">
                <div class="profile-content">
                    <a href="{{ route('admin.logout') }}" class="nav_link mb-3">
                        <img src="/assets/image/dashboard/turn-off.svg" alt="">
                        <span class="nav_name logout-menu">Logout</span>
                    </a>
                </div>
            </div> -->
        </nav>
    </div>
    <!-- SIDE BAR END -->
    <!-- NAVBAR SEC -->
    <!-- <div class="row">
        <div class="list-body">
            <nav class="flex align-center py-3">

                <div class="col-lg-3 text-end mt-3"><i class='bx bx-menu'></i></div>
                
                <div class="col-lg-5 text-end mt-2"><i class="bi bi-bell-fill"></i></div>

                <div class="col-lg-1">
                    <div class="d-flex justify-content-end mt-2">
                        <button class="dashboard-nav-btn"><img class="dashboard-nav-btnimg" src="/assets/image/dashboard/dashboard_login.png" alt=""> {{ ucfirst(session('admin_role'))  }}</button>
                    </div>
                </div>
            </nav>
        </div>
    </div> -->


    <!-- NAVBAR SEC END -->
    @yield('content')
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
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script> -->
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        $(".sbmtBtn").click(function(evt) {

        });
        $(document).ready(function() {
            $(".sbmtBtn").click(function(evt) {
                // if ($('#form_valid').valid()) {
                //     $('.sbmtBtn').attr("disabled", true);
                //     $('#program_description').val($('#summernote1').summernote('code'));
                //     $('#address').val($('#summernote2').summernote('code'));
                //     $('#plan_description').val($('#summernote3').summernote('code'));
                //     $('#important_info').val($('#summernote4').summernote('code'));
                //     $('#program_inclusion').val($('#summernote5').summernote('code'));
                //     $('#break_fast').val($('#summernote6').summernote('code'));
                //     $('#lunch').val($('#summernote7').summernote('code'));
                //     $('#dinner').val($('#summernote8').summernote('code'));
                //     $('#client_review').val($('#summernote').summernote('code'));
                //     $('#form_valid').submit();
                // }
                if ($('#form_valid').valid()) {
                    $('.sbmtBtn').attr("disabled", true);

                    // Iterate over each Summernote editor and set the corresponding hidden input value
                    $('#plan-container').find('.plan-item').each(function(index, item) {
                        const planDescription = $(item).find('.note-editable').html(); // Get the Summernote HTML content
                        $(item).find('input[name="plan_description[]"]').val(planDescription);
                    });

                    // Set other Summernote fields
                    $('#program_description').val($('#summernote1').summernote('code'));
                    $('#address').val($('#summernote2').summernote('code'));
                    $('#plan_description').val($('#summernote3').summernote('code'));
                    $('#important_info').val($('#summernote4').summernote('code'));
                    $('#program_inclusion').val($('#summernote5').summernote('code'));
                    $('#break_fast').val($('#summernote6').summernote('code'));
                    $('#lunch').val($('#summernote7').summernote('code'));
                    $('#dinner').val($('#summernote8').summernote('code'));
                    $('#client_review').val($('#summernote').summernote('code'));

                    $('#form_valid').submit();
                }
            });
            @if(session('success'))
            toastr.success("{{ session('success') }}");
            @endif
            @if($errors->any())
            @foreach($errors->all() as $error)
            toastr.error("{{ $error }}");
            @endforeach
            @endif
        });



        
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

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    @endpush
    @stack('scripts')
</body>

</html>