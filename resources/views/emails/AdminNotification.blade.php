<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $details['subject']}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eaeaea;
        }
        .header img {
            max-width: 200px;
            height: auto;
        }
        .content {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #eaeaea;
            color: #666;
            font-size: 12px;
        }
        .footer p {
            margin: 5px 0;
        }
        .powered-by {
            font-size: 11px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
     <div class="header">
    <img src="{{ env('APP_URL') }}uploads/settings/fav_icon/innerpece_logo1.png" alt="Logo" style="max-width:150px;">
</div>
    
    <!-- Main Content Section -->
    <div class="content">
        {!! $body !!}
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <p>Copyright © {{ date('Y') }} by Innerpece. All Rights Reserved</p>
        <p class="powered-by">Powered by Aryu Technologies</p>
    </div>
</body>
</html>