<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yachaq Kawsay — Aprende indagando</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: var(--puna-dark);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'DM Sans', sans-serif;
        }

        .hero {
            text-align: center;
            padding: 3rem 2rem;
            max-width: 600px;
        }

        .hero-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            display: block;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-10px); }
        }

        .hero h1 {
            font-family: 'Fraunces', serif;
            font-size: 3rem;
            color: var(--arena);
            margin: 0 0 0.5rem;
            line-height: 1.1;
        }

        .hero .subtitle {
            font-size: 1.1rem;
            color: var(--arena-dark);
            margin-bottom: 0.5rem;
        }

        .hero .quechua {
            font-family: 'Fraunces', serif;
            font-style: italic;
            color: var(--dorado);
            font-size: 1rem;
            margin-bottom: 2.5rem;
            display: block;
        }

        .btn-primary {
            background: var(--terracota);
            color: white;
            padding: 0.9rem 2.5rem;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            margin-right: 1rem;
            transition: background 0.2s, transform 0.1s;
            display: inline-block;
        }

        .btn-primary:hover {
            background: var(--terracota-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            color: var(--arena);
            padding: 0.9rem 2rem;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 400;
            font-size: 1rem;
            border: 1px solid rgba(245,237,216,0.3);
            transition: border-color 0.2s;
            display: inline-block;
        }

        .btn-secondary:hover {
            border-color: var(--dorado);
            color: var(--dorado);
        }

        .features {
            display: flex;
            gap: 1.5rem;
            margin-top: 4rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .feature {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(245,237,216,0.1);
            border-radius: 1rem;
            padding: 1.5rem;
            width: 160px;
            text-align: center;
        }

        .feature .icon { font-size: 2rem; margin-bottom: 0.5rem; }
        .feature .label {
            font-size: 0.8rem;
            color: var(--arena-dark);
            line-height: 1.4;
        }

        .pattern {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image:
                repeating-linear-gradient(
                    45deg,
                    transparent,
                    transparent 40px,
                    rgba(201,149,42,0.03) 40px,
                    rgba(201,149,42,0.03) 41px
                );
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="pattern"></div>

    <div class="hero fade-in">
        <span class="hero-icon">🏔️</span>
        <h1>Yachaq Kawsay</h1>
        <p class="subtitle">Aprende ciencia desde los Andes</p>
        <span class="quechua">"El conocimiento que da vida"</span>

        <div>
            <a href="{{ route('register') }}" class="btn-primary">Comenzar ahora</a>
            <a href="{{ route('login') }}" class="btn-secondary">Iniciar sesión</a>
        </div>

        <div class="features">
            <div class="feature">
                <div class="icon">🦙</div>
                <div class="label">Tupaq, tu guía andino con IA</div>
            </div>
            <div class="feature">
                <div class="icon">🔬</div>
                <div class="label">Misiones de indagación científica</div>
            </div>
            <div class="feature">
                <div class="icon">🏆</div>
                <div class="label">Insignias en quechua</div>
            </div>
            <div class="feature">
                <div class="icon">👨‍🏫</div>
                <div class="label">Seguimiento docente en tiempo real</div>
            </div>
        </div>
    </div>
</body>
</html>