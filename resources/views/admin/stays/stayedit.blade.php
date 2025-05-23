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

    .form-check-input {
        transform: scale(1.5);
        /* Increase the size of the checkbox */
    }
</style>
<div class="container-wrapper py-5">
    <div class="row ">
        <div class="col-lg-12">
            <h3 class="fw-bold pb-2">Edit Stay Details</h3>
        </div>

        <!-- FORM -->
        <form id="form_valid" action="{{ route('admin.stay_details_update', $stay_details->id) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <!-- 1.INFORMATION -->
            <div class="row mb-3">
                <div class="col">
                    <div class="form-body rounded-4 p-4 ">
                        <h4 class="fw-bold mb-5 px-5 pt-5">Information</h4>
                        <div class="mb-3 px-5">
                            <div class="row gap-2">

                                <div class="col-md-4 ">
                                    <label class="mb-2 ">Destination <span class="text-danger">*</span></label>
                                    <select id="cities_name" name="cities_name"
                                        class="form-select py-2 rounded-3 shadow-sm">
                                        <option value="">Select Destination</option>
                                        @foreach($cities_dts as $id => $name)
                                        <option value="{{ $name }}"
                                            @if(old('cities_name', $stay_details->destination ?? '') == $name) selected @endif>
                                            {{ $name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class=" mb-2 "> Title <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Title" id="title" name="title" class="form-control py-2 rounded-3 shadow-sm" required value="{{$stay_details->stay_title}}">
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label class=" mb-2 "> Stay Location <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Location" id="title" name="stay_location" class="form-control py-2 rounded-3 shadow-sm" required value="{{$stay_details->stay_location}}">
                                </div>

                                <div class="mt-5">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="form-label form-label-top form-label-auto mb-2">Program Description <span class="text-danger">*</span></label>
                                            <textarea id="description" class="container__textarea px-3 py-2 textarea-feild" name="description" style="display:none;">{{$stay_details->stay_description}}</textarea>

                                            @php
                                            $plain_text_description = is_array($stay_details->stay_description)
                                            ? json_encode($stay_details->stay_description)
                                            : strip_tags($stay_details->stay_description);
                                            @endphp
                                            <div id="summernote1" style="height: 200px;">{{$plain_text_description}}</div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>




                        <div id="photo-upload-container" class="row mt-4 px-5 py-5">
                            <h4 class="py-3 fw-bold">Gallery Image - Room</h4>
                            @php
                            // Decode JSON if needed
                            $images = json_decode($stay_details->gallery_image, true);
                            $imageCount = is_array($images) ? count($images) : 0;
                            @endphp
                            @if(is_array($images))
                            @foreach($images as $key => $image)
                            <div class="col-lg-2 photo-upload-field">

                                <div class="form-input">
                                    <label for="file-ip-{{ $key }}" class="px-4 py-3 text-center">
                                        <img class="text-center mt-3" id="file-ip-{{ $key }}-preview" src="{{ asset($image) }}" alt="Image Preview">
                                        <p class="text-center fw-light mt-3">Edit Pic</p>
                                    </label>
                                    <input type="file" name="img_{{ $key }}" id="file-ip-{{ $key }}" data-number="{{ $key }}" accept="image/*">
                                </div>
                            </div>
                            @endforeach
                            @else
                            <p>No images available.</p>
                            @endif
                        </div>


                        <button id="add-photo-btn" type="button" class="mx-5 my-3 btn btn-primary m-1">Add More Photos</button>




                        <!-- 5.PRICING -->
                        <div class="row mb-3 mt-3">
                            <div class="col">
                                <div class="form-body px-5  rounded-4 ">
                                    <h4 class="fw-bold mb-3"> Pricing</h4>
                                    <div class="mb-2">

                                        <div class="row mb-2">
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-top form-label-auto fw-bold mb-2">
                                                    Days
                                                </label>
                                                <input type="number" name="price_title" class="form-control py-2 rounded-3 shadow-sm"
                                                    placeholder="Title" value="{{$stay_details->no_of_days}}">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fw-bold mb-2">Amount <span class="text-danger">*</span></label>
                                                <div class="position-relative">
                                                    <span class="position-absolute top-50 start-0 translate-middle-y ps-3">₹</span>
                                                    <input type="number" name="price_amount" class="form-control py-2 ps-5 rounded-3 shadow-sm"
                                                        placeholder="Actual Amount" value="{{$stay_details->price}}">
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>





                        <!-- 8.AMENITIES -->
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-body px-5 rounded-4">
                                    <h4 class="fw-bold mb-3">Amenities </h4>
                                    <div class="row mb-4">
                                        @foreach($amenities_dts->chunk(4) as $chunk)
                                        <div class="row mb-3">
                                            @foreach($chunk as $amenity)
                                            <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" class="me-2 custom-checkbox" id="amenity-{{ $amenity->id }}" name="amenity_services[]" value="{{ $amenity->id }}" @if(in_array((string) $amenity->id, $selectedAmenities)) checked @endif>
                                                    <label class="mb-0" for="amenity-{{ $amenity->id }}">{{ $amenity->amenity_name }}</label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>




                        <!-- 9.FOOD & BEVERAGES -->
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-body px-5 rounded-4 ">
                                    <h4 class="fw-bold mb-3"> Food and Beverages</h4>
                                    <div class="row mb-4">
                                        @foreach($foodBeverages_dts->chunk(6) as $chunk)
                                        <div class="row mb-3">
                                            @foreach($chunk as $item)
                                            <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" class="custom-checkbox me-2" id="food-beverage-{{ $item->id }}" name="food_beverages[]" value="{{ $item->id }}" @if(in_array((string) $item->id, $selectedfood_beverages)) checked @endif>

                                                    <label class="mb-0" for="food-beverage-{{ $item->id }}">{{ $item->food_beverage }}</label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--10. ACTIVITIES -->
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-body px-5  rounded-4  ">
                                    <h4 class="fw-bold mb-3">Activities</h4>
                                    <div class="row mb-4">
                                        @foreach($activities_dts->chunk(6) as $chunk)
                                        <div class="row mb-3">
                                            @foreach($chunk as $item)
                                            <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" class="custom-checkbox me-2" id="activities-{{ $item->id }}" name="activities[]" value="{{ $item->id }}" @if(in_array((string) $item->id, $selectedactivities)) checked @endif>
                                                    <label class="mb-0" for="activities-{{ $item->id }}">{{ $item->activities }}</label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- 11.SAFETY FEATURES  -->
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-body px-5  rounded-4  ">
                                    <h4 class="fw-bold mb-3">Safety Features</h4>
                                    <div class="row mb-4">
                                        @foreach($safety_features_dts->chunk(6) as $chunk)
                                        <div class="row mb-3">
                                            @foreach($chunk as $item)
                                            <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" class="custom-checkbox me-2" id="safety_features-{{ $item->id }}" name="safety_features[]" value="{{ $item->id }}" @if(in_array((string) $item->id, $selectedsafety_features)) checked @endif>
                                                    <label class="mb-0" for="safety_features-{{ $item->id }}">{{ $item->safety_features }}</label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endforeach
                                    </div>



                                    <div class="row g-2 mt-3">
                                        <div class="col">
                                            <h4 class="fw-bold ">Status</h4>
                                            <div class="form-check form-switch d-flex align-items-center">
                                                <input class="form-check-input check_bx" name="status" type="checkbox" id="status" {{ $stay_details->status ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-2">
                                        <div class="col-lg-3">
                                            <h4 class="fw-bold mb-2">Order</h4>
                                            <input type="number" placeholder="Order" id="list_order" name="list_order"
                                                value="{{$stay_details->order}}" class="form-control py-2 rounded-3 shadow-sm" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-12 text-end mt-5">
                                    <a href="{{ route('admin.staylist') }}">
                                        <button type="button" class="cancel-btn"> Cancel </button>
                                    </a>
                                    <button class="submit-btn sbmtBtn ms-4"> Submit </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </form>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote1')
            .summernote({
                height: 200 // Set the height of the editor
            });
        $('#summernote1').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

    });


    document.addEventListener('DOMContentLoaded', function() {
        // updateTypeOptions(); // Call function to prepopulate the "Types" dropdown on page load
    });

    function updateTypeOptions() {
        // Get the selected value from the first dropdown
        var propCatValue = document.getElementById('prop_cat').value;

        // Get the container and the second dropdown
        var typeContainer = document.getElementById('type-container');
        var typeSelect = document.getElementById('type');

        // Define options based on the selected value
        var options = [];
        if (propCatValue === 'events') {
            options = [{
                    value: '',
                    text: 'Select Types'
                },
                {
                    value: 'upcoming_events',
                    text: 'Upcoming Events'
                },
                {
                    value: 'popular_events',
                    text: 'Popular Events'
                }
            ];
        } else if (propCatValue === 'packages') {
            options = [{
                    value: '',
                    text: 'Select Types'
                },
                {
                    value: 'upcoming_packages',
                    text: 'Upcoming Packages'
                },
                {
                    value: 'popular_packages',
                    text: 'Popular Packages'
                }
            ];
        }

        // Populate the options in the second dropdown
        typeSelect.innerHTML = ''; // Clear previous options
        options.forEach(function(option) {
            var opt = document.createElement('option');
            opt.value = option.value;
            opt.text = option.text;
            typeSelect.add(opt);
        });



        // Show or hide the second dropdown based on selection
        typeContainer.style.display = propCatValue ? 'block' : 'none';
    }




    $(document).ready(function() {
        let photoCount = @json($imageCount); // Use the correct count variable

        // Function to generate new photo upload field HTML
        function createPhotoUploadField(count) {
            return `
            <div class="col-lg-2 photo-upload-field">
                <div class="form-input">
                    <label for="file-ip-${count}" class="px-4 py-3 text-center">
                        <img class="text-center mt-3" id="file-ip-${count}-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg" alt="Image Preview">
                        <p class="text-center fw-light mt-3">Add Pic</p>
                    </label>
                    <input type="file" name="img_${count}" id="file-ip-${count}" data-number="${count}" accept="image/*">
                </div>
            </div>
        `;
        }

        // Event listener for the "Add More Photos" button
        $('#add-photo-btn').on('click', function() {
            photoCount++;
            const newFieldHtml = createPhotoUploadField(photoCount);
            $('#photo-upload-container').append(newFieldHtml);
        });

        // Function to show preview of selected image
        function showPreview(event) {
            var file = event.target.files[0];
            var number = $(event.target).data('number'); // Use data attribute to get the number
            var previewId = "#file-ip-" + number + "-preview";

            var reader = new FileReader();

            reader.onload = function(e) {
                $(previewId).attr('src', e.target.result);
            };

            if (file) {
                if (file.size <= 2 * 1024 * 1024) { // 2 MB limit
                    if (file.type === 'image/png' || file.type === 'image/jpeg') {
                        reader.readAsDataURL(file);
                    } else {
                        alert('Please upload a valid PNG or JPEG image.');
                    }
                } else {
                    alert('File size exceeds 2 MB limit.');
                }
            }
        }

        // Delegate event binding for dynamically added file inputs
        $('#photo-upload-container').on('change', 'input[type="file"]', showPreview);
    });



    function addCampRuleField() {
        // Find the container where new fields will be added
        var container = document.getElementById('camp-rule-container');

        // Create a new div for the new field
        var newField = document.createElement('div');
        newField.className = 'row g-2 mb-4 camp-rule-field';
        newField.innerHTML = `
        <div class="col">
            <input type="text" name="camp_rule[]" class="form-control py-3 rounded-3 shadow-sm" placeholder="Rule And Regulations" required>
        </div>
        <div class="col-lg-1 mt-5 text-end">
            <a class="table-link danger remove-plan" onclick="removeField(this)">
                <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                </span>
            </a>
        </div>`;

        // Append the new field to the container
        container.appendChild(newField);
    }

    function removeField(element) {
        // Find the parent element (field container) and remove it
        var field = element.closest('.camp-rule-field');
        if (field) {
            field.remove();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const themeSelect = document.getElementById('themes_name');
        const categorySelect = document.getElementById('theme_cat');

        function populateCategories(themeId) {
            fetch(`/all-inclusive-package/theme-categories/${themeId}`)
                .then(response => response.json())
                .then(data => {
                    categorySelect.innerHTML = '<option value="">Select Category</option>'; // Clear previous options
                    Object.keys(data).forEach(id => {
                        const option = document.createElement('option');
                        option.value = id;
                        option.textContent = data[id];
                        categorySelect.appendChild(option);
                    });

                    // Pre-select the category
                    @if($selectedCategoryId)
                    categorySelect.value = '{{ $selectedCategoryId }}';
                    @endif
                })
                .catch(error => console.error('Error fetching theme categories:', error));
        }

        // Initial population of categories if a theme is pre-selected
        if (themeSelect.value) {
            populateCategories(themeSelect.value);
        }

        // Add event listener for theme change
        themeSelect.addEventListener('change', function() {
            const themeId = this.value;
            if (themeId) {
                populateCategories(themeId);
            } else {
                categorySelect.innerHTML = '<option value="">Select Category</option>'; // Clear categories if no theme selected
            }
        });
    });

    $(document).ready(function() {
        const $citiesSelect = $('#cities_name');
        const $destinationCatSelect = $('#destination_cat');
        const selectedDestinationCat = @json($selecteddesCategoryId); // Pass the initially selected destination category ID

        function populateDestinationCategories(cityId) {
            $destinationCatSelect.empty().append('<option value="">Select Destination Category</option>');

            if (cityId) {
                $.ajax({
                    url: '{{ route("admin.destination_categories") }}', // Use the route defined in web.php
                    type: 'GET',
                    data: {
                        city_id: cityId
                    },
                    success: function(data) {
                        $.each(data, function(id, name) {
                            $destinationCatSelect.append(new Option(name, id));
                        });

                        // Set the previously selected destination category
                        if (selectedDestinationCat) {
                            $destinationCatSelect.val(selectedDestinationCat);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching categories:', error);
                    }
                });
            }
        }

        // Populate destination categories on city change
        $citiesSelect.on('change', function() {
            const cityId = $(this).val();
            populateDestinationCategories(cityId);
        });

        // Populate destination categories on page load if a city is selected
        const initialCityId = $citiesSelect.val();
        if (initialCityId) {
            populateDestinationCategories(initialCityId);
        }
    });
</script>
@endsection