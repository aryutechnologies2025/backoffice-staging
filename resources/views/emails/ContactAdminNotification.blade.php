<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Contact Us</title>
</head>
<body>

     <img src="{{ asset('uploads/settings/fav_icon/innerpece_logo.svg') }}" alt="">
     <p>Hello Jay,</p>
     <p>You have received a new Contact Form submission. Below are the details:</p>
    <ul>
        <li><strong>Name:</strong> {{ $details['name'] }}</li>
        <li><strong>Email:</strong> {{$details['email'] }}</li>
        <li><strong>Phone Number:</strong> {{$details ['phone']}}</li>
        <li><strong>Message:</strong> {{$details ['comments'] }}</li>

        <p>Please log in to your admin dashboard to review the complete submission.</p>
        </ul>

        <p>Best regards,</p>
        <p>Innerpeace</p>
</body>
</html>


















