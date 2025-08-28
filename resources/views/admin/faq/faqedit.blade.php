@extends('layouts.app')
@Section('content')

<style>
    a:hover {
        color: red;
    }
    a{
        color:rgb(37, 150, 190);
    }
    .add{
        color:blue;
    }
/* Align the form with the title */
.container-wrapper {
        padding-left: 30px;
        /* Adjust as per your layout */
        padding-right: 30px;
        /* Consistent padding for both sides */
    }

    .form-body {
      
       
        border-radius: 10px;
        
    }
</style>

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
         <b><a href="/dashboard" >Dashboard</a> > <a href="/faq" >FAQ</a> > <a class="edit" >Edit</a></b>
    </div>

</div>

<div class="row mb-5">
    <div class="col-lg-12">
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
                <form id="form_valid" action="{{ route('admin.faq_update', ['id'=>$faq_details->id]) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                    <div class="row g-2 mb-4">
                            <div class="add_form col">
                                <label class="fw-bold mb-2"> Question <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Question" id="question" name="question" value="{{ $faq_details->question }}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>

                        <div class="g-2 mb-4">
                            <div class="add_form col">
                            <label class=" fw-bold mb-2" id="label_textarea"> Answer <span class="text-danger">*</span></label>
                            <textarea id="textarea-description" class="container__textarea"  name="answer" placeholder="Answer" required>{{ $faq_details->answer ?? '' }}</textarea>
                        </div>
                        </div>
                        <div class="row g-2">
                            <div class="add_form col">
                                <label class="fw-bold ">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input check_bx" type="checkbox" id="status" name="status" {{ $faq_details->status ? 'checked' : '' }}>
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