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
            <b><a href="/dashboard">Dashboard</a> > <a href="/cab">Cab</a> > <a
                    class="add">Add</a></b>
            <br>
            <br>
            <h3 class="fw-bold pb-2">Edit Cab</h3>
        </div>

        <!-- FORM -->
        <form class="" id="form_valid" action="{{ route('admin.cab_update', $destination_details->id) }}" method="POST" autocomplete="off"
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
                                <!-- Destination Dropdown -->
                                <div class="col-md-4">
                                    <label class="mb-2">Destination <span class="text-danger">*</span></label>
                                    <select id="cities_name" name="cities_name" class="form-select py-2 rounded-3 shadow-sm" required>
                                        <option value="" disabled selected>Select Destination</option>
                                        @foreach($cities as $name)
                                        <option value="{{ $name }}"
                                            @if(old('cities_name', $destination_details->destination_id ?? '') == $name) selected @endif>
                                            {{ $name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- District Dropdown -->
                                <div class="col-md-4">
                                    <label class="mb-2">District <span class="text-danger">*</span></label>
                                    <select id="district_name" name="district_name" class="form-select py-2 rounded-3 shadow-sm" required>
                                        <option value="" disabled selected>Select District</option>
                                        @if(isset($destination_details->district_id))
                                        <option value="{{ $destination_details->district_id }}" selected>
                                            {{ $destination_details->district_id }}
                                        </option>
                                        @endif
                                    </select>
                                </div>


                                <div class="col-md-4">
                                    <label class="mb-2">Travel Mode <span class="text-danger">*</span></label>
                                    <select id="travel_mode" name="travel_mode" class="form-select py-2 rounded-3 shadow-sm" required>
                                        <option value="" disabled selected>Select Mode</option>
                                        <option value="cab" @if(old('travel_mode', $destination_details->travel_mode ?? null) == 'cab') selected @endif>
                                            Cab
                                        </option>
                                        <option value="bus" @if(old('travel_mode', $destination_details->travel_mode ?? null) == 'bus') selected @endif>
                                            Bus
                                        </option>
                                    </select>
                                </div>

                                <div class="d-flex flex-column">
                                    <!-- Initial fields -->
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label class="mb-2">Title <span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Title"
                                                name="camp_rules[0][title]"
                                                class="form-control py-2 rounded-3 shadow-sm"
                                                value="{{ $camp_rules[0]['title'] ?? old('camp_rules.0.title') }}" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="mb-2">Price <span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Price"
                                                name="camp_rules[0][price]"
                                                class="form-control py-2 rounded-3 shadow-sm"
                                                value="{{ $camp_rules[0]['price'] ?? old('camp_rules.0.price') }}" required>
                                        </div>

                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-primary" onclick="addPriceField()">
                                                <i class="fa fa-plus"></i> Add More
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Container for dynamic fields -->
                                    <div id="camp-rule-container">
                                        @foreach(array_slice($camp_rules, 1) as $index => $rule)
                                        <div class="camp-rule-field mb-4 p-3 border rounded">
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <label class="mb-2">Title <span class="text-danger">*</span></label>
                                                    <input type="text" placeholder="Title"
                                                        name="camp_rules[{{ $index + 1 }}][title]"
                                                        class="form-control py-2 rounded-3 shadow-sm"
                                                        value="{{ $rule['title'] ?? old('camp_rules.' . ($index + 1) . '.title') }}" required>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="mb-2">Price <span class="text-danger">*</span></label>
                                                    <input type="text" placeholder="Price"
                                                        name="camp_rules[{{ $index + 1 }}][price]"
                                                        class="form-control py-2 rounded-3 shadow-sm"
                                                        value="{{ $rule['price'] ?? old('camp_rules.' . ($index + 1) . '.price') }}" required>
                                                </div>

                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger" onclick="removeField(this)">
                                                        <i class="fa fa-trash"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="row g-2 px-5">
                            <div class="col">
                                <h4> <label class="fw-bold">Status</label></h4>
                                <div class="form-check form-switch d-flex align-items-center">
                                    <input class="form-check-input check_bx" type="checkbox" id="status" name="status" {{ $destination_details->status ? 'checked' : '' }}>>
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
                            <a href="{{ route('admin.cablist') }}">
                                <button type="button" class="cancel-btn"> Cancel </button>
                            </a>
                            <button class="submit-btn sbmtBtn ms-4 mb-5"> Submit </button>
                        </div>
                    </div>
                </div>



        </form>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>

        let fieldCounter = {{ count($camp_rules ?? []) > 0 ? count($camp_rules) : 0 }};

        $(document).ready(function() {

            const $citiesSelect = $('#cities_name');
        


            // Initialize with existing values if in edit mode
            const initialDestination = "{{ $destination_details->destination_id ?? '' }}";
            const initialDistrict = "{{ $destination_details->district_id ?? '' }}";

            if (initialDestination) {
                // Trigger district load for existing destination
                loadDistricts(initialDestination, initialDistrict);
            }

            $('#cities_name').change(function() {
                const destination = $(this).val();
                loadDistricts(destination);
            });

            function loadDistricts(destination, selectedDistrict = null) {
                const districtSelect = $('#district_name');

                if (!destination) {
                    districtSelect.empty().append(
                        '<option value="" disabled selected>Select District</option>'
                    ).prop('disabled', true);
                    return;
                }

                // Show loading state
                districtSelect.empty().append(
                    '<option value="" disabled>Loading districts...</option>'
                ).prop('disabled', true);

                $.ajax({
                    url: '/get-districts/' + encodeURIComponent(destination),
                    type: 'GET',
                    success: function(data) {
                        districtSelect.empty().append(
                            '<option value="" disabled selected>Select District</option>'
                        );

                        if (data && data.length > 0) {
                            $.each(data, function(index, district) {
                                const isSelected = (selectedDistrict && district === selectedDistrict);
                                districtSelect.append(
                                    $('<option>', {
                                        value: district,
                                        text: district,
                                        selected: isSelected
                                    })
                                );
                            });
                            districtSelect.prop('disabled', false);
                        } else {
                            districtSelect.append(
                                '<option value="" disabled>No districts found</option>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        districtSelect.empty().append(
                            '<option value="" disabled>Error loading districts</option>'
                        );
                    }
                });
            }

        });




        // Function to add new price field
        function addPriceField() {
            const container = document.getElementById('camp-rule-container');
            fieldCounter++;

            const newField = document.createElement('div');
            newField.className = 'camp-rule-field mb-4 p-3 border rounded';
            newField.innerHTML = `
        <div class="row g-2">
            <div class="col-md-4">
                <label class="mb-2">Title <span class="text-danger">*</span></label>
                <input type="text" placeholder="Title" 
                    name="camp_rules[${fieldCounter}][title]"
                    class="form-control py-2 rounded-3 shadow-sm" required>
            </div>
            
            <div class="col-md-4">
                <label class="mb-2">Price <span class="text-danger">*</span></label>
                <input type="text" placeholder="Price" 
                    name="camp_rules[${fieldCounter}][price]"
                    class="form-control py-2 rounded-3 shadow-sm" required>
            </div>
            
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger" onclick="removeField(this)">
                    <i class="fa fa-trash"></i> Remove
                </button>
            </div>
        </div>
    `;

            container.appendChild(newField);
        }

        // Function to remove field and mark as removed
        function removeField(button) {
            const fieldGroup = button.closest('.camp-rule-field');
            if (fieldGroup) {
                // Instead of removing, we'll hide and add a hidden input to mark as removed
                fieldGroup.style.display = 'none';

                // Add hidden input to mark this field as removed
                const inputs = fieldGroup.querySelectorAll('input[name^="camp_rules"]');
                if (inputs.length > 0) {
                    const inputName = inputs[0].name;
                    const indexMatch = inputName.match(/\[(\d+)\]/);
                    if (indexMatch && indexMatch[1]) {
                        const index = indexMatch[1];
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = `camp_rules[${index}][removed]`;
                        hiddenInput.value = '1';
                        fieldGroup.appendChild(hiddenInput);
                    }
                }
            }
        }

        // Before form submission, clean up removed items
        document.getElementById('form_valid').addEventListener('submit', function(e) {
            // Remove all hidden fields (they've already served their purpose)
            document.querySelectorAll('.camp-rule-field [name$="[removed]"]').forEach(el => {
                el.remove();
            });

            // Remove all hidden field groups
            document.querySelectorAll('.camp-rule-field').forEach(el => {
                if (el.style.display === 'none') {
                    el.remove();
                }
            });

            // Reindex remaining fields to maintain sequential numbering
            const remainingFields = document.querySelectorAll('.camp-rule-field:not([style*="display: none"])');
            remainingFields.forEach((field, newIndex) => {
                // Skip the first field (index 0) as it's static
                if (newIndex >= 0) {
                    const inputs = field.querySelectorAll('input[name^="camp_rules"]');
                    inputs.forEach(input => {
                        const oldName = input.name;
                        const newName = oldName.replace(/\[(\d+)\]/, `[${newIndex + 1}]`);
                        input.name = newName;
                    });
                }
            });
        });
    </script>
    @endsection

