@extends('layouts.app')
@section('style')
<!-- Vendor Fonts -->
<link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}?v={{ time() }}" />
<link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}?v={{ time() }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />


<!-- DataTables CSS -->
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />

@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-6">
        <div class="col-12">
            <div class="card tour-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">


                        <div class="content-left">
                            <div class="d-flex gap-3">
                                <div class="current-visitors-singleline" id="current-visitors-container">
                                    <span class="online-indicator"></span>
                                    <span id="current-visitors">{{ $currentVisitors }}</span>
                                    <span class="d-none d-sm-inline">current visitors</span>
                                </div>
                            </div>
                        </div>

                        <div class="content-right">
                            <form id="dateRangeForm" method="GET">
                                <input type="hidden" name="date_range" id="dateRangeInput" value="{{ $dateRange ?? 'today' }}">

                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                        id="dateRangeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="dateRangeText">{{ $selectedRange ?? 'Today' }}</span>
                                    </button>

                                    <ul class="dropdown-menu" aria-labelledby="dateRangeDropdown" style="min-width: 200px;">
                                        <li><a class="dropdown-item range-option" href="javascript:void(0)" data-range="today">Today</a></li>
                                        <li><a class="dropdown-item range-option" href="javascript:void(0)" data-range="yesterday">Yesterday</a></li>
                                        <li><a class="dropdown-item range-option" href="javascript:void(0)" data-range="realtime">Realtime</a></li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li><a class="dropdown-item range-option" href="javascript:void(0)" data-range="last_7_days">Last 7 days</a></li>
                                        <li><a class="dropdown-item range-option" href="javascript:void(0)" data-range="last_28_days">Last 28 days</a></li>
                                        <li><a class="dropdown-item range-option" href="javascript:void(0)" data-range="last_91_days">Last 90 days</a></li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li><a class="dropdown-item range-option" href="javascript:void(0)" data-range="month_to_date">This Month</a></li>
                                        <li><a class="dropdown-item range-option" href="javascript:void(0)" data-range="last_month">Last Month</a></li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li><a class="dropdown-item range-option" href="javascript:void(0)" data-range="year_to_date">This Year</a></li>
                                        <li><a class="dropdown-item range-option" href="javascript:void(0)" data-range="last_12_months">Last 12 Months</a></li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li><a class="dropdown-item range-option" href="javascript:void(0)" data-range="all_time">All Time</a></li>
                                        <li><a class="dropdown-item range-option" href="javascript:void(0)" data-range="custom_range">Custom Range</a></li>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="limitedDataAlert" class="alert alert-info alert-dismissible fade show mt-3" role="alert" style="display: none;">
        <i class="bx bx-info-circle me-2"></i>
        <strong>Limited Data Available:</strong> Google Analytics is still processing data for today. Summary
        metrics
        are shown,
        but detailed breakdowns and historical charts may be incomplete. Try selecting "Yesterday" for complete
        data.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <div>

        @if(!empty($error))
        <!-- Error Card -->
        <div class="row" id="error-container">
            <div class="col-12">
                <div class="card py-5 px-5">
                    <div class="card-body text-center ">
                        <div id="analytics-content">
                            <div class="analytics-error" role="alert">
                                <i class="bx bx-info-circle fs-2 text-danger mb-3"></i>
                                <h5 class="text-danger">Error Loading Data</h5>
                                <div class="mb-0">{{ $error }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Error Card -->
        @endif
        <!-- Loading Card -->
        <div class="row" id="loading-container">
            <div class="col-12">
                <div class="card py-5 px-5">
                    <div class="card-body text-center ">
                        <div id="analytics-content">
                            <div class="analytics-loading" role="alert">
                                <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                                <h5 class="text-primary">Loading Analytics Data...</h5>
                                <div class="mb-0">Please wait while we fetch your data</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Loading Card -->

        <div id="dashboard-content" style="display: none;">
            <!-- Stats Cards -->
            @if($dateRange ==='realtime')
            <div class="row g-6 mb-6">
                <div class="col-sm-6 col-xl-6">
                    <div class="stats-card clickable-card" data-metric="unique_visitors" data-active="true">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">UNIQUE VISITORS(30 MIN)</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2" id="uniqueVisitors">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-6">
                    <div class="stats-card clickable-card" data-metric="total_pageviews">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">TOTAL PAGEVIEWS(30 MIN)</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @else
            <div class="row g-6 mb-6">
                <div class="col-6 col-xl-2">
                    <div class="stats-card clickable-card" data-metric="unique_visitors" data-active="true">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="metrics-label">UNIQUE VISITORS</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2 metrics-value" id="uniqueVisitors">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-2">
                    <div class="stats-card clickable-card" data-metric="total_visits">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="metrics-label">TOTAL SESSIONS</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2 metrics-value">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-2">
                    <div class="stats-card clickable-card" data-metric="total_pageviews">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="metrics-label">TOTAL PAGEVIEWS</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2 metrics-value">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-2">
                    <div class="stats-card clickable-card" data-metric="views_per_visit">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="metrics-label">VIEWS PER VISIT</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2 metrics-value">0.00</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-2">
                    <div class="stats-card clickable-card" data-metric="bounce_rate">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="metrics-label">BOUNCE RATE</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2 metrics-value">0.0%</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-2">
                    <div class="stats-card clickable-card" data-metric="avg_session_duration">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="metrics-label">VISIT DURATION</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2 metrics-value">0m 00s</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!-- / Stats Cards -->

            <!-- Chart Cards -->
            <div class="row g-6 mb-6">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title text-heading mb-0" id="chartTitle">Unique Visitors</h5>
                            <div class="granularity-dropdown">
                                <select id="granularitySelect" class="form-select form-select-sm" style="width: auto;">
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="lineChart"
                                data-categories='[]'
                                data-visitors='[]'
                                data-y-max="100"
                                data-total-visitors="0"
                                data-range="last_7_days"
                                data-metric="unique_visitors">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Chart Cards  -->
            @if($dateRange ==='realtime')
            <div class="row g-6 mb-6">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Realtime activity</h5>
                        </div>
                        <div class="card-datatable table-responsive px-5">
                            <table class="datatable-table table table-bordered" id="realtimeTable">
                                <thead>
                                    <tr>
                                        <th>COUNTRY</th>
                                        <th>DEVICE TYPE</th>
                                        <th>PAGE</th>
                                        <th>DATE</th>
                                        <th>TIME</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- First Row: Top Channels & Top Pages -->
            <div class="row g-6 mb-6">
                <!-- Top Channels -->
                <div class="col-md-6">
                    <div class="card custom-h-card">
                        <div class="card-header">
                            <h5 class="card-title">Top Channels</h5>
                            <div class="section-tabs">
                                <button type="button" class="section-tabs active" id="Channels-tab">
                                    Channels
                                </button>
                                <button type="button" class="section-tabs" id="Sources-tab">
                                    Sources
                                </button>
                            </div>
                        </div>
                        <hr class="my-0" />
                        <div class="card-body overflow-y-auto">
                            <div class="tab-content p-0">
                                <div class="tab-pane fade show active cursor-pointer w-100" id="navs-pills-channels" role="tabpanel">
                                </div>
                                <div class="tab-pane fade cursor-pointer w-100" id="navs-pills-sources" role="tabpanel">
                                </div>
                            </div>
                        </div>
                        <hr class="my-0" />
                        <div class="card-footer text-center">
                            <a href="#" id="top-channels-details">
                                Details
                            </a>
                        </div>
                    </div>
                </div>
                <!-- / Top Channels -->

                <!-- Top Pages -->
                <div class="col-md-6">
                    <div class="card custom-h-card">
                        <div class="card-header">
                            <h5 class="card-title">Top Pages</h5>
                            <div class="section-tabs">
                                <button type="button" class="section-tabs active w-100" id="TopPages-tab">
                                    Top Pages
                                </button>
                                <button type="button" class="section-tabs w-100" id="EntryPages-tab">
                                    Entry Pages
                                </button>
                                <button type="button" class="section-tabs w-100" id="ExitPages-tab">
                                    Exit Pages
                                </button>
                            </div>
                        </div>
                        <hr class="my-0" />
                        <div class="card-body overflow-y-auto">
                            <div class="tab-content p-0">
                                <div class="tab-pane fade show active cursor-pointer w-100" id="navs-pills-top-pages" role="tabpanel">
                                </div>
                                <div class="tab-pane fade cursor-pointer w-100" id="navs-pills-entry-pages" role="tabpanel">
                                </div>
                                <div class="tab-pane fade cursor-pointer w-100" id="navs-pills-exit-pages" role="tabpanel">
                                </div>
                            </div>
                        </div>
                        <hr class="my-0" />
                        <div class="card-footer text-center">
                            <a href="#" id="top-pages-details">
                                Details
                            </a>
                        </div>
                    </div>
                </div>
                <!-- / Top Pages -->
            </div>
            <!-- / First Row -->

            <!-- Second Row: Locations & Devices -->
            <div class="row g-6 mb-6">
                <!-- Locations -->
                <div class="col-md-6">
                    <div class="card custom-h-card">
                        <div class="card-header">
                            <h5 class="card-title">Locations</h5>
                            <div class="section-tabs">
                                <button type="button" class="section-tabs active w-100" id="Map-tab">
                                    Map
                                </button>
                                <button type="button" class="section-tabs w-100" id="Countries-tab">
                                    Countries
                                </button>
                                <button type="button" class="section-tabs w-100" id="Regions-tab">
                                    Regions
                                </button>
                                <button type="button" class="section-tabs w-100" id="Cities-tab">
                                    Cities
                                </button>
                            </div>
                        </div>
                        <hr class="my-0 " />
                        <div class="card-body overflow-y-auto">
                            <div class="tab-content p-0">
                                <div class="tab-pane fade show active w-100" id="navs-pills-map" role="tabpanel">
                                    <!-- <div id="visitorsMap" style="height: 300px; width: 100%;"></div> -->
                                    <div id="visitorsMap" style="height: 300px; width: 100%;">
                                    </div>
                                </div>
                                <div class="tab-pane fade cursor-pointer w-100" id="navs-pills-countries" role="tabpanel">
                                </div>
                                <div class="tab-pane fade cursor-pointer w-100" id="navs-pills-regions" role="tabpanel">
                                </div>
                                <div class="tab-pane fade cursor-pointer w-100" id="navs-pills-cities" role="tabpanel">
                                </div>
                            </div>
                        </div>
                        <hr class="my-0" />
                        <div class="card-footer text-center">
                            <a href="#" id="locations-details">
                                Details
                            </a>
                        </div>
                    </div>
                </div>
                <!-- / Locations -->

                <!-- Devices -->
                <div class="col-md-6">
                    <div class="card custom-h-card">
                        <div class="card-header justify-content-start">
                            <h5 class="card-title">Devices</h5>
                            <div class="section-tabs">
                                <button type="button" class="section-tabs active w-100" id="Browser-tab">
                                    Browser
                                </button>
                                <button type="button" class="section-tabs w-100" id="OS-tab">
                                    OS
                                </button>
                                <button type="button" class="section-tabs w-100" id="Size-tab">
                                    Size
                                </button>
                            </div>
                        </div>
                        <hr class="my-0" />
                        <div class="card-body overflow-y-auto">
                            <div class="tab-content p-0">
                                <div class="tab-pane fade show active cursor-pointer" id="navs-pills-browser" role="tabpanel">
                                </div>
                                <div class="tab-pane fade cursor-pointer" id="navs-pills-os" role="tabpanel">
                                </div>
                                <div class="tab-pane fade cursor-pointer" id="navs-pills-size" role="tabpanel">
                                </div>
                            </div>
                        </div>
                        <hr class="my-0" />
                        <div class="card-footer text-center">
                            <a href="#" id="device-details">
                                Details
                            </a>
                        </div>
                    </div>
                </div>
                <!-- / Devices -->
            </div>
            @endif
            <!-- / Second Row -->
        </div>
    </div>
</div>
@endsection

@section('modal')

<!-- Channels Modal -->
<div class="modal fade" id="channelsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Channels Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-datatable table-responsive">
                    <table class="datatable-table table table-striped">
                        <thead>
                            <tr>
                                <th>Channel</th>
                                <th>Visitors</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pages Modal -->
<div class="modal fade" id="pagesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Pages Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-datatable table-responsive">
                    <table class="datatable-table table table-striped">
                        <thead>
                            <tr>
                                <th>Page URL</th>
                                <th>Page Views</th>
                                <th>Unique Visitors</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic content will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Locations Modal -->
<div class="modal fade" id="locationsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Locations Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-datatable table-responsive">
                    <table class="datatable-table table table-striped">
                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Visitors</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody id="locationsModalTable">
                            <!-- Dynamic content will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Devices Modal -->
<div class="modal fade" id="devicesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="devicesModalTitle">All Devices Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-datatable table-responsive">
                    <table class="datatable-table table table-striped">
                        <thead>
                            <tr id="devicesModalHeaders">
                                <th>Browser</th>
                                <th>Visitors</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody id="devicesModalTable">
                            <!-- Dynamic content will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Range Modal -->
<div class="modal fade" id="customRangeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Custom Date Range</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <div class="modal-body">
                <input type="text" id="bs-rangepicker-basic" class="form-control" />
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="applyCustomRange">Apply</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<!-- KidzType specific JS -->
{{-- =================== Datepicker SCRIPTS =================== --}}
<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="{{ asset('assets/js/analytics.js') }}?v={{ time() }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateRangeInput = document.getElementById('dateRangeInput');
        const dateRangeText = document.getElementById('dateRangeText');
        const rangeOptions = document.querySelectorAll('.range-option');
        const dateRangeForm = document.getElementById('dateRangeForm');

        const customModalEl = document.getElementById("customRangeModal");
        const customModal = new bootstrap.Modal(customModalEl);
        const bsRangePickerBasic = $('#bs-rangepicker-basic');

        // Initialize date range picker
        if (bsRangePickerBasic.length) {
            bsRangePickerBasic.daterangepicker({
                opens: 'center',
                autoUpdateInput: true,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            // Update input when dates are selected
            bsRangePickerBasic.on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            });
        }

        // Function to update URL with selected range
        function updateURLWithDateRange(rangeValue, displayText, customDate = null) {
            dateRangeInput.value = rangeValue;
            dateRangeText.textContent = displayText;

            // Build URL with query parameters
            let url = '{{ route("analytics.index") }}';
            let params = new URLSearchParams();

            params.append('date_range', rangeValue);

            if (customDate) {
                params.append('date', customDate);
            }

            // Update the form action and submit
            dateRangeForm.action = url + '?' + params.toString();
            dateRangeForm.submit();
        }

        // Dropdown click handler for all range options
        rangeOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const selectedRange = this.getAttribute('data-range');
                const displayText = this.textContent.trim();

                if (selectedRange === 'custom_range') {
                    // Open custom range modal
                    customModal.show();
                } else {
                    // Normal predefined range - update URL immediately
                    updateURLWithDateRange(selectedRange, displayText);
                }
            });
        });

        // Apply button inside modal
        $('#applyCustomRange').on('click', function() {
            const rangeValue = $('#bs-rangepicker-basic').val();

            if (rangeValue) {
                // For custom range, we need to pass the actual date range string
                updateURLWithDateRange('custom_range', rangeValue, rangeValue);

                // Hide modal
                customModal.hide();
            } else {
                alert('Please select a date range');
            }
        });

        // Clear date picker when modal is closed
        customModalEl.addEventListener('hidden.bs.modal', function() {
            bsRangePickerBasic.val('');
        });

    });
</script>
@endsection