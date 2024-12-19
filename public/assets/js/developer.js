document.addEventListener("DOMContentLoaded", function(event) {
    const showNavbar = (toggleId, navId, bodyId, headerId) => {
        const toggle = document.getElementById(toggleId),
            nav = document.getElementById(navId),
            bodypd = document.getElementById(bodyId),
            headerpd = document.getElementById(headerId),
            logo = document.querySelector(".nav_list img");

        // Ensure all variables exist
        if (toggle && nav && bodypd && headerpd && logo) {
            // Initially set the navbar to be open
            nav.classList.add('show');
            toggle.classList.add('bx-x');
            bodypd.classList.add('body-pd');
            headerpd.classList.add('body-pd');

            toggle.addEventListener('click', () => {
                // Toggle the 'show' class on the nav element
                nav.classList.toggle('show');
                // Change the icon of the toggle button
                toggle.classList.toggle('bx-x');
                // Add padding to body and header
                bodypd.classList.toggle('body-pd');
                headerpd.classList.toggle('body-pd');
                // Toggle the logo visibility while keeping its space
                logo.classList.toggle('invisible');
            });
        }
    };

    showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');

    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll('.nav_link');

    function colorLink() {
        if (linkColor) {
            linkColor.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        }
    }

    linkColor.forEach(l => l.addEventListener('click', colorLink));
});

$(document).ready(function() {
    $('#summernote').summernote({
        height: 200 // Set the height of the editor
    });
    $('#summernote').summernote({
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






function fileHandler() {
    // Example implementation of file handling
    var fileInput = document.createElement('input');
    fileInput.setAttribute('type', 'file');
    fileInput.setAttribute('accept', 'image/*');
    fileInput.addEventListener('change', function() {
        var file = fileInput.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var range = quill.getSelection();
                quill.insertEmbed(range.index, 'image', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
    fileInput.click();
}



$(document).ready(function () {
    $(document).on("click", ".stsconfirm", function(evt) {
            var act_url = $(this).attr('data-act_url');
            var row_id = $(this).attr('data-row_id');
            var stsmode = $(this).attr('data-stsmode');
            var verify_status = $(this).attr('data-status');
            var mainEvt = $(this);
            var csrf_token = $(this).data('csrf_token');
            // console.log(csrf_token);
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to change the status!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Change!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('.btn-success').attr("disabled", true);
                    $.ajax({
                        type: 'post',
                        url: act_url,
                        data: {
                            'record_id': row_id,
                            'mode': stsmode,
                            'verify_status': verify_status,
                            '_token': csrf_token
                        },
                        dataType: 'json',
                        success: function(res) {
                            $('.btn-success').removeAttr("disabled");
                            if (res.status == '1') {
                                Swal.fire({
                                    title: "Status Changed!",
                                    icon: 'success',
                                    text: res.response,
                                    type: "success"
                                });
                                if (stsmode == '0') {
                                    mainEvt.attr('data-stsmode', '1');
                                    mainEvt.html('<button type="button" class="btn btn-hold px-5">Inactive</button>');
                                } else if (stsmode == '1') {
                                    mainEvt.attr('data-stsmode', '0');
                                    mainEvt.html('<button type="button" class="btn btn-live px-5">Active</button>');
                                } else {
                                    $('.drRideBox').hide();
                                }
                                //setTimeout(function () { $('.swal2-confirm').trigger('click'); }, 2500);
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    icon: 'error',
                                    text: res.response,
                                    type: "error"
                                });
                            }
                            if (res.status == '00') {
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }
                        }
                    });
                }
            });
    
    
        });
    
    
        $(document).on("click", ".delconfirm", function(evt) {
            var row_id = $(this).attr('data-row_id');
            var act_url = $(this).attr('data-act_url');
            var csrf_token = $(this).data('csrf_token');
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to Delete the Record!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Yes, Delete!",
                cancelButtonText: "No, Cancel!",
    
            }).then((result) => {
    
                if (result.isConfirmed) {
                    $('.btn-success').attr("disabled", true);
                    $.ajax({
                        type: 'post',
                        url: act_url,
                        data: {
                            'record_id': row_id,
                            '_token': csrf_token
                        },
                        dataType: 'json',
                        success: function(res) {
                            $('.btn-success').removeAttr("disabled");
                            if (res.status == '1') {
                                Swal.fire({
                                    title: "Deleted!",
                                    icon: 'success',
                                    text: res.response,
                                    type: "success"
                                });
                                $('#' + row_id).remove();
                                setTimeout(function() {
                                    location.reload();
                                }, 2500);
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    icon: 'error',
                                    text: res.response,
                                    type: "error"
                                });
                            }
                            if (res.status == '00') {
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }
                        }
                    });
                }
            })
        });
    });

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
                if (file.size <= 20 * 1024 * 1024) { // 2 MB limit
                    if (file.type === 'image/png' || file.type === 'image/jpeg' || file.type === 'image/svg+xml') {
                        reader.readAsDataURL(file);
                    } else {
                        $(errorMessageId).text('Please upload a valid PNG or JPEG image, or SVG image.');
                    }
                } else {
                    $(errorMessageId).text('File size exceeds 5 MB limit.');
                }
            }
        }

        // Bind onchange event to all file inputs
        $('input[type="file"]').change(function(event) {
            var number = $(this).attr('id').split('-')[2]; // Get the number from id attribute
            showPreview(event, number);
        });
    });