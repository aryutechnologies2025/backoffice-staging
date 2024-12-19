<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <div class="row  register-sec ">
            <video src="/assets/image/login/innerpece_login_bg.mp4" autoplay loop playsinline muted></video>
        
                
                <div class="col-lg-3 regiter-form px-5 py-4 rounded-5">
                    <div class="logo fw-bolder text-center  ">
                        <img class="mb-4" src="/assets/image/login/inner_peace_logo_blue.png" style="width: 60%" alt="" loading="lazy">
                        <h3 class=" hedings fw-bolder text-center text-white ">Welcome {{$title}}</h3>
                        <h6 class=" hedings text-center text-white mb-3">Log in to continue</h6>
                    </div>
                    
                    <form id="login_form" method="post" action="{{ route('admin.doLogin') }}" autocomplete="off">
                            @csrf
                        <div class="mt-4">
                            <input type="text" class="form-control shadow-sm p-2" id="email" name="email"
                                placeholder="Enter your Email" required>
                        </div>
                        <div class="mt-4">
                            <input type="password" class="form-control shadow-sm p-2" id="password" name="password"
                                placeholder="password" required>
                        </div>
                        <div class="mt-4">
                            <!-- <label class="text-white" ><input type="checkbox" id="cb" name="" value="">Keep me signed in</label> -->
                        </div>
                        <div class=" login-btn rounded text-center mb-5">
                            <button type="submit" class="btn btn-primary  border-0 in-btn">LOGIN</button>
                        </div>
                        
                    </form>

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
                    email: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 8
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your email"
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