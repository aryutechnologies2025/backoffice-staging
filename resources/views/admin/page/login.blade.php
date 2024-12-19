<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$megatitle}}</title>
    <link rel="stylesheet" href="/assets/css/admin_login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body>

    <section class="head-sec">
        <nav class="navbar navbar-expand-lg navbar-light  px-4 py-3">
            <div class="container-fluid">
                <img style="width: 15%;" src="/assets/image/login/inner_pece_logo.png" alt="innerpece">
            </div>
        </nav>
    </section>

    <section class="banner-sec">
        <h1 class="text-white fw-bolder">{{$title}}</h1>
    </section>

    <section>
        <div class="row  register-sec">
            <div class="col regiter-form bg-white px-4  py-4 rounded shadow-lg">
                <div class="row">
                    <div class="col-lg-5">
                        <img style="width: 100%;" src="/assets/image/login/innerpece_register_img.png" alt="innerpece">
                    </div>
                    <div class="col-lg-7 px-3">
                        <h4 class="fw-bolder px-1 py-3 mb-5"> Create an account to get started </h4>
                        <form id="login_form" method="post" action="{{ route('admin.doLogin') }}" autocomplete="off">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control shadow-sm" id="email" name="email" placeholder="Enter your Email" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label"> Password</label>
                                <input type="password" class="form-control shadow-sm" id="password" name="password" placeholder="password" required>
                            </div>
                            <div class="mb-3 text-end">
                                <a href="#" class="text-decoration-none text-danger">Forgot Password?</a>
                            </div>
                            <div class="d-grid mb-5">
                                <button type="submit" class="btn btn-primary border-0 in-btn">SIGN IN</button>
                            </div>
                            <div>
                                <h6>Don't you have an account? &nbsp;<a href="{{ route('admin.signup') }}" class="in-btn border-0 btn btn-primary px-4">Register</a></h6>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#login_form").validate({
                rules: {
                    username: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 8
                    }
                },
                messages: {
                    username: {
                        required: "Please enter your username"
                    },
                    password: {
                        required: "Please enter your password",
                        minlength: "Your password must be at least 8 characters long"
                    }
                },
                errorPlacement: function(error, element) {
                    error.appendTo(element.parent()); // Display error message next to the input field
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
