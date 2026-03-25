<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>419 | Page Expired</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        /* Matching your variable colors */
        --hf-magenta: #d6007b;
        --hf-magenta-light: #f51a97;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        width: 100%;
        height: 100vh;
        font-family: 'Plus Jakarta Sans', sans-serif;
        /* Deep dark background matching the login flow */
        background-color: #0f0f10;

        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        color: #fff;
    }

    /* The 'Glass' Card matching your login container */
    .glass-card {
        width: 100%;
        max-width: 450px;
        padding: 3rem 2rem;
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        text-align: center;
        margin: 20px;
    }

    /* Magenta 419 Code */
    .code {
        font-size: 100px;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.5rem;
        color: var(--hf-magenta);
        letter-spacing: -0.05em;
        /* Glow effect */
        text-shadow: 0 0 30px rgba(214, 0, 123, 0.3);
    }

    .title {
        font-size: 1.875rem; /* text-3xl */
        font-weight: 800;
        margin-bottom: 0.5rem;
        letter-spacing: 0.025em;
    }

    .description {
        color: #d1d5db; /* text-gray-300 */
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    /* Button matching your Sign In button */
    .btn-action {
        display: block;
        width: 100%;
        padding: 0.75rem 0;
        background-color: var(--hf-magenta);
        color: white;
        font-weight: 700;
        text-decoration: none;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 10px 15px -3px rgba(214, 0, 123, 0.4);
        text-transform: none;
        letter-spacing: 0.025em;
        border: none;
        cursor: pointer;
    }

    .btn-action:hover {
        background-color: var(--hf-magenta-light);
        transform: translateY(-2px);
    }

    .footer-text {
        margin-top: 1.5rem;
        font-size: 0.875rem;
        color: #d1d5db;
    }
</style>
</head>
<body>

    <div class="glass-card">
        <div class="code">419</div>
        <h2 class="title">Session Expired</h2>

        <p class="description">
            Your security token has expired due to inactivity. <br>
            Please login again to refresh your session.
        </p>

        <a href="{{ route('login') }}" class="btn-action">
            Go to Login
        </a>

        <p class="footer-text">
            Need help? Contact your administrator.
        </p>
    </div>

</body>
</html>
