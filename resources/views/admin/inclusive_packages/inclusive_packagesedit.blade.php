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

    .px-5 {

        padding-left: 0rem !important;
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
        width: 100%;
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
    }

    .forms {
        margin-left: 100px;
    }

    @media (min-width: 768px) {
        .col-md-1 {
            flex: 0 0 auto;
            width: 10.33333333%;
        }
    }


    .photo-upload-field {
        text-align: center;
    }

    .photo-upload-field img {
        width: 100%;
        /* Ensures images are responsive */
        max-height: 150px;
        /* Optional, for consistent image height */
        object-fit: cover;
    }

    .g-3 {
        --bs-gutter-x: 6rem !important;
    }

    .me-1 {
        margin-right: -8.75rem !important;
    }

    .g-4 {
        margin-left: -170px ! important;
    }

    /* .form-input {
        border: 1px dashed #ccc;
        padding: 10px;
        border-radius: 5px;
        background-color: #f9f9f9;
    } */
</style>
<div class="container-wrapper pt-5">
    <div class="row">
        <div class="col-lg-12">
            <b><a href="/dashboard">Dashboard</a> > <a href="/all-inclusive-package">Program</a> > <a
                    class="edit">Edit</a></b>
            <br>
            <br>
            <h3 class="fw-bold">{{ $title }}</h3>
        </div>

        <!-- FORM -->
        <form id="form_valid" action="{{ route('admin.inclusive_package_update', $package_details->id) }}" method="POST"
            autocomplete="off" enctype="multipart/form-data">
            @csrf

            <!-- 1.INFORMATION -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="form-body px-4 py-4 mb-5 rounded-4">
                        <h4 class="fw-bold mb-4">1. Information</h4>

                        <!-- Themes, Destination, and Title in One Line -->
                        <div class="row g-3 mb-4">
                            <div class="col-lg-4">
                                <label class="fw-bold mb-2">Themes</label>
                                <select id="themes_name" name="themes_name"
                                    class="form-select py-2 rounded-3 shadow-sm">
                                    <option value="">Select Theme</option>
                                    @foreach ($themes as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ $id == $selectedthemeId ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="fw-bold mb-2">Destination <span class="text-danger">*</span></label>
                                <select id="cities_name" name="cities_name"
                                    class="form-select py-2 rounded-3 shadow-sm">
                                    <option value="">Select Destination</option>
                                    @foreach ($cities_dts as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ $id == $selectedCityId ? 'selected' : '' }}>{{ $name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="fw-bold mb-2">Title <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Title" id="title" name="title"
                                    class="form-control py-2 rounded-3 shadow-sm" value="{{ $package_details->title }}">
                            </div>
                        </div>

                        <!-- Program Description -->
                        <div class="mb-4">
                            <label class="fw-bold mb-2">Program Description <span class="text-danger">*</span></label>
                            <textarea id="program_description" name="program_description" style="display:none;"></textarea>







                            <!-- @php
                                    $plain_text_description = is_array($package_details->program_description)
                                        ? json_encode($package_details->program_description)
                                        : strip_tags($package_details->program_description);
                                @endphp -->
                            @php
                            $plain_text_description = is_array($package_details->program_description)
                            ? json_encode($package_details->program_description)
                            : strip_tags($package_details->program_description);
                            @endphp


                            <div id="summernote1" class="form-control py-3 shadow-sm"
                                style="height: 250px; overflow-y: auto;">{!! $package_details->program_description !!}</div>
                        </div>



                        <!-- Flags -->
                        <div class="mb-4">
                            <label class="fw-bold mb-2">Flags</label>
                            <div class="d-flex gap-5 align-items-center">

                                <div class=" d-flex gap-2 align-items-center">
                                    <input type="checkbox" id="popular_program" name="prop_cat[]"
                                        value="popular_program"
                                        {{ in_array('popular_program', $selectedprogram) ? 'checked' : '' }}>
                                    <label for="popular_program">Popular Program</label>
                                </div>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="checkbox" id="upcoming_program" name="prop_cat[]"
                                        value="upcoming_program"
                                        {{ in_array('upcoming_program', $selectedprogram) ? 'checked' : '' }}>
                                    <label for="upcoming_program">Upcoming Program</label>
                                </div>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="checkbox" id="featured" name="prop_cat[]" value="featured"
                                        {{ in_array('featured', $selectedprogram) ? 'checked' : '' }}>
                                    <label for="featured">Featured</label>
                                </div>

                            </div>

                        </div>


                        <!-- Cover Image -->
                        <div class="row mt-4">
                            <div class="col-md-2 h-25">
                                <label for="file-cover" class="form-label">Cover Image</label>
                                <div class="form-input text-start pt-2 pb-0">
                                    <label for="file-cover" class="d-block pt-4">
                                        @if ($package_details->cover_img)
                                        <img id="file-cover-preview" src="{{ asset($package_details->cover_img) }}" alt="Cover Image"
                                            class="rounded-3 shadow-sm" style="max-width: 250px; max-height: 250px; object-fit: cover;">
                                        @else
                                        <img id="file-cover-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg" alt="Add Pic"
                                            class="rounded-3 shadow-sm" style="max-width: 250px; max-height: 250px;">
                                        @endif
                                        <p class="mt-2">Add Pic</p>
                                    </label>
                                    <input type="file" id="file-cover" name="cover_img" class="form-control" accept="image/png, image/jpeg, image/svg+xml" onchange="previewCoverImage(event)">
                                    <div id="file-cover-error" class="text-danger"></div>
                                    <small class="text-danger d-block mt-2 text-center">* Upload size [1200x120]</small>
                                </div>
                            </div>
                        </div>


                        <!-- Gallery Images -->
                        <div id="photo-upload-container" class="row g-3">
                            <label class="fw-bold mt-4">Gallery Image</label>

                            @php
                            $images = !empty($package_details->events_package_images)
                            ? json_decode($package_details->events_package_images, true)
                            : [];
                            $imageCount = count($images);
                            @endphp

                            @if ($imageCount > 0)
                            @foreach ($images as $key => $image)
                            <div class="col-md-2 col-sm-4 col-6 photo-upload-field" id="photo-field-{{ $key }}">
                                <div class="form-input text-center">
                                    <label for="file-ip-{{ $key }}">
                                        <img class="img-fluid mt-3" id="file-ip-{{ $key }}-preview" src="{{ asset($image) }}" alt="Image Preview">
                                        <p class="fw-light mt-2">Edit Pic</p>
                                    </label>
                                    <input type="file" name="img_{{ $key }}" id="file-ip-{{ $key }}" data-number="{{ $key }}" accept="image/*" onchange="previewGalleryImage(event, this)">
                                    <button type="button" class="btn btn-danger btn-sm mt-2 delete-photo-btn" data-key="{{ $key }}" data-image="{{ $image }}">Delete</button>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <p>No images uploaded yet.</p>
                            @endif
                        </div>

                        <!-- Hidden input to store deleted images -->
                        <input type="hidden" name="deleted_images" id="deleted-images" value="[]">

                        <div class="mt-3 mb-4">
                            <button id="add-photo-btn" type="button" class="btn btn-primary">Add More Photos</button>
                        </div>

                        <script>
                            // Start photo counter from existing gallery photos
                            let photoCount = @json($imageCount);

                            // Function to add a new gallery photo upload field
                            document.getElementById('add-photo-btn').addEventListener('click', function() {
                                photoCount++;
                                const container = document.getElementById('photo-upload-container');
                                const newFieldHtml = `
                                <div class="col-md-2 col-sm-4 col-6 photo-upload-field" id="photo-field-${photoCount}">
                                    <div class="form-input text-center">
                                        <label for="file-ip-${photoCount}">
                                            <img class="img-fluid mt-3" id="file-ip-${photoCount}-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg" alt="Image Preview">
                                            <p class="fw-light mt-2">Add Pic</p>
                                        </label>
                                        <input type="file" name="img_${photoCount}" id="file-ip-${photoCount}" data-number="${photoCount}" accept="image/*" onchange="previewGalleryImage(event, this)">
                                        <button type="button" class="btn btn-danger btn-sm mt-2 delete-photo-btn" data-key="${photoCount}">Delete</button>
                                    </div>
                                </div>`;
                                container.insertAdjacentHTML('beforeend', newFieldHtml);
                            });

                            // Function to preview the cover image after file selection
                            function previewCoverImage(event) {
                                const preview = document.getElementById('file-cover-preview'); // Updated to preview the image element
                                const file = event.target.files[0];
                                const reader = new FileReader();

                                reader.onload = function() {
                                    preview.src = reader.result; // This updates the preview image
                                };

                                if (file) {
                                    reader.readAsDataURL(file); // This loads the selected file
                                }
                            }


                            // Function to preview the gallery image after selection
                            function previewGalleryImage(event, inputElement) {
                                const preview = document.getElementById(`file-ip-${inputElement.getAttribute('data-number')}-preview`);
                                const file = event.target.files[0];
                                const reader = new FileReader();

                                reader.onload = function() {
                                    preview.src = reader.result;
                                };

                                if (file) {
                                    reader.readAsDataURL(file);
                                }
                            }

                            // Function to delete photo field
                            document.getElementById('photo-upload-container').addEventListener('click', function(event) {
                                if (event.target.classList.contains('delete-photo-btn')) {
                                    const key = event.target.getAttribute('data-key');
                                    const photoField = document.querySelector(`#photo-field-${key}`);
                                    const imagePath = event.target.getAttribute('data-image');

                                    if (photoField) {
                                        // Add the image path to the hidden field
                                        const deletedImagesInput = document.getElementById('deleted-images');
                                        const deletedImages = JSON.parse(deletedImagesInput.value);
                                        deletedImages.push(imagePath); // Add the image path to the list
                                        deletedImagesInput.value = JSON.stringify(deletedImages);

                                        // Remove the photo field from the UI
                                        photoField.remove();
                                    }
                                }
                            });
                        </script>


                        <div class="row mb-2">
                            <div class="col">
                                <div class="form-body px-5  rounded-4">
                                    <h4 class="fw-bold mb-3">2.Location</h4>
                                    <div class="mb-1">
                                        <div class="row g-2 mb-1">
                                            <div class="col">
                                                <input type="hidden" id="location" name="location">

                                                @php
                                                $plain_text_important_info = html_entity_decode(
                                                strip_tags($package_details->location),
                                                );
                                                @endphp
                                                <div class=" mt-1">
                                                    <div class="row">
                                                        <div class="col-lg-12 ">
                                                            <div id="summernote10" style="height: 200px;">{!! $package_details->location !!}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. TOUR PLANNING -->

                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-body px-5 rounded-4">
                                    <h4 class="fw-bold mb-2">02. Tour Planning <span class="text-danger">*</span></h4>
                                    <div id="plan-container">
                                        @php
                                        // Decode JSON if needed

                                        $tourPlanning = json_decode($package_details->tour_planning ?? '{}', true);
                                        @endphp
                                        <div class="plan-item">
                                            <!-- Plan Description -->
                                            <div class="row g-2 mt-3 align-items-center mb-2">
                                                <div class="col-lg-12">
                                                    <label class="form-label mb-2">
                                                        Plan Description <span class="text-danger">*</span>
                                                    </label>

                                                    @php
                                                    $rawDescription = $tourPlanning['plan_description'][0] ?? '';
                                                    $plainTextDescription = html_entity_decode(strip_tags($rawDescription));
                                                    @endphp

                                                    <input type="hidden" name="plan_description[]" value="{{ $plainTextDescription }}">

                                                    <div id="summernote3" class="border rounded p-2 shadow-sm bg-light">
                                                        {!! $rawDescription !!}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- Add New Plan Button -->
                                    <!-- <div class="text-end ">
                                        <button type="button" id="add-plan-btn"
                                            class="btn-add rounded-3 border-0 px-3 py-2 text-white">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Add
                                        </button>
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <!-- 4. Needed -->



                        <!-- 5.PRICING -->
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-body px-5  rounded-4 ">
                                    <h4 class="fw-bold mb-3">3. Pricing</h4>
                                    <div class="mb-2">
                                        @php
                                        $priceTitle = json_decode($package_details->price_tilte, true) ?? [];
                                        $priceAmount = json_decode($package_details->price_amount, true) ?? [];
                                        $count = max(4, count($priceTitle), count($priceAmount));
                                        @endphp

                                        @for ($i = 0; $i < $count; $i++)
                                            <div class="row mb-2">
                                            <div class="col-lg-6">
                                                <label class="form-label form-label-top form-label-auto fw-bold mb-2">
                                                    Title
                                                </label>
                                                <input type="text" name="price_title[]" class="form-control py-2 rounded-3 shadow-sm"
                                                    placeholder="Title"
                                                    value="{{ $priceTitle[$i] ?? '' }}">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fw-bold mb-2">Amount <span class="text-danger">*</span></label>
                                                <div class="position-relative">
                                                    <span class="position-absolute top-50 start-0 translate-middle-y ps-3">₹</span>
                                                    <input type="number" name="price_amount[]" class="form-control py-2 ps-5 rounded-3 shadow-sm"
                                                        placeholder="Actual Amount"
                                                        value="{{ $priceAmount[$i] ?? '' }}">
                                                </div>
                                            </div>
                                    </div>
                                    @endfor

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 6.Rule And Regulations -->
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-body px-5 rounded-4">
                                <h4 class="fw-bold mb-3">4. Payment Policy <span class="text-danger">*</span></h4>
                                <div class="mb-3">
                                    <div id="camp-rule-container">
                                        @php
                                        $campRuleArray = json_decode($package_details->camp_rule, true);
                                        if (is_array($campRuleArray)) {
                                        $package_details->camp_rule = $campRuleArray;
                                        } else {
                                        // Handle the error or initialize it to an empty array if it's not a valid JSON string
                                        $package_details->camp_rule = [];
                                        }
                                        @endphp
                                        @if (is_array($package_details->camp_rule))
                                        @foreach ($package_details->camp_rule as $rule)
                                        <div class="row g-2 mb-2 camp-rule-field">
                                            <div class="col">
                                                <input type="text" name="camp_rule[]"
                                                    class="form-control py-2 rounded-3 shadow-sm"
                                                    placeholder="Rule And Regulations"
                                                    value="{{ $rule }}">
                                            </div>
                                            <div class="col-lg-1 mt-2 text-end">
                                                <a class="table-link danger remove-plan"
                                                    onclick="removeField(this)">
                                                    <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
                                                        <i
                                                            class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                        @else
                                        <p>No Rule And Regulations available.</p>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <button type="button"
                                            class="btn-add rounded border-0 px-5 py-2 text-white"
                                            onclick="addCampRuleField()">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- 7. Important info -->
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-body px-1 py-3 rounded-4">
                                <h4 class="fw-bold mb-3">5. Notes <span class="text-danger">*</span></h4>
                                <div>
                                    <input type="hidden" id="important_info" name="important_info">
                                    @php
                                    $plain_text_important_info = html_entity_decode(
                                    strip_tags($package_details->important_info),
                                    );
                                    @endphp
                                    <div class="mt-2">
                                        <div id="summernote4">{!! $package_details->important_info !!}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 8. Program Inclusion -->
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-body px-1 py-3 rounded-4">
                                <h4 class="fw-bold mb-3">6. Program Inclusion</h4>
                                <div>
                                    <input type="hidden" id="program_inclusion" name="program_inclusion">
                                    @php
                                    $plain_text_program_inclusion = html_entity_decode(
                                    strip_tags($package_details->program_inclusion),
                                    );
                                    @endphp
                                    <div class="mt-2">
                                        <div id="summernote5">{!! $package_details->program_inclusion !!}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-body px-1 py-3 rounded-4">
                                <h4 class="fw-bold mb-3">7. Program Exclusion</h4>
                                <div>
                                    <input type="hidden" id="program_exclusion" name="program_exclusion">
                                    @php
                                    $plain_text_program_inclusion = html_entity_decode(
                                    strip_tags($package_details->program_exclusion),
                                    );
                                    @endphp
                                    <div class="mt-2">
                                        <div id="summernote9">{!! $package_details->program_exclusion !!}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <script>
                        // Function to extract the src attribute from the iframe input value
                        function extractIframeSrc(iframeString) {
                            const match = iframeString.match(/src=["']([^"']+)["']/); // Regex to extract src
                            return match ? match[1] : null;
                        }

                        // Populate the iframe on page load
                        document.addEventListener('DOMContentLoaded', function() {
                            const googleMapInput = document.getElementById('google_map');
                            const mapPreviewIframe = document.getElementById('map_preview');
                            const iframeSrc = extractIframeSrc(googleMapInput.value);

                            if (iframeSrc) {
                                mapPreviewIframe.src = iframeSrc;
                            }
                        });

                        // Update iframe dynamically as user changes input
                        document.getElementById('google_map').addEventListener('input', function() {
                            const iframeSrc = extractIframeSrc(this.value);
                            const mapPreviewIframe = document.getElementById('map_preview');

                            if (iframeSrc) {
                                mapPreviewIframe.src = iframeSrc;
                            } else {
                                mapPreviewIframe.removeAttribute('src'); // Clear iframe if invalid input
                            }
                        });
                    </script>


                    <script>
                        document.getElementById('google_map').addEventListener('input', function() {
                            const inputValue = this.value;
                            const iframeSrcMatch = inputValue.match(/src=["']([^"']+)["']/); // Extract the src attribute value
                            const mapPreviewIframe = document.getElementById('map_preview');

                            if (iframeSrcMatch && iframeSrcMatch[1]) {
                                mapPreviewIframe.src = iframeSrcMatch[1]; // Set the extracted src to the iframe
                            } else {
                                mapPreviewIframe.removeAttribute('src'); // Clear the iframe if input is invalid
                            }
                        });
                    </script>



                    <!-- 10. Food Menu -->


                    <!-- 11. Amenities -->
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-body px-2 py-3 rounded-4">
                                <h4 class="fw-bold mb-3">8. Amenities</h4>
                                <div class="row g-1 mb-2">
                                    @foreach ($amenities_dts->chunk(4) as $chunk)
                                    <div class="row mb-2">
                                        @foreach ($chunk as $amenity)
                                        <div class="col-lg-3 col-md-5 col-sm-6 mb-2">
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" class="me-2"
                                                    id="amenity-{{ $amenity->id }}"
                                                    name="amenity_services[]" value="{{ $amenity->id }}"
                                                    @if (in_array((string) $amenity->id, $selectedAmenities)) checked @endif>
                                                <label class="form-check-label"
                                                    for="amenity-{{ $amenity->id }}">{{ $amenity->amenity_name }}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 12. Food & Beverages -->
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-body px-2 py-3 rounded-4">
                                <h4 class="fw-bold mb-3">9. Food & Beverages</h4>
                                <div class="row g-2 mb-2">
                                    @foreach ($foodBeverages_dts->chunk(4) as $chunk)
                                    <div class="row mb-2">
                                        @foreach ($chunk as $item)
                                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" class="me-2"
                                                    id="food-beverage-{{ $item->id }}"
                                                    name="food_beverages[]" value="{{ $item->id }}"
                                                    @if (in_array((string) $item->id, $selectedfood_beverages)) checked @endif>
                                                <label class="form-check-label"
                                                    for="food-beverage-{{ $item->id }}">{{ $item->food_beverage }}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 13. Activities -->
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-body px-2 py-3 rounded-4">
                                <h4 class="fw-bold mb-3">10. Activities</h4>
                                <div class="row g-2 mb-3">
                                    @foreach ($activities_dts->chunk(4) as $chunk)
                                    <div class="row mb-2">

                                        @foreach ($chunk as $item)
                                        <div class="col-lg-3 col-md-5 col-sm-6 mb-2">
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" class="me-2"
                                                    id="activities-{{ $item->id }}"
                                                    name="activities[]" value="{{ $item->id }}"
                                                    @if (in_array((string) $item->id, $selectedactivities)) checked @endif>
                                                <label class="form-check-label"
                                                    for="activities-{{ $item->id }}">{{ $item->activities }}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 14. Safety Features -->
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-body px-2 py-3 rounded-4">
                                <h4 class="fw-bold mb-3">11. Safety Features</h4>
                                <div class="row g-2 mb-3">
                                    @foreach ($safety_features_dts->chunk(6) as $chunk)
                                    <div class="row mb-2">

                                        @foreach ($chunk as $item)
                                        <div class="col-lg-3 col-md-5 col-sm-6 mb-2">
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" class="me-2"
                                                    id="safety_features-{{ $item->id }}"
                                                    name="safety_features[]" value="{{ $item->id }}"
                                                    @if (in_array((string) $item->id, $selectedsafety_features)) checked @endif>
                                                <label class="form-check-label"
                                                    for="safety_features-{{ $item->id }}">{{ $item->safety_features }}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>

                                <div class="row g-2 mt-3">
                                    <div class="col">
                                        <label class="fw-bold">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input check_bx" name="status"
                                                type="checkbox" id="status"
                                                {{ $package_details->status ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>

                                


                                <div class="row g-3">
                                    <div class="col-lg-3">
                                        <label class="fw-bold mb-3 ">Order <span class="text-danger">*</span></label>
                                            <input type="number" placeholder="Order" id="list_order" name="list_order" value="{{ $package_details->list_order }}" class="form-control py-2 rounded-3 shadow-sm" required>
                                    </div>
                                </div>


                                <div class="col-lg-12 text-end mt-5 py-5 ">
                                    <a href="{{ route('admin.inclusive_package_list') }}">
                                        <button type="button"
                                            class="btn btn-outline-secondary px-4 py-3 fw-bold cancel-btn text-center">
                                            Cancel
                                        </button>
                                    </a>
                                    <button type="submit"
                                        class="btn btn-primary ms-4 px-6 py-3 fw-bold submit-btn sbmtBtn text-center ">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    </form>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote1,#summernote2,#summernote3,#summernote4,#summernote5,#summernote6,#summernote7,#summernote8,#summernote10,#summernote9')
            .summernote({
                height: 200 // Set the height of the editor
            });

        // Update hidden input when Summernote content changes
        $('#summernote1').on('summernote.change', function() {
            $('#program_description').val($(this).val());
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
        $('#summernote2').summernote({
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
        $('#summernote3').summernote({
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
        $('#summernote4').summernote({
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
        $('#summernote5').summernote({
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
        $('#summernote6').summernote({
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
        $('#summernote7').summernote({
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
        $('#summernote8').summernote({
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
        $('#summernote10').summernote({
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
        $('#summernote9').summernote({
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

        // // Event listener for the "Add More Photos" button
        // $('#add-photo-btn').on('click', function() {
        //     photoCount++;
        //     const newFieldHtml = createPhotoUploadField(photoCount);
        //     $('#photo-upload-container').append(newFieldHtml);
        // });

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
                if (file.size <= 20 * 1024 * 1024) { // 2 MB limit
                    if (file.type === 'image/png' || file.type === 'image/jpeg' || file.type ===
                        'image/svg+xml') {
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
            <input type="text" name="camp_rule[]" class="form-control py-3 rounded-3 shadow-sm" placeholder="Rule And Regulations" >
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

    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('file-ip-2-preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection