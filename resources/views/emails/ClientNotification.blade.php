<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission Confirmation</title>
</head>
<body>

     <img src="{{ asset('uploads/settings/fav_icon/innerpece_logo.svg') }}" alt="">
    <p>Hello {{$details['name']}},</p>
    <p>Thank you for reaching out to us! We have received your submission and will get back to you shortly. Below are the details of your submission:</p>
    <ul>
        <li><strong>Name:</strong> {{ $details['name'] }}</li>
        <li><strong>Email:</strong> {{$details['email'] }}</li>
        <li><strong>Phone Number:</strong> {{$details ['phone']}}</li>
        <li><strong>Travel Destination:</strong>{{$details ['travel_destination']}}</li>
        <li><strong>Message:</strong> {{$details ['comments'] }}</li>
        <li><strong>Program_information:</strong>{{$details ['program_pdf']}}</li>

            <p>If you have any urgent inquiries, please contact us at bharath@innerpece.com or +91 6384131642  </p>
        </ul>

        <p>Best regards,</p>
        <p>Innerpeace</p>
</body>
</html>


















