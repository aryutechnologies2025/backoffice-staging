@extends('layouts.app')
@Section('content')

<style>
    a:hover {
        color: red;
    }

    a {
        color: rgb(37, 150, 190);
    }

    .add {
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

        padding-top: 1% !important;
        padding-bottom: 1% !important;
        width: 90% !important;
    }

    .mb-1 {
        margin-bottom: .5rem !important;
    }


    .form-control {
        width: 80%;
    }

    .btn-add {
        background-color: #2164c0 !important;
        border-radius: 15px !important;
        color: #FFF !important;

        padding: 10px 28px !important;
        font-size: 12px !important;
        border: none;

    }


    .form-input img {
        width: 80%;
    }

    .form-check-input {
        margin-top: 1% !important;
    }

    .form-check-input {
        margin-top: 1% !important;
    }

    .plan-item .form-label {
        font-weight: bold;
    }

    .plan-item input {
        margin-bottom: 10px;
    }

    .btn-add {
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }

    #summernote3 {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 10px;
        background-color: #fff;
    }

    .form-switch {
        padding-left: 3.5em;
    }

    .form-input label {
        width: 150% !important;
        border: 0px !important;
    }

    .forms {
        margin-left: 38px;
    }

    @media (min-width: 768px) {
        .col-md-1 {
            flex: 0 0 auto;
            width: 10.33333333%;
        }
    }



    .custom-checkbox {
        width: 18px;
        height: 18px;
    }

    /* Ensure responsiveness on all screen sizes */
    @media (max-width: 768px) {
        .custom-checkbox {
            width: 18px;
            height: 18px;
        }
    }
