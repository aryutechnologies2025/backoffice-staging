@extends('layouts.app')
@Section('content')
<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-12">
        <h3 class="fw-bold"><span class="vr"></span>&nbsp;Add Blog</h3>
        <!-- <p class="fw-light">There are many variations of passages of Lorem Ipsum</p> -->
    </div>

    <!-- FORM -->
    <div class="row mb-5">
        <div class="col">

            <!-- INFORMATION -->
            <div class="form-body px-5 py-5 rounded-4 m-auto mt-5">
                <form id="form_valid" action="{{ route('admin.blog_insert') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <h4 class="fw-bold mb-5">1.Blog Details</h4>
                    <!-- Attendee Information Section -->
                    <div class="mb-3">
                        <div class="row g-2 mb-4">
                            <div class="col-lg-6 mt-3">
                                <label class="fw-bold mb-4 ">Blog tittle <span class="text-danger">*</span></label>
                                <input type="text" id="blog_title" name="blog_title" class="form-control py-3 rounded-3 shadow-sm" placeholder="Title" required>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="row g-2 mb-5">
                        <div class="  mt-5">
                            <div class="row">
                                <div class="col-lg-12 ">
                                    <label class="fw-bold mb-4 ">Description</label>
                                    <div id="summernote"></div>
                                    <input type="hidden" id="description" name="description">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- UPLOAD IMG -->

                    <label class="fw-bold  mb-4">Blog Photo</label>
                    <div class="row d-flex mb-4">
                        <div class="col-lg-2">
                            <div class="form-input">
                                <label for="file-ip-1" class="px-4 py-3 text-center">
                                    <img class="text-center mt-3" id="file-ip-1-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg">
                                    <p class="text-center fw-light mt-3"> Add Pic</p>
                                </label>
                                <input type="file" name="img_one" id="file-ip-1" data-number="1" accept="image/*">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-input">
                                <label for="file-ip-2" class="px-4 py-3 text-center">
                                    <img class="text-center mt-3" id="file-ip-2-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg">
                                    <p class="text-center fw-light mt-3"> Add Pic </p>
                                </label>
                                <input type="file" name="img_two" id="file-ip-2" data-number="2" accept="image/*">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-input">
                                <label for="file-ip-3" class="px-4 py-3 text-center">
                                    <img class="text-center mt-3" id="file-ip-3-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg">
                                    <p class="text-center fw-light mt-3"> Add Pic</p>
                                </label>
                                <input type="file" name="img_three" id="file-ip-3" data-number="3" accept="image/*">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-input">
                                <label for="file-ip-4" class="px-4 py-3 text-center">
                                    <img class="text-center mt-3" id="file-ip-4-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg">
                                    <p class="text-center fw-light mt-3"> Add Pic</p>
                                </label>
                                <input type="file" name="img_four" id="file-ip-4" data-number="4" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <h6>*Supported <strong>Png</strong> & Jpg Not more than 2 Mb</h6>
                    <div class="row g-2 mt-5">
                        <div class="col">
                            <label class="fw-bold ">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                            </div>
                        </div>
                    </div>

                    <!-- UPLOAD IMG END -->

                    <div class="col-lg-12 text-end mt-5">
                        <a href="{{ route('admin.blog_list') }}">
                            <button type="button" class="cancel-btn"> Cancel </button>
                        </a>
                        <button class="submit-btn sbmtBtn ms-4"> Submit </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function showPreview(event, number) {
            var file = event.target.files[0];
            var reader = new FileReader();
            var previewId = "#file-ip-" + number + "-preview";
            var errorMessageId = "#file-ip-" + number + "-error";

            reader.onload = function(e) {
                $(previewId).attr('src', e.target.result);
                $(errorMessageId).text(''); // Clear any previous error message
            };

            if (file) {
                if (file.size <= 2 * 1024 * 1024) { // 2 MB limit
                    if (file.type === 'image/png' || file.type === 'image/jpeg') {
                        reader.readAsDataURL(file);
                    } else {
                        $(errorMessageId).text('Please upload a valid PNG or JPEG image.');
                    }
                } else {
                    $(errorMessageId).text('File size exceeds 2 MB limit.');
                }
            }
        }

        // Bind onchange event to all file inputs
        $('input[type="file"]').change(function(event) {
            var number = $(this).data('number'); // Use data attribute to get the number
            showPreview(event, number);
        });

    });
</script>
@endsection