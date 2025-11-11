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



    .mb-1 {
        margin-bottom: .5rem !important;
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

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a href="/mail-template">Mail Template</a> > <a
                class="add">Add</a></b>
    </div>

</div>
<div class="row mb-5">
    <div class="col-lg-12">
        <!-- INFORMATION -->
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form class="" id="form_valid" action="{{ route('admin.mailtemplatestore') }}" method="POST" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <h4 class="fw-bold mb-5">Information</h4>


                <div class="mb-3">
                    <div class="row gap-2">
                        <!-- Theme and Destination -->
                        <div class="add_form col-md-4 ">
                            <label class="mb-2">Title <span class="text-danger">*</span></label>
                            <input type="text" placeholder="Title" id="title" name="title" value="{{ old('title') }}" class="form-control py-2 rounded-3 shadow-sm" required>
                        </div>
                        <div class="g-2 mb-2">
                            <div class="add_form col">

                                <label class=" fw-bold mb-2" id="label_textarea"> Email Template<span class="text-danger">*</span></label>
                                <input type="hidden" id="email_template" name="email_template" value="{{ old('email_template') }}">
                                <div class=" mt-2">
                                    <div class="row">
                                        <div class="col-lg-10 ">
                                            <div id="summernote" style="height: 200px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>

                <div class="row g-2">
                    <div class="add_form col">
                        <h4> <label class="fw-bold">Status</label></h4>
                        <div class="form-check form-switch d-flex align-items-center">
                            <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 text-center mt-5">
                    <a href="{{ route('admin.mailtemplatelist') }}">
                        <button type="button" class="cancel-btn"> Cancel </button>
                    </a>
                    <button class="submit-btn sbmtBtn ms-4 mb-5"> Submit </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 200,
            callbacks: {
                onChange: function(contents) {
                    $('#client_review').val(contents); // Sync content to hidden input
                }
            }
        });

        // Update hidden input on form submit
        $('#form_valid').on('submit', function() {
            var content = $('#summernote').summernote('code');
            $('#email_template').val(content);

            // Validate that content is not empty
            if (!content || content === '<p><br></p>' || content.trim() === '') {
                alert('Please enter email template content');
                return false;
            }
        });
    });
</script>