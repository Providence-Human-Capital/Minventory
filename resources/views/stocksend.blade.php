<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $subject }}</h1>
        </div>
        <div class="content">
            <p>Dear {{ $requester }},</p>
            <p>{{ $messages }}</p>
            <p>Thank you for your understanding.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Providence Human . All rights reserved.</p>
        </div>
    </div>
</body>
</html>