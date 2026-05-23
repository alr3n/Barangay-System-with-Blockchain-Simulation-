<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Expired</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: ui-sans-serif, system-ui, sans-serif; background: #F8FAFC; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem; }
        .container { text-align: center; max-width: 420px; }
        .icon { font-size: 4rem; margin-bottom: 1rem; }
        h1 { font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem; }
        p { color: #64748B; font-size: 0.9rem; line-height: 1.6; }
        a { display: inline-block; margin-top: 1.5rem; background: #1E3A5F; color: white; padding: 0.65rem 1.75rem; border-radius: 0.5rem; text-decoration: none; font-size: 0.875rem; font-weight: 500; }
        a:hover { background: #152d4a; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">⏱</div>
        <h1>Page Session Expired</h1>
        <p>Your session has expired due to inactivity or the page was open for too long. Please go back and try again.</p>
        <a href="{{ url()->previous() }}">← Go Back</a>
    </div>
</body>
</html>
