<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sigma Engineering Services | Maintenance</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&family=Share+Tech+Mono&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --accent: #d6007b;
    --accent-dim: rgba(214, 0, 123, 0.15);
    --text: #e8e8e8;
    --text-muted: #5a5a5a;
    --bg: #080809;
    --surface: rgba(255,255,255,0.03);
  }

  body {
    background: var(--bg);
    color: var(--text);
    font-family: 'DM Sans', sans-serif;
    min-height: 100vh;
    overflow: hidden;
    position: relative;
  }

  /* ─── Ambient Light Blobs ─── */
  .ambient { position: absolute; border-radius: 50%; filter: blur(120px); pointer-events: none; z-index: 0; }
  .amb-1 { width: 700px; height: 700px; background: rgba(214, 0, 123, 0.04); top: -300px; left: -200px; }
  .amb-2 { width: 500px; height: 500px; background: rgba(100, 100, 255, 0.02); bottom: -200px; right: -150px; }
  .amb-3 { width: 300px; height: 300px; background: rgba(214, 0, 123, 0.03); top: 40%; left: 55%; }

  /* ─── Layout ─── */
  .page {
    position: relative; z-index: 1;
    width: 100%; min-height: 100vh;
    display: flex; flex-direction: column;
    justify-content: space-between;
    padding: 36px 48px;
  }

  /* ─── Top Bar ─── */
  .topbar { display: flex; align-items: center; justify-content: space-between; }
  .logo { display: flex; align-items: center; gap: 14px; }
  .logo-box {
    width: 38px; height: 38px;
    border: 1.5px solid var(--accent);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 22px; color: var(--accent);
    position: relative;
  }
  .logo-box::before {
    content: '';
    position: absolute; inset: -4px;
    border: 1px solid var(--accent-dim);
  }
  .logo-label {
    font-family: 'DM Sans', sans-serif;
    font-size: 11px; font-weight: 500;
    letter-spacing: 0.32em;
    text-transform: uppercase; color: var(--text-muted);
  }

  .status-live {
    display: flex; align-items: center; gap: 8px;
    font-family: 'Share Tech Mono', monospace;
    font-size: 10px; color: var(--text-muted);
    letter-spacing: 0.15em; text-transform: uppercase;
  }
  .status-dot {
    width: 7px; height: 7px;
    background: var(--accent);
    border-radius: 50%;
    box-shadow: 0 0 8px var(--accent);
    animation: pulse-dot 2.4s ease-in-out infinite;
  }
  @keyframes pulse-dot {
    0%, 100% { opacity: 1; box-shadow: 0 0 6px var(--accent); }
    50% { opacity: 0.5; box-shadow: 0 0 14px var(--accent); }
  }

  /* ─── Center Hero ─── */
  .hero { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 0 20px; }

  .hero-label {
    font-family: 'Share Tech Mono', monospace;
    font-size: 11px; color: var(--accent);
    letter-spacing: 0.35em; text-transform: uppercase;
    margin-bottom: 28px;
    opacity: 0; animation: fadeUp 0.7s 0.3s forwards;
  }

  .hero-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(80px, 15vw, 180px);
    line-height: 0.85;
    letter-spacing: -0.02em;
    color: var(--text);
    opacity: 0; animation: fadeUp 0.8s 0.5s forwards;
  }
  .hero-title .accent { color: var(--accent); position: relative; }

  .hero-sub {
    margin-top: 36px;
    font-size: 15px; font-weight: 300;
    color: var(--text-muted);
    max-width: 420px; line-height: 1.7;
    opacity: 0; animation: fadeUp 0.7s 0.9s forwards;
  }



  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @media (max-width: 640px) {
    .page { padding: 24px 22px; }
    .hero-title { font-size: 80px; }
  }
</style>
</head>
<body>

<div class="ambient amb-1"></div>
<div class="ambient amb-2"></div>
<div class="ambient amb-3"></div>
<div class="scanline"></div>

<div class="page">
  <div class="topbar">
    <div class="logo">
      <div class="logo-box">Σ</div>
      <div class="logo-label">Sigma Engineering Services </div>
    </div>
    <div class="status-live">
      <span class="status-dot"></span>
      Monitoring Active
    </div>
  </div>

  <div class="hero">
    <p class="hero-label">— System Maintenance —</p>
    <h1 class="hero-title">UNDER<br><span class="accent">maintenance</span></h1>
    <p class="hero-sub">Our infrastructure is being rebuilt for greater speed, reliability, and engineering precision. We appreciate your patience.</p>
  </div>
</div>

</body>
</html>
