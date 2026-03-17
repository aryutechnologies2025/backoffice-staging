<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ $settings->meta_title ?? 'Inner Pece' }}</title>
    <link rel="icon" href="{{ $settings->fav_icon ? asset($settings->fav_icon) : '' }}" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/admin_login.css">
    <link rel="stylesheet" href="/assets/css/admin_login_redesign.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body>
    <section>
        <div class="row register-sec">
            <video src="/assets/image/login/innerpece_login_bg.mp4" autoplay loop playsinline muted></video>

            <div class="col-lg-4 regiter-form px-5 py-4 rounded-5">
                <div class="logo fw-bolder text-center">
                    <img class="mb-4" src="/assets/image/login/inner_peace_logo_blue.png" style="width: 60%" alt="" loading="lazy">
                    <h3 class="hedings fw-bolder text-center text-white">Create Account</h3>
                    <h6 class="hedings text-center text-white mb-3">Register to get started</h6>
                </div>

                <form id="register_form" method="post" action="{{ route('admin.register') }}" autocomplete="off">
                    @csrf
                    
                    <div class="mt-3">
                        <input type="text" class="form-control shadow-sm p-2" id="first_name" name="first_name"
                            placeholder="First Name" required>
                    </div>

                    <div class="mt-3">
                        <input type="text" class="form-control shadow-sm p-2" id="last_name" name="last_name"
                            placeholder="Last Name" required>
                    </div>

                    <div class="mt-3">
                        <input type="email" class="form-control shadow-sm p-2" id="email" name="email"
                            placeholder="Enter your Email" required>
                    </div>

                    <div class="mt-3">
                        <input type="tel" class="form-control shadow-sm p-2" id="phone" name="phone"
                            placeholder="Phone Number" required>
                    </div>

                    <div class="mt-3">
                        <input type="password" class="form-control shadow-sm p-2" id="password" name="password"
                            placeholder="Password" required>
                    </div>

                    <div class="mt-3">
                        <input type="password" class="form-control shadow-sm p-2" id="password_confirmation" name="password_confirmation"
                            placeholder="Confirm Password" required>
                    </div>

                    <div class="mt-4 login-btn rounded text-center mb-3">
                        <button type="submit" class="btn btn-primary border-0 in-btn w-100">REGISTER</button>
                    </div>

                    <div class="text-center">
                        <p class="text-white">Already have an account? <a href="{{ route('admin.login') }}" class="text-primary">Login here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#register_form").validate({
                rules: {
                    first_name: {
                        required: true,
                        minlength: 2
                    },
                    last_name: {
                        required: true,
                        minlength: 2
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        minlength: 10
                    },
                    password: {
                        required: true,
                        minlength: 8
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 8,
                        equalTo: "#password"
                    }
                },
                messages: {
                    first_name: {
                        required: "First name is required",
                        minlength: "First name must be at least 2 characters"
                    },
                    last_name: {
                        required: "Last name is required",
                        minlength: "Last name must be at least 2 characters"
                    },
                    email: {
                        required: "Email is required",
                        email: "Please enter a valid email"
                    },
                    phone: {
                        required: "Phone number is required",
                        minlength: "Phone number must be at least 10 digits"
                    },
                    password: {
                        required: "Password is required",
                        minlength: "Password must be at least 8 characters"
                    },
                    password_confirmation: {
                        required: "Please confirm your password",
                        minlength: "Password must be at least 8 characters",
                        equalTo: "Passwords do not match"
                    }
                },
                errorPlacement: function(error, element) {
                    error.appendTo(element.parent());
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
</body>

</html>
