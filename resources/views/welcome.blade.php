<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yachaq Kawsay — Aprende indagando</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { margin:0; background:#FFFFFF; font-family:'Nunito',sans-serif; }
        .hero { min-height:100vh; display:grid; grid-template-columns:1fr 1fr; }
        .hero-left {
            background:#1D2458;
            padding:3rem;
            display:flex;
            flex-direction:column;
            justify-content:center;
            position:relative;
            overflow:hidden;
        }
        .hero-left::after {
            content:'';
            position:absolute;
            bottom:-60px; right:-60px;
            width:300px; height:300px;
            border-radius:50%;
            background:rgba(28,171,226,0.12);
        }
        .hero-right {
            padding:3rem;
            display:flex;
            flex-direction:column;
            justify-content:center;
            background:#FFFFFF;
        }
        .tag {
            display:inline-flex;
            align-items:center;
            gap:6px;
            background:rgba(28,171,226,0.15);
            border:1px solid rgba(28,171,226,0.3);
            border-radius:999px;
            padding:5px 14px;
            margin-bottom:1.5rem;
            width:fit-content;
        }
        .tag-dot { width:7px;height:7px;border-radius:50%;background:#1CABE2; }
        .tag span { font-size:0.75rem;color:#1CABE2;font-weight:700; }
        .hero-title {
            font-family:'Fraunces',serif;
            font-size:2.8rem;
            color:white;
            line-height:1.1;
            margin-bottom:0.5rem;
        }
        .hero-subtitle {
            font-family:'Fraunces',serif;
            font-style:italic;
            color:#1CABE2;
            font-size:1rem;
            margin-bottom:1rem;
        }
        .hero-desc {
            font-size:0.88rem;
            color:rgba(255,255,255,0.55);
            line-height:1.7;
            margin-bottom:2rem;
            max-width:380px;
        }
        .btn-primary {
            background:#1CABE2;
            color:white;
            padding:0.8rem 2rem;
            border-radius:10px;
            text-decoration:none;
            font-weight:800;
            font-size:0.9rem;
            display:inline-block;
            transition:background 0.2s, transform 0.1s;
        }
        .btn-primary:hover { background:#1591C2; transform:translateY(-2px); }
        .btn-secondary {
            color:rgba(255,255,255,0.6);
            padding:0.8rem 1.5rem;
            border-radius:10px;
            text-decoration:none;
            font-weight:700;
            font-size:0.9rem;
            border:1px solid rgba(255,255,255,0.15);
            display:inline-block;
            margin-left:0.8rem;
            transition:border-color 0.2s;
        }
        .btn-secondary:hover { border-color:rgba(28,171,226,0.5); color:white; }
        .stats { display:flex;gap:2rem;margin-top:2.5rem; }
        .stat-num { font-family:'Fraunces',serif;font-size:1.6rem;color:white;font-weight:700; }
        .stat-label { font-size:0.7rem;color:rgba(255,255,255,0.45);font-weight:600; }
        .feature-cards { display:flex;flex-direction:column;gap:12px;max-width:360px; }
        .feature-card {
            background:#EEF7FC;
            border:1px solid rgba(28,171,226,0.2);
            border-radius:14px;
            padding:1.2rem;
            display:flex;
            align-items:center;
            gap:14px;
        }
        .feature-icon {
            width:44px;height:44px;border-radius:12px;
            background:#1D2458;
            display:flex;align-items:center;justify-content:center;
            font-size:1.3rem;flex-shrink:0;
        }
        .feature-title { font-size:0.85rem;font-weight:800;color:#1D2458; }
        .feature-desc { font-size:0.72rem;color:#4A7A9A;margin-top:2px; }
        .right-top { margin-bottom:2.5rem; }
        .right-title { font-family:'Fraunces',serif;font-size:2rem;color:#1D2458;font-weight:700;margin-bottom:0.3rem; }
        .right-sub { font-size:0.82rem;color:#4A7A9A;font-weight:600;margin-bottom:2rem; }
        @media(max-width:768px){
            .hero { grid-template-columns:1fr; }
            .hero-left { padding:2rem; min-height:50vh; }
            .hero-right { padding:2rem; }
        }
    </style>
</head>
<body>
<div class="hero fade-in">
    <div class="hero-left">
        <div class="tag"><div class="tag-dot"></div><span>Plataforma educativa andina</span></div>
        

        <div style="display:flex;flex-direction:column;gap:16px;">
            <img 
                src="{{ asset('images/hero-andino.png') }}" 
                alt="Yachaq Kawsay" 
                style="width:100%;max-width:480px;border-radius:20px;object-fit:cover;">
        </div>

        
        <h1 class="hero-title">Yachaq<br>Kawsay</h1>
        <p class="hero-subtitle">"El conocimiento que da vida"</p>
        <p class="hero-desc">Aprende ciencia investigando tu propio entorno andino. Con Tupaq, tu guía con IA, cada misión te acerca más a ser un verdadero indagador.</p>
        <div>
            <a href="{{ route('register') }}" class="btn-primary">Comenzar ahora</a>
            <a href="{{ route('login') }}" class="btn-secondary">Iniciar sesión</a>
        </div>
        <div class="stats">
            <div><div class="stat-num">2</div><div class="stat-label">Misiones andinas</div></div>
            <div><div class="stat-num">8</div><div class="stat-label">Insignias quechua</div></div>
            <div><div class="stat-num">5</div><div class="stat-label">Fases de indagación</div></div>
        </div>
    </div>
    <div class="hero-right">
        <div class="right-top">
            <h2 class="right-title">Aprende investigando</h2>
            <p class="right-sub">Todo alineado al Currículo Nacional del Perú</p>
        </div>
        <div class="feature-cards">
            <div class="feature-card">
                <div class="feature-icon">🦙</div>
                <div>
                    <div class="feature-title">Tupaq, tu guía con IA</div>
                    <div class="feature-desc">Te orienta, da pistas y evalúa tus respuestas en cada fase</div>
                </div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔬</div>
                <div>
                    <div class="feature-title">Misiones de indagación</div>
                    <div class="feature-desc">Problemas reales del contexto andino de Huancavelica</div>
                </div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🏆</div>
                <div>
                    <div class="feature-title">Insignias en quechua</div>
                    <div class="feature-desc">Gana insignias como Tapuq Sinchi y Kuntur Runa</div>
                </div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👨‍🏫</div>
                <div>
                    <div class="feature-title">Seguimiento docente</div>
                    <div class="feature-desc">El docente ve el progreso de cada estudiante en tiempo real</div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>