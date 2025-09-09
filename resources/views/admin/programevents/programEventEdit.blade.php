@extends('layouts.app')
@Section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .datetime-container {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .datetime-section {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 25px;
        border-left: 4px solid #6c5ce7;
    }

    .section-title {
        color: #6c5ce7;
        margin-bottom: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }

    .flatpickr-input {
        border-radius: 8px;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        transition: all 0.3s;
        width: 100%;
    }

    .flatpickr-input:focus {
        border-color: #6c5ce7;
        box-shadow: 0 0 0 0.25rem rgba(108, 92, 231, 0.25);
        outline: none;
    }

    .input-group {
        position: relative;
    }

    .input-group-text {
        background: transparent;
        border: 2px solid #e9ecef;
        border-left: none;
        border-radius: 0 8px 8px 0;
    }

    .input-group .form-control:focus+.input-group-text {
        border-color: #6c5ce7;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6c5ce7, #8c7ae6);
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 92, 231, 0.4);
    }

    .preview-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    .preview-title {
        color: #6c5ce7;
        font-weight: 600;
        margin-bottom: 15px;
        border-bottom: 2px solid #f1f3f5;
        padding-bottom: 10px;
    }

    .time-display {
        display: flex;
        justify-content: space-between;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
    }

    .time-label {
        font-weight: 600;
        color: #495057;
    }

    .time-value {
        font-weight: 700;
        color: #6c5ce7;
    }

    @media (max-width: 768px) {
        .form-body {
            padding: 20px;
        }
    }

    .input-section {
        max-width: 600px;
        margin: 24px auto
    }

    .input-field {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        padding: 8px;
        border-radius: 6px
    }

    .input-field input {
        flex: 1;
        border: 0;
        outline: none;
        padding: 8px
    }

    .search-results {
        border: 1px solid #eee;
        max-height: 220px;
        overflow: auto;
        margin-top: 6px;
        border-radius: 6px
    }

    .search-results div {
        padding: 10px;
        cursor: pointer
    }

    .search-results div:hover {
        background: #f5f5f5
    }

    #selectedLocationContainer {
        max-width: 600px;
        margin: 12px auto;
        padding: 12px;
        border-radius: 6px;
        background: #fafafa;
        border: 1px solid #eee
    }

    #eventdescription {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 10px;
        background-color: #fff;
    }
</style>
<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a href="{{ route('admin.programeventslist') }}">Events</a> > <a
                class="add">Add</a></b>
    </div>

