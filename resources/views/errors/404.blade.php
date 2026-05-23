<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: ui-sans-serif, system-ui, sans-serif; background: #F8FAFC; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem; }
        .container { text-align: center; max-width: 400px; }
        .code { font-size: 6rem; font-weight: 800; color: #1E3A5F; line-height: 1; }
        h1 { font-size: 1.5rem; font-weight: 700; color: #1F2937; margin: 1rem 0 0.5rem; }
        p { color: #64748B; font-size: 0.9rem; line-height: 1.6; }
        .links { margin-top: 1.5rem; display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; }
        a.primary { background: #1E3A5F; color: white; padding: 0.6rem 1.5rem; border-radius: 0.5rem; text-decoration: none; font-size: 0.875rem; font-weight: 500; }
        a.secondary { border: 1px solid #d1d5db; color: #374151; padding: 0.6rem 1.5rem; border-radius: 0.5rem; text-decoration: none; font-size: 0.875rem; }
        a:hover { opacity: 0.85; }
    </style>
</head>
<body>
    <div class="container">
        <div class="code">404</div>
        <h1>Page Not Found</h1>
        <p>The page you are looking for might have been removed or the link is incorrect.</p>
        <div class="links">
            <a href="{{ url('/dashboard') }}" class="primary">Go to Dashboard</a>
            <a href="{{ url()->previous() }}" class="secondary">← Go Back</a>
        </div>
    </div>
</body>
</html>
