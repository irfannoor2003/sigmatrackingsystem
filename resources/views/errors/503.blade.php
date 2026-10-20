<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sigma · Maintenance</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&family=Share+Tech+Mono&display=swap"
        rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #06050a;
            color: #f5f2f8;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }
        .ambient { position: absolute; border-radius: 50%; filter: blur(120px); pointer-events: none; }
        .amb-1 { width: 700px; height: 700px; background: rgba(214,0,123,.08); top: -300px; left: -200px; }
        .amb-2 { width: 500px; height: 500px; background: rgba(139,92,246,.05); bottom: -200px; right: -150px; }
        .amb-3 { width: 300px; height: 300px; background: rgba(214,0,123,.06); top: 40%; left: 55%; }
        .page { position: relative; z-index: 1; min-height: 100vh; display: flex; flex-direction: column; justify-content: space-between; padding: 36px 48px; }
        .topbar { display: flex; align-items: center; justify-content: space-between; }
        .logo { display: flex; align-items: center; gap: 14px; }
        .logo-box {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, #ff2ba6, #d6007b, #8f0a5e);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 20px; font-weight: 700; color: #fff;
            box-shadow: 0 0 24px rgba(214,0,123,.5);
        }
        .logo-label { font-size: 11px; font-weight: 500; letter-spacing: .32em; text-transform: uppercase; color: #8f839e; }
        .status-live { display: flex; align-items: center; gap: 8px; font-family: 'Share Tech Mono', monospace; font-size: 10px; color: #8f839e; letter-spacing: .15em; text-transform: uppercase; }
        .status-dot { width: 7px; height: 7px; background: #ff2ba6; border-radius: 50%; box-shadow: 0 0 8px #ff2ba6; animation: pulse-dot 2.4s ease-in-out infinite; }
        @keyframes pulse-dot { 0%,100% { opacity: 1; box-shadow: 0 0 6px #ff2ba6; } 50% { opacity: .5; box-shadow: 0 0 14px #ff2ba6; } }
        .hero { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 0 20px; }
        .hero-label { font-family: 'Share Tech Mono', monospace; font-size: 11px; color: #ff2ba6; letter-spacing: .35em; text-transform: uppercase; margin-bottom: 28px; opacity: 0; animation: fadeUp .7s .3s forwards; }
        .hero-title { font-family: 'Space Grotesk', sans-serif; font-size: clamp(64px, 12vw, 140px); line-height: .9; letter-spacing: -.02em; color: #f5f2f8; opacity: 0; animation: fadeUp .8s .5s forwards; }
        .hero-title .accent { background: linear-gradient(135deg, #ff66c2, #ff2ba6, #b00568); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-sub { margin-top: 36px; font-size: 15px; font-weight: 300; color: #8f839e; max-width: 420px; line-height: 1.7; opacity: 0; animation: fadeUp .7s .9s forwards; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 640px) { .page { padding: 24px 22px; } }
    </style>
</head>

<body>
    <div class="ambient amb-1"></div>
    <div class="ambient amb-2"></div>
    <div class="ambient amb-3"></div>

    <div class="page">
        <div class="topbar">
            <div class="logo">
                <div class="logo-box">S</div>
                <div class="logo-label">Sigma Engineering Services</div>
            </div>
            <div class="status-live"><span class="status-dot"></span> Monitoring Active</div>
        </div>

        <div class="hero">
            <p class="hero-label">— System Maintenance —</p>
            <h1 class="hero-title">UNDER<br><span class="accent">maintenance</span></h1>
            <p class="hero-sub">Our infrastructure is being rebuilt for greater speed, reliability, and engineering precision. We appreciate your patience.</p>
        </div>
    </div>
</body>

</html>