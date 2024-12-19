<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$megatitle}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/admin_signup.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body>
    <section class="head-sec">
        <nav class="navbar navbar-expand-lg navbar-light  px-4 py-3">
            <div class="container-fluid">
                <img style="width: 15%;" src="/assets/image/login/inner_pece_logo.png" alt="">
            </div>
        </nav>
    </section>

    <section class="banner-sec">
        <h1 class="text-white fw-bolder">{{$title}}</h1>
    </section>

    <!-- FORM  -->
    <section>
        <div class="row  register-sec ">
            <div class="col regiter-form bg-white px-4  py-4 rounded shadow-lg ">
                <div class="row">
                    <div class="col-lg-5">
                        <img style="width: 100%;" src="/assets/image/login/innerpece_register_img.png" alt="">
                    </div>
                    <div class="col-lg-7 px-3">
                        <h4 class="fw-bolder px-1 py-3 mb-5">Create an account to get started</h4>
                        <form id="signup_form" method="post" action="{{ route('admin.register') }}" autocomplete="off">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control p-2 " id="name" placeholder="Enter your name" required>
                                    <label for="phone" class="form-label mt-5">Phone Number</label>
                                    <!-- <div class="input-group"> -->
                                        <input type="tel" class="form-control" name="phone" id="phone" placeholder="+91xxxxxxx236" required>
                                    <!-- </div> -->
                                </div>
                                <div class="col-lg-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control p-2 " name="email" id="email" placeholder="Enter your Email" required>
                                    <label for="password" class="form-label mt-5">Create password</label>
                                    <input type="password" class="form-control p-2" name="password" id="password" placeholder="Password" required>
                                </div>
                                <div class="d-grid mt-5 mb-4">
                                    <button type="submit" class="btn btn-primary border-0 in-btn sbmtBtn">SIGN UP</button>
                                </div>
                                <div>
                                    <h6>Already have an account? &nbsp;&nbsp;&nbsp;<a href="{{ route('admin.login') }}" class="in-btn border-0 btn btn-primary px-4">Login</a></h6>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            const phoneInputField = document.querySelector("#phone");
        const phoneInput = window.intlTelInput(phoneInputField, {
            preferredCountries: ["in", "us", "gb"],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });



            // $("#signup_form").validate({
            //     rules: {
            //         name: {
            //             required: true
            //         },
            //         email: {
            //             required: true,
            //             email: true
            //         },
            //         phone: {
            //             required: true
            //         },
            //         password: {
            //             required: true,
            //             minlength: 8
            //         }
            //     },
            //     messages: {
            //         name: {
            //             required: "Please enter your name"
            //         },
            //         email: {
            //             required: "Please enter an email address",
            //             email: "Please enter a valid email address"
            //         },
            //         phone: {
            //             required: "Please enter a phone number"
            //         },
            //         password: {
            //             required: "Please enter a password",
            //             minlength: "Your password must be at least 8 characters long"
            //         }
            //     },
            //     errorPlacement: function(error, element) {
            //         error.appendTo(element.parent()); // Display error message next to the input field
            //     }
            // });

            $(".sbmtBtn").click(function(evt) {
                if ($('#signup_form').valid()) {
                    $('.sbmtBtn').attr("disabled", true);
                    $('#signup_form').submit();
                }
            });
            @if(session('success'))
            toastr.success("{{ session('success') }}");
            @endif
            @if($errors->any())
            @foreach($errors->all() as $error)
            toastr.error("{{ $error }}");
            @endforeach
            @endif
        });
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>