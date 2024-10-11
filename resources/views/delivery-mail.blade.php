<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $subject }}</h1>
        </div>
        <div class="content">
            <p>{{ $messages }}</p>
            <p>Thank you for your understanding.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Providence Human Capital. All rights reserved.</p>
        </div>
    </div>
</body>
</html>