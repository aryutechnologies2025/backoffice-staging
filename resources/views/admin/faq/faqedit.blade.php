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
<div class="container-wrapper pt-5">
    <div class="row">
    <b><a href="/dashboard" >Dashboard</a> > <a href="/faq" >FAQ</a> > <a class="edit" >Edit</a></b>
        <br>
        <br>
        <h3 class="fw-bold">{{$title}}</h3>
    </div>
</div>
    <div class="row mb-5">
    <div class="col-lg-12">
            <div class="form-body px-4 mb-5 rounded-4">
                <form id="form_valid" action="{{ route('admin.faq_update', ['id'=>$faq_details->id]) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                    <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Question <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Question" id="question" name="question" value="{{ $faq_details->question }}" class="form-control py-2 rounded-3 shadow-sm" required>
                            </div>
                        </div>

                        <div class="g-2 mb-4">
                            <div class="col">
                            <label class=" fw-bold mb-4" id="label_textarea"> Answer <span class="text-danger">*</span></label>
                            <textarea id="textarea-description" class="container__textarea"  name="answer" placeholder="Answer" required>{{ $faq_details->answer ?? '' }}</textarea>
                        </div>
                        </div>
                        <div class="row g-2">
                            <div class="col">
                                <label class="fw-bold ">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input check_bx" type="checkbox" id="status" name="status" {{ $faq_details->status ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 text-end mt-5">
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