</div>
<div class="row mb-5">
    <div class="col-lg-12">
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form id="form_valid" action="{{ route('admin.programeventupdate', $programdetails->id) }}" method="POST"
                autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <div class="row g-4">
                        <div class="add_form col-md-4">
                            <label class="mb-2">Title <span class="text-danger">*</span></label>
                            <input type="text" placeholder="Title" id="title" name="title"
                                class="form-control py-2 rounded-3 shadow-sm" value="{{ $programdetails->title }}">
                        </div>

                        <div class="add_form col-md-4">
                            <label class="mb-2">Event Type<span class="text-danger">*</span></label>
                            <select id="event_type" name="event_type"
                                class="form-select py-2 rounded-3 shadow-sm" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="public" {{ $programdetails->event_type == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ $programdetails->event_type == 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>

                        <div class="add_form col-md-4">
                            <label for="timezone" class="form-label">Select Timezone</label>
                            <select id="timezone" name="timezone" class="form-select">
                                <option value="" disabled {{ empty($programdetails->timezone) ? 'selected' : '' }}>Select Timezone</option>

                                <option value="america_los_angeles" {{ $programdetails->timezone == 'america_los_angeles' ? 'selected' : '' }}>
                                    Pacific Time - Los Angeles (GMT-07:00)
                                </option>

                                <option value="america_chicago" {{ $programdetails->timezone == 'america_chicago' ? 'selected' : '' }}>
                                    Central Time - Chicago (GMT-05:00)
                                </option>

                                <option value="america_toronto" {{ $programdetails->timezone == 'america_toronto' ? 'selected' : '' }}>
                                    Eastern Time - Toronto (GMT-04:00)
                                </option>

                                <option value="america_new_york" {{ $programdetails->timezone == 'america_new_york' ? 'selected' : '' }}>
                                    Eastern Time - New York (GMT-04:00)
                                </option>

                                <option value="america_sao_paulo" {{ $programdetails->timezone == 'america_sao_paulo' ? 'selected' : '' }}>
                                    Brasilia Standard Time - Sao Paulo (GMT-03:00)
                                </option>

                                <option value="europe_london" {{ $programdetails->timezone == 'europe_london' ? 'selected' : '' }}>
                                    United Kingdom Time - London (GMT+01:00)
                                </option>

                                <option value="europe_madrid" {{ $programdetails->timezone == 'europe_madrid' ? 'selected' : '' }}>
                                    Central European Time - Madrid (GMT+02:00)
                                </option>

                                <option value="europe_paris" {{ $programdetails->timezone == 'europe_paris' ? 'selected' : '' }}>
                                    Central European Time - Paris (GMT+02:00)
                                </option>

                                <option value="asia_dubai" {{ $programdetails->timezone == 'asia_dubai' ? 'selected' : '' }}>
                                    Gulf Standard Time - Dubai (GMT+04:00)
                                </option>

                                <option value="asia_kolkata" {{ $programdetails->timezone == 'asia_kolkata' ? 'selected' : '' }}>
                                    India Standard Time - Kolkata (GMT+05:30)
                                </option>

                                <option value="asia_singapore" {{ $programdetails->timezone == 'asia_singapore' ? 'selected' : '' }}>
                                    Singapore Standard Time - Singapore (GMT+08:00)
                                </option>

                                <option value="asia_tokyo" {{ $programdetails->timezone == 'asia_tokyo' ? 'selected' : '' }}>
                                    Japan Standard Time - Tokyo (GMT+09:00)
                                </option>
                            </select>

                        </div>
                    </div>
                </div>


                <!-- Cover Image -->
                <div class="row mt-4">
                    <div class="col-md-2 h-25">
                        <label for="file-cover" class="form-label">Cover Image</label>
                        <div class="form-input text-start pt-2 pb-0">
                            <label for="file-cover" class="d-block pt-4">
                                @if ($programdetails->cover_img)
                                <img id="file-cover-preview" src="{{ asset($programdetails->cover_img) }}"
                                    alt="Cover Image" class="rounded-3 shadow-sm"
                                    style="max-width: 250px; max-height: 250px; object-fit: cover;">
                                @else
                                <img id="file-cover-preview"
                                    src="/assets/image/dashboard/innerpece_addpic_icon.svg" alt="Add Pic"
                                    class="rounded-3 shadow-sm"
                                    style="max-width: 250px; max-height: 250px;">
                                @endif
                                <p class="mt-2">Add Pic</p>
                            </label>
                            <input type="file" id="file-cover" name="cover_img" class="form-control"
                                accept="image/png, image/jpeg, image/svg+xml"
                                onchange="previewCoverImage(event)">
                            <div id="file-cover-error" class="text-danger"></div>
                            <!-- <small class="text-danger d-block mt-2 text-center">* Upload size
                                        [1200x120]</small> -->
                        </div>
                    </div>
                </div>


                <!-- Gallery Images -->
                <div id="photo-upload-container" class="row g-3">
                    <label class="fw-bold mt-4">Gallery Image</label>

                    @php
                    $images = !empty($programdetails->events_package_images)
                    ? json_decode($programdetails->events_package_images, true)
                    : [];
                    $imageCount = count($images);
                    @endphp

                    @if ($imageCount > 0)
                    @foreach ($images as $key => $image)
                    <div class="col-md-2 col-sm-4 col-6 photo-upload-field"
                        id="photo-field-{{ $key }}">
                        <div class="form-input text-center">
                            <label for="file-ip-{{ $key }}">
                                <img class="img-fluid mt-3" id="file-ip-{{ $key }}-preview"
                                    src="{{ asset($image) }}" alt="Image Preview">
                                <p class="fw-light mt-2">Edit Pic</p>
                            </label>
                            <input type="file" name="img_{{ $key }}"
                                id="file-ip-{{ $key }}" data-number="{{ $key }}"
                                accept="image/*" onchange="previewGalleryImage(event, this)">
                            <button type="button" class="btn btn-danger btn-sm mt-2 delete-photo-btn"
                                data-key="{{ $key }}"
                                data-image="{{ $image }}">Delete</button>
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
                    <button id="add-photo-btn" type="button" class="btn btn-primary">Add More
                        Photos</button>
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

                <div class="mb-3">
                    <div class="row g-4">
                        <div class="add_form col-md-3">
                            <label for="datetimePicker" class="form-label">Start Date & Time</label>
                            <div class="input-group">
                                <input type="text" class="form-control flatpickr-input" name="start_datetime" id="datetimePicker" placeholder="Select date and time" value="{{ $programdetails->start_datetime }}">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                            </div>
                        </div>

                        <div class="add_form col-md-3">
                            <label for="endDatetimePicker" class="form-label">End Date & Time</label>
                            <div class="input-group">
                                <input type="text" class="form-control flatpickr-input" name="end_datetime" id="endDatetimePicker" placeholder="Select end date and time" value="{{ $programdetails->end_datetime }}">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Enter location or virtual link</label>
                            <div class="input-field">
                                <i class="bi bi-search" style="margin-right:8px"></i>
                                <input type="text" id="locationSearch" name="location_name" placeholder="Search for a location..." value="{{$programdetails->location_name}}">
                            </div>
                            <div id="searchResults" class="search-results"></div>

                            <!-- ✅ Hidden fields that will be filled dynamically -->
                            <input type="hidden" name="location_address" id="location_address" value="{{$programdetails->	location_address}}">
                            <input type="hidden" name="latitude" id="latitude" value="{{$programdetails->latitude}}">
                            <input type="hidden" name="longitude" id="longitude" value="{{$programdetails->	longitude}}">
                            <input type="hidden" name="location_type" id="location_type" value="{{$programdetails->	location_type}}">

                            <div id="selectedLocationContainer">
                                <div class="no-locations">Search for a location to select</div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea id="event_description" name="event_description" style="display:none;"></textarea>
                            <div id="eventdescription" style="height: 200px;"> {!! old('event_description', $programdetails->event_description) !!}</div>
                        </div>


                        <div class="col-lg-12 text-center mt-5">
                            <a href="{{ route('admin.programeventslist') }}">
                                <button type="button" class="cancel-btn"> Cancel </button>
                            </a>
                            <button class="submit-btn sbmtBtn ms-4 mb-5"> Submit </button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Load Google Maps JS with Places library -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places"></script>
<script>
    $(document).ready(function() {

        $('#eventdescription')
            .summernote({
                height: 200 // Set the height of the editor
            });
        $('#eventdescription').summernote({
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

        $('#timezone').select2({
            placeholder: "Search for a timezone",
            allowClear: true
        });
    });


    // Initialize Flatpickr with datetime plugin
    const datetimePicker = flatpickr("#datetimePicker", {
        enableTime: true,
        dateFormat: "Y-m-d h:i K", // This format shows AM/PM
        altFormat: "F j, Y at h:i K", // Display format with AM/PM
        time_24hr: false,
        minuteIncrement: 1,
        onChange: function(selectedDates, dateStr, instance) {
            updatePreview();
        }
    });

    const endDatetimePicker = flatpickr("#endDatetimePicker", {
        enableTime: true,
        dateFormat: "Y-m-d h:i K", // This format shows AM/PM
        altFormat: "F j, Y at h:i K", // Display format with AM/PM
        time_24hr: false,
        minuteIncrement: 1,
        onChange: function(selectedDates, dateStr, instance) {
            updatePreview();
        }
    });

    // Update preview in real-time
    function updatePreview() {
        const startDate = datetimePicker.selectedDates[0];
        const endDate = endDatetimePicker.selectedDates[0];

        if (startDate) {
            const formattedStart = formatDateTime(startDate);
            document.getElementById('previewStart').textContent = formattedStart;
        } else {
            document.getElementById('previewStart').textContent = 'Not selected';
        }

        if (endDate) {
            const formattedEnd = formatDateTime(endDate);
            document.getElementById('previewEnd').textContent = formattedEnd;

            // Calculate duration if both dates are selected
            if (startDate) {
                const duration = calculateDuration(startDate, endDate);
                document.getElementById('previewDuration').textContent = duration;
            }
        } else {
            document.getElementById('previewEnd').textContent = 'Not selected';
            document.getElementById('previewDuration').textContent = '-';
        }
    }

    // Format date to readable string
    function formatDateTime(date) {
        return date.toLocaleString('en-US', {
            weekday: 'short',
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }

    // Calculate duration between two dates
    function calculateDuration(startDate, endDate) {
        const diffMs = endDate - startDate;
        const diffHrs = Math.floor((diffMs % 86400000) / 3600000);
        const diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000);

        if (diffMs < 0) {
            return "End date must be after start date";
        }

        return `${diffHrs} hours, ${diffMins} minutes`;
    }

    $(document).ready(function() {
        const input = document.getElementById('locationSearch');
        const selected = document.getElementById('selectedLocationContainer');
        let map, marker;

        const autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['geocode'] // limit to addresses/places
        });

        // ✅ Handle Google suggestion selection
        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();

            if (!place.geometry) {
                handleCustomAddress(input.value);
                return;
            }

            showLocation(
                place.formatted_address || place.name,
                place.geometry.location.lat(),
                place.geometry.location.lng()
            );
        });

        // ✅ Handle Enter key for manual input
        input.addEventListener("keydown", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                handleCustomAddress(input.value);
            }
        });

        // 🔹 Save hidden inputs
        function saveLocation(location) {
            $("#location_address").val(location.formatted_address);
            $("#latitude").val(location.latitude ?? "");
            $("#longitude").val(location.longitude ?? "");
            $("#location_type").val(location.type);
        }

        // 🔹 Custom address
        function handleCustomAddress(addr) {
            if (!addr) return;
            selected.innerHTML = `
            <div style="max-width:600px;margin:0 auto;">
                <strong>${addr}</strong> <span style="color:gray">(Custom Address)</span>
            </div>
        `;
            saveLocation({
                formatted_address: addr,
                latitude: null,
                longitude: null,
                type: "custom"
            });
        }

        // 🔹 Google address → show map
        function showLocation(addr, lat, lng) {
            selected.innerHTML = `
            <div style="max-width:600px;margin:0 auto;">
                <strong>${addr}</strong>
                <div id="map" style="width:100%;height:300px;margin-top:10px;border-radius:8px;overflow:hidden"></div>
            </div>
        `;

            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: lat,
                    lng: lng
                },
                zoom: 15
            });

            marker = new google.maps.Marker({
                position: {
                    lat: lat,
                    lng: lng
                },
                map: map,
                title: addr
            });

            saveLocation({
                formatted_address: addr,
                latitude: lat,
                longitude: lng,
                type: "google"
            });
        }

        // ✅ Preload location on edit (if values exist)
        const existingLat = parseFloat($("#latitude").val());
        const existingLng = parseFloat($("#longitude").val());
        const existingAddr = $("#location_address").val();
        const existingType = $("#location_type").val();

        if (existingAddr) {
            if (existingType === "google" && !isNaN(existingLat) && !isNaN(existingLng)) {
                showLocation(existingAddr, existingLat, existingLng);
            } else {
                handleCustomAddress(existingAddr);
            }
        }
    });
</script>
@endsection