</style>
<div class="container-wrapper py-5">
    <div class="row">
        <div class="col-lg-12">
            <b><a href="/dashboard">Dashboard</a> > <a href="/pricingcalculator">Pricing</a> > <a
                    class="add">Add</a></b>
            <br>
            <br>
            <h3 class="fw-bold pb-2">Pricing Calculator</h3>
        </div>

        <!-- FORM -->
        <form class="" id="form_valid" action="{{ route('admin.pricing_insert') }}" method="POST" autocomplete="off"
            enctype="multipart/form-data">
            @csrf
            <!-- 1.INFORMATION -->
            <div class="row mb-3">
                <div class="col">
                    <div class="form-body p-4 rounded-4">
                        <h4 class="fw-bold mb-5 px-5 pt-5">Information</h4>


                        <div class="mb-3 px-5">

                            <div class="row gap-2">
                                <!-- Theme and Destination -->
                                <div class="col-md-4">
                                    <label class="mb-2">Destination <span class="text-danger">*</span></label>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                            type="button" id="destinationDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span id="destinationDropdownText">Select Destination</span>
                                        </button>
                                        <ul class="dropdown-menu w-100 p-2" aria-labelledby="destinationDropdown"
                                            style="max-height: 200px; overflow-y: auto;">
                                            @foreach($cities as $id => $name)
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input destination-checkbox" type="checkbox"
                                                        id="destination-{{ $id }}" name="cities_name[]"
                                                        value="{{ $name }}"
                                                        @if (is_array(old('cities_name', [])) && in_array($id, old('cities_name', []))) checked @endif>
                                                    <label class="form-check-label w-100"
                                                        for="destination-{{ $id }}">
                                                        {{ $name }}
                                                    </label>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="mb-2">District <span class="text-danger">*</span></label>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                            type="button" id="districtDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span id="districtDropdownText">Select District</span>
                                        </button>
                                        <ul class="dropdown-menu w-100 p-2" aria-labelledby="districtDropdown"
                                            style="max-height: 200px; overflow-y: auto;">
                                            <!-- Districts will be populated here via JavaScript -->
                                        </ul>
                                    </div>
                                    <!-- Hidden input to store selected values for form submission -->
                                    <input type="hidden" name="district_name" id="districtHiddenInput">
                                </div>

                                <div class="row d-flex mt-3">
                                    <div class="col-md-4">
                                        <label class="mb-2">Total Amount</label>
                                        <input id="total_amount" type="number" class="form-control" name="total_amount">
                                    </div>
                                </div>

                            </div>

                            <br>

                            <div class="row g-2 px-5">
                                <div class="col">
                                    <h4> <label class="fw-bold">Status</label></h4>
                                    <div class="form-check form-switch d-flex align-items-center">
                                        <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                                    </div>
                                </div>
                            </div>

                            <style>
                                .form-check-input {
                                    transform: scale(1.5);
                                    /* Increase the size of the checkbox */
                                }
                            </style>

                            <div class="col-lg-12 text-end mt-5">
                                <a href="{{ route('admin.pricinglist') }}">
                                    <button type="button" class="cancel-btn"> Cancel </button>
                                </a>
                                <button class="submit-btn sbmtBtn ms-4 mb-5"> Submit </button>
                            </div>
                        </div>
                    </div>



        </form>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Destination dropdown code (unchanged)
            const destinationDropdownButton = document.getElementById('destinationDropdown');
            const destinationDropdownText = document.getElementById('destinationDropdownText');
            const destinationCheckboxes = document.querySelectorAll('.destination-checkbox');

            // District dropdown elements
            const districtDropdownButton = document.getElementById('districtDropdown');
            const districtDropdownText = document.getElementById('districtDropdownText');
            const districtHiddenInput = document.getElementById('districtHiddenInput');
            const districtDropdownMenu = districtDropdownButton.nextElementSibling;

            // Initialize with any pre-checked boxes
            updateDestinationButtonText();

            // Update when destination checkboxes change
            destinationCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateDestinationButtonText();
                    handleDestinationChange();
                });
            });

            function updateDestinationButtonText() {
                const checked = Array.from(destinationCheckboxes).filter(cb => cb.checked);

                if (checked.length === 0) {
                    destinationDropdownText.textContent = 'Select Destination';
                } else if (checked.length === 1) {
                    destinationDropdownText.textContent = checked[0].nextElementSibling.textContent;
                } else {
                    destinationDropdownText.textContent = `${checked.length} destinations selected`;
                }
            }

            function handleDestinationChange() {
                const checked = Array.from(destinationCheckboxes).filter(cb => cb.checked);
                const selectedDestinations = Array.from(checked).map(cb => cb.value);

                // Clear districts if no destinations selected
                if (selectedDestinations.length === 0) {
                    districtDropdownMenu.innerHTML = '';
                    districtDropdownText.textContent = 'Select District';
                    districtHiddenInput.value = '';
                    return;
                }

                // Show loading state
                districtDropdownMenu.innerHTML = '<li><div class="p-2 text-center">Loading districts...</div></li>';

                // AJAX request
                $.ajax({
                    url: '/get-districts-list',
                    type: 'POST',
                    data: {
                        destination_ids: selectedDestinations,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        districtDropdownMenu.innerHTML = '';

                        if (response && response.length > 0) {
                            const uniqueDistricts = [...new Set(response)].sort();

                            uniqueDistricts.forEach(district => {
                                const li = document.createElement('li');
                                li.innerHTML = `
                            <div class="form-check">
                                <input class="form-check-input district-checkbox" type="checkbox" 
                                    id="district-${district.replace(/\s+/g, '-')}" 
                                    value="${district}">
                                <label class="form-check-label w-100" 
                                    for="district-${district.replace(/\s+/g, '-')}">
                                    ${district}
                                </label>
                            </div>
                        `;
                                districtDropdownMenu.appendChild(li);
                            });

                            // Add event listeners to new checkboxes
                            document.querySelectorAll('.district-checkbox').forEach(checkbox => {
                                checkbox.addEventListener('change', updateDistrictSelection);
                            });
                        } else {
                            districtDropdownMenu.innerHTML = '<li><div class="p-2 text-center">No districts found</div></li>';
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        districtDropdownMenu.innerHTML = '<li><div class="p-2 text-center">Error loading districts</div></li>';
                    }
                });
            }

            function updateDistrictSelection() {
                const checked = Array.from(document.querySelectorAll('.district-checkbox:checked'));

                // Update button text
                if (checked.length === 0) {
                    districtDropdownText.textContent = 'Select District';
                } else if (checked.length === 1) {
                    districtDropdownText.textContent = checked[0].nextElementSibling.textContent;
                } else {
                    districtDropdownText.textContent = `${checked.length} districts selected`;
                }

                // Update hidden input value with comma-separated districts
                districtHiddenInput.value = checked.map(cb => cb.value).join(',');
            }

            // Prevent dropdown from closing when clicking checkboxes
            document.querySelector('#destinationDropdown ~ .dropdown-menu').addEventListener('click', function(e) {
                if (e.target.type === 'checkbox') {
                    e.stopPropagation();
                }
            });

            districtDropdownMenu.addEventListener('click', function(e) {
                if (e.target.type === 'checkbox') {
                    e.stopPropagation();
                }
            });
        });
    </script>
    @endsection