@extends('layouts.app')
@Section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
            <form class="" id="form_valid" action="#" method="POST"
                autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <div class="row g-4">
                        <div class="add_form col-md-4">
                            <label class="mb-2">Title <span class="text-danger">*</span></label>
                            <input type="text" placeholder="Title" id="title" name="title"
                                class="form-control py-2 rounded-3 shadow-sm" value="{{ old('title') }}">
                        </div>

                        <div class="add_form col-md-4">
                            <label class="mb-2">Event Type<span class="text-danger">*</span></label>
                            <select id="event_type" name="event_type"
                                class="form-select py-2 rounded-3 shadow-sm" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="public">Public</option>
                                <option value="private">Private</option>
                            </select>
                        </div>

                        <div class="add_form col-md-4">
                            <label for="timezone" class="form-label">Select Timezone</label>
                            <select id="timezone" name="timezone" class="form-select">
                                <option value="" disabled selected>Select Timezone</option>
                                <option value="America/Los_Angeles">Pacific Time - Los Angeles (GMT-07:00)</option>
                                <option value="America/Chicago">Central Time - Chicago (GMT-05:00)</option>
                                <option value="America/Toronto">Eastern Time - Toronto (GMT-04:00)</option>
                                <option value="America/New_York">Eastern Time - New York (GMT-04:00)</option>
                                <option value="America/Sao_Paulo">Brasilia Standard Time - Sao Paulo (GMT-03:00)</option>
                                <option value="Europe/London">United Kingdom Time - London (GMT+01:00)</option>
                                <option value="Europe/Madrid">Central European Time - Madrid (GMT+02:00)</option>
                                <option value="Europe/Paris">Central European Time - Paris (GMT+02:00)</option>
                                <option value="Asia/Dubai">Gulf Standard Time - Dubai (GMT+04:00)</option>
                                <option value="Asia/Kolkata">India Standard Time - Kolkata (GMT+05:30)</option>
                                <option value="Asia/Singapore">Singapore Standard Time - Singapore (GMT+08:00)</option>
                                <option value="Asia/Tokyo">Japan Standard Time - Tokyo (GMT+09:00)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="row g-4">
                        <div class="add_form col-md-4">
                            <label for="datetimePicker" class="form-label">Start Date & Time</label>
                            <div class="input-group">
                                <input type="text" class="form-control flatpickr-input" id="datetimePicker" placeholder="Select date and time">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                            </div>
                        </div>

                        <div class="add_form col-md-4">
                            <label for="datetimePicker" class="form-label">End Date & Time</label>
                            <div class="input-group">
                                <input type="text" class="form-control flatpickr-input" id="endDatetimePicker" placeholder="Select end date and time">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                            </div>
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

    // Handle form submission
    document.getElementById('datetimeForm').addEventListener('submit', function(e) {
        e.preventDefault();

        if (!datetimePicker.selectedDates[0]) {
            alert('Please select a start date and time');
            return;
        }

        const startDate = datetimePicker.selectedDates[0];
        const endDate = endDatetimePicker.selectedDates[0];

        if (endDate && endDate <= startDate) {
            alert('End date must be after start date');
            return;
        }

        alert('Date and time saved successfully!');
    });
</script>
@endsection