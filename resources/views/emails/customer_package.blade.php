<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us</title>

    <style>
        /* Reset default spacing */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 100px 10%;
            background-color: #fff;
            color: #333;
        }

        /* Header image styling */
        .header-image {
            width: 100%;
            height: 50vh;
            object-fit: cover;
            display: block;
            border-radius: 10px;
        }
        .footer-image{
            width: 100%; 
            /* height: 50vh; */
            object-fit: cover;
            display: block;
        }

        /* Main section styling */
        main {
            padding: 40px 0;
            display: flex;
            flex-direction: column;
            gap: 20px;
            line-height: 1.6;
        }

        main h2 {
            font-size: 2rem;
        }

        main p {
            font-size: 1.1rem;
        }

        main div p:last-child {
            font-weight: bold;
        }

        .para{
            margin-bottom: 20px;
        }
        .para1{
            margin-left: 20px;
        }

        /* Footer styling */
        footer {
            /* background-image: url("./footer-bg-image.png"); */
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            width: 100%;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 50px;
            color: white;
        }

        footer img {
            width: 120px;
            height: auto;
        }

        .footer-text {
            color: black;
            /* font-size: 1.2rem; */
        }

        /* ✅ Responsive Design */
        @media (max-width: 1024px) {
            body {
                padding: 80px 8%;
            }

            main h2 {
                font-size: 1.8rem;
            }

            main p {
                font-size: 1rem;
            }

            footer {
                padding: 0 40px;
                min-height: 100px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 60px 6%;
            }

            .header-image {
                height: 35vh;
            }

            footer {
                flex-direction: column;
                text-align: center;
                gap: 10px;
                padding: 20px;
            }

            footer img {
                width: 100px;
            }

            .footer-text {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 40px 5%;
            }

            .header-image {
                height: 30vh;
            }

            main h2 {
                font-size: 1.5rem;
            }

            main p {
                font-size: 0.95rem;
            }

            footer {
                flex-direction: column;
                padding: 15px;
                gap: 8px;
            }

            footer img {
                width: 80px;
            }

            .footer-text {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>

    <img src="{{ url('uploads/settings/fav_icon/header-image.png') }}" alt="Header" class="header-image para">

    <main>
        <p>Hello {{ $details['name'] }},</p>
        <p class="para1">Thank you for reaching out to us! We have received your submission and will get back to you shortly. Below
            are the details of your submission:</p>
        <ul class="para1">
            <li><strong>Name:</strong> {{ $details['name'] }}</li>
            <li><strong>Email:</strong> {{ $details['email'] }}</li>
            <li><strong>Phone Number:</strong> {{ $details['phone_number'] }}</li>
            <li><strong>Package:</strong> {{ $details['package_type'] }}</li>
            Package Details: <a
                href="https://innerpece.com/{{ $details['package_id'] }}/{{ $details['package_type'] }}#{{ $details['name'] }}">https://innerpece.com/{{ $details['package_id'] }}/{{ $details['package_type'] }}</a>
            <p>If you have any urgent inquiries, please contact us at bharath@innerpece.com, jay@innerpece.com or +91
                6384131642 </p>
        </ul>
        <p class="para para1">
            If you have any questions or would like to speak with our experts, feel
            free to contact us. You can update your preferences or unsubscribe at
            any time.
        </p>
        
            <p class="para1">Best regards,</p>
            <p class="para1">Innerpece</p>
            <p class="footer-text para1">innerpece.com</p>
    </main>
    <footer>
        <img src="{{ url('uploads/settings/fav_icon/footer-new.png')}}" alt="Footer Logo" class="footer-image" />
        
    </footer>
</body>

</html>
