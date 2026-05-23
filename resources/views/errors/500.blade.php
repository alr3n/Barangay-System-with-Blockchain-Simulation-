<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: ui-sans-serif, system-ui, sans-serif; background: #F8FAFC; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem; }
        .container { text-align: center; max-width: 400px; }
        .code { font-size: 6rem; font-weight: 800; color: #EF4444; line-height: 1; }
        h1 { font-size: 1.5rem; font-weight: 700; color: #1F2937; margin: 1rem 0 0.5rem; }
        p { color: #64748B; font-size: 0.9rem; line-height: 1.6; }
        a { display: inline-block; margin-top: 1.5rem; background: #1E3A5F; color: white; padding: 0.6rem 1.5rem; border-radius: 0.5rem; text-decoration: none; font-size: 0.875rem; font-weight: 500; }
    </style>
</head>
<body>
    <div class="container">
        <div class="code">500</div>
        <h1>Server Error</h1>
        <p>Something went wrong on our end. The system administrator has been notified. Please try again later.</p>
        <a href="{{ url('/dashboard') }}">Go to Dashboard</a>
    </div>
</body>
</html>
