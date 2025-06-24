<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <img src="{{ asset('uploads/settings/fav_icon/innerpece_logo.svg') }}" alt="">
    <h2>Hi {{ $user->first_name }}!</h2>
    <p>Thank you for signing up. We’re excited to have you on board.</p>
    <p>Regards,<br>Innerpece </p>
</body>
</html>
