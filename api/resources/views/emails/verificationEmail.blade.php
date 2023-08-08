<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .header {
            background-color: #3490dc;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3490dc;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
        }
        .url {
            word-wrap: break-word;
            max-width: 100%;
            display: inline-block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Verify Your Email</h1>
    </div>
    <div class="content">
        <p>Hello {{ $user->name }},</p>
        <p>Thank you for signing up! Please click the button below to verify your email address:</p>
        <p><a class="button" href="{{$verificationUrl}}">Verify Email</a></p>
        <p>If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:</p>
        <pre><a class="url" href="{{$verificationUrl}}">{{$verificationUrl}}</a></pre>
        <p>If you did not create an account, no further action is required.</p>
        <p>Thanks,<br>The {{ config('app.name') }} Team</p>
    </div>
</div>
</body>
</html>

