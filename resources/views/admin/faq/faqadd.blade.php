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
</style>
<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a href="/stay_review">Stay Review</a> > <a class="add">Add</a></b>
    </div>

</div>
<!-- FORM -->
<div class="row mb-5">
    <div class="col-lg-12">
        <!-- INFORMATION -->
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form id="form_valid" action="{{ route('admin.faq_insert') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <div class="row g-3 mb-4">

                        <!-- Question -->
                        <div class="col-md-6">
                            <label class="fw-bold mb-2"> Question <span class="text-danger">*</span></label>
                            <input type="text" placeholder="Question" id="question" name="question" class="form-control py-2 rounded-3 shadow-sm" required value="{{ old('question') }}">
                        </div>

                        <!-- Answer -->
                        <div class="col-md-6">
                            <label class="fw-bold mb-2"> Answer <span class="text-danger">*</span></label>
                            <textarea id="textarea-description" class="form-control py-2 rounded-3 shadow-sm"  name="answer" placeholder="Answer" required style="height: 42px; resize: none;">{{ old('answer') }}</textarea>
                        </div>

                    </div>
                <!-- Status (new row) -->
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="fw-bold">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                        </div>
                    </div>
                </div>
        </div>


        <div class="col-lg-12 text-center mt-5">
            <a href="{{ route('admin.faqlist') }}">
                <button type="button" class="cancel-btn"> Cancel </button>
            </a>
            <button class="submit-btn sbmtBtn ms-4"> Submit </button>
        </div>
        </form>
    </div>
</div>
</div>

</div>
@endsection