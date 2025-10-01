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
<style>
    .search-results {
        position: absolute;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        width: calc(100% - 30px);
        margin-top: 5px;
    }

    .search-result-item {
        padding: 10px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }

    .search-result-item:hover {
        background-color: #f5f5f5;
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .input-field {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-field i {
        position: absolute;
        left: 10px;
        z-index: 1;
    }

    .input-field input {
        padding-left: 30px;
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
            <form id="form_valid" action="{{ route('admin.programeventstore') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <div class="row g-4">
                        <!-- Title Field -->
                        <div class="add_form col-md-4">
                            <label class="mb-2">Title <span class="text-danger">*</span></label>
                            <input type="text" placeholder="Title" id="title" name="title" class="form-control py-2 rounded-3 shadow-sm @error('title') is-invalid @enderror" value="{{ old('title') }}">

                        </div>

                        <!-- Start Date & Time -->
                        <div class="add_form col-md-3">
                            <label for="datetimePicker" class="form-label">Start Date & Time<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control flatpickr-input @error('start_datetime') is-invalid @enderror" name="start_datetime" id="datetimePicker" placeholder="Select date and time" value="{{ old('start_datetime') }}">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>

                            </div>
                        </div>

                        <!-- End Date & Time -->
                        <div class="add_form col-md-3">
                            <label for="endDatetimePicker" class="form-label">End Date & Time<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control flatpickr-input @error('end_datetime') is-invalid @enderror" name="end_datetime" id="endDatetimePicker" placeholder="Select end date and time" value="{{ old('end_datetime') }}">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cover Image -->
                <div class="row mt-4">
                    <div class="col-md-2 h-25">
                        <label for="file-ip-1" class="form-label">Cover Image<span class="text-danger">*</span></label>
                        <div class="form-input text-start pt-2 pb-0">
                            <label for="file-ip-1" class="d-block pt-4">
                                <img id="file-ip-1-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg" class="img-thumbnail">
                                <p class="mt-2">Add Pic</p>
                            </label>
                            <input type="file" id="file-ip-1" name="cover_img" class="form-control @error('cover_img') is-invalid @enderror" accept="image/png, image/jpeg, image/svg+xml">

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row g-3 pt-4">
                            <!-- Upload Image Name -->
                            <div class="add_form col-12 pt-4 forms">
                                <label>Upload Image Name <span class="text-danger">*</span></label>
                                <input type="text" id="upload_image_name" name="upload_image_name" placeholder="Rename the Photo" value="{{ old('upload_image_name') }}" class="form-control py-2 rounded-3 shadow-sm w-50 @error('upload_image_name') is-invalid @enderror">

                            </div>

                            <!-- Alternate Image Name -->
                            <div class="add_form col-12 forms">
                                <label>Alternate Image Name <span class="text-danger">*</span></label>
                                <input type="text" id="alternate_image_name" name="alternate_image_name" placeholder="Alternate Name" value="{{ old('alternate_image_name') }}" class="form-control py-2 rounded-3 shadow-sm w-50 @error('alternate_image_name') is-invalid @enderror">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="row g-4">
                        <!-- Hosted By -->
                        <div class="add_form col-md-4">
                            <label class="mb-2">Hosted By<span class="text-danger">*</span></label>
                            <input type="text" placeholder="Hosted By" id="hosted_by" name="hosted_by" class="form-control py-2 rounded-3 shadow-sm @error('hosted_by') is-invalid @enderror" value="{{ old('hosted_by') }}">

                        </div>

                        <!-- Welcome Message -->
                        <div class="add_form col-md-6">
                            <label class="mb-2">Welcome msg<span class="text-danger">*</span></label>
                            <input type="text" placeholder="Welcome Msg" id="welcome_msg" name="welcome_msg" class="form-control py-2 rounded-3 shadow-sm @error('welcome_msg') is-invalid @enderror" value="{{ old('welcome_msg') }}">

                        </div>

                        <!-- Embed Map -->
                        <div class="add_form col-md-6">
                            <label class="mb-2">Location Address<span class="text-danger">*</span></label>
                            <textarea placeholder="Location Address" id="location_address" name="location_address" class="form-control py-2 rounded-3 shadow-sm @error('location_address') is-invalid @enderror" rows="4" style="width: 100%; height: 120px;">{{ old('location_address') }}</textarea>

                        </div>

                        <!-- Embed Map -->
                        <div class="add_form col-md-6">
                            <label class="mb-2">Location Iframe<span class="text-danger">*</span></label>
                            <textarea placeholder="Location Iframe" id="embed_map" name="embed_map" class="form-control py-2 rounded-3 shadow-sm @error('embed_map') is-invalid @enderror" rows="4" style="width: 100%; height: 120px;">{{ old('embed_map') }}</textarea>

                        </div>


                        <!-- Send Link -->
                        <div class="add_form col-md-4">
                            <label class="mb-2">Send Link<span class="text-danger">*</span></label>
                            <input type="text" placeholder="Add map send a link" id="send_link" name="send_link" class="form-control py-2 rounded-3 shadow-sm @error('send_link') is-invalid @enderror" value="{{ old('send_link') }}">

                        </div>


                        <!-- Event Description -->
                        <div class="col-md-12">
                            <label class="form-label">About event <span class="text-danger">*</span></label>
                            <textarea id="event_description" name="event_description" style="display:none;">{{ old('event_description') }}</textarea>
                            <div id="eventdescription" style="height: 200px;" class="@error('event_description') is-invalid @enderror"></div>

                        </div>


                        <div class="row g-2">
                            <div class="add_form col">
                                <h4> <label class="fw-bold">Status</label></h4>
                                <div class="form-check form-switch d-flex align-items-center">
                                    <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                                </div>
                            </div>
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
<script>
    $(document).ready(function() {
        // Initialize Summernote editor
        $('#eventdescription').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 200,
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

        // Initialize Flatpickr with datetime plugin
        const datetimePicker = flatpickr("#datetimePicker", {
            enableTime: true,
            dateFormat: "Y-m-d h:i K",
            altFormat: "F j, Y at h:i K",
            time_24hr: false,
            minuteIncrement: 1,
            onChange: function(selectedDates, dateStr, instance) {
                updatePreview();
            }
        });

        const endDatetimePicker = flatpickr("#endDatetimePicker", {
            enableTime: true,
            dateFormat: "Y-m-d h:i K",
            altFormat: "F j, Y at h:i K",
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

    });
</script>
@endsection