<!-- resources/views/forbidden.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Forbidden</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Link to your CSS if needed -->
</head>
<body>
    <div class="container">
        <h1>Access Forbidden</h1>
        <p>You do not have permission to access this page.</p>
        <a href="{{ url('/') }}">Go to Home</a>
    </div>
</body>
</html>
