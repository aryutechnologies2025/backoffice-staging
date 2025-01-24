<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Form Submission Received</title>
</head>
<body>
    <p>Hello Jay,</p>
    <p>You have received a new form submission. Below are the details:</p>
    <ul>
        <li><strong>Name:</strong> {{$details['name'] }}</li>
        <li><strong>Email:</strong> {{ $details ['email'] }}</li>
        <li><strong>Phone Number:</strong> {{ $details ['phone'] }}</li>
        <li><strong>Message:</strong> {{ $details ['comments'] }}</li>
        <li><strong>Location:</strong> {{$details['location'] }}</li>
        <li><strong>days:</strong> {{ $details ['days'] }}</li>
        <li><strong>Travel Destination:</strong> {{ $details ['travel_destination'] }}</li>
        <li><strong>Cab:</strong> {{ $details ['cab_need'] }}</li>
        <li><strong>Total Members:</strong> {{ $details ['total_count'] }}</li>
        <li><strong>Total Count:</strong> {{ $details ['child_count'] }}</li>
    </ul>
        <p>Please log in to your admin dashboard to review the complete submission.</p>

        <p>Best regards,</p>
        <p>Innerpeace</p>

</body>
</html>




















