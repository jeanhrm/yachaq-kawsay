<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar sesión — Yachaq Kawsay</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { margin:0; font-family:'Nunito',sans-serif; background:#FFFFFF; }
        .auth-grid { display:grid; grid-template-columns:1fr 1fr; min-height:100vh; }
        .auth-left {
            background:#1D2458;
            padding:3rem;
            display:flex;
            flex-direction:column;
            justify-content:center;
            position:relative;
            overflow:hidden;
        }
        .auth-left::after {
            content:'';
            position:absolute;
            bottom:-80px; right:-80px;
            width:320px; height:320px;
            border-radius:50%;
            background:rgba(28,171,226,0.1);
        }
        .auth-right {
            padding:3rem;
            display:flex;
            flex-direction:column;
            justify-content:center;
            background:#FFFFFF;
            max-width:480px;
            margin:0 auto;
            width:100%;
        }
        .brand { font-family:'Fraunces',serif; font-size:1.8rem; color:white; font-weight:700; line-height:1.1; margin-bottom:0.4rem; }
        .brand-sub { font-family:'Fraunces',serif; font-style:italic; color:#1CABE2; font-size:0.88rem; margin-bottom:2rem; }
        .feature-item { display:flex; align-items:center; gap:10px; margin-bottom:10px; }
        .feature-box { width:28px; height:28px; border-radius:8px; background:rgba(28,171,226,0.15); border:1px solid rgba(28,171,226,0.25); display:flex; align-items:center; justify-content:center; font-size:0.85rem; flex-shrink:0; }
        .feature-text { font-size:0.78rem; color:rgba(255,255,255,0.6); font-weight:700; }
        .form-title { font-family:'Fraunces',serif; font-size:1.8rem; color:#1D2458; font-weight:700; margin-bottom:0.3rem; }
        .form-sub { font-size:0.78rem; color:#4A7A9A; font-weight:700; margin-bottom:1.8rem; }
        .form-label { font-size:0.72rem; color:#1D2458; font-weight:800; margin-bottom:4px; display:block; }
        .form-input {
            width:100%; border:2px solid #C8DCE8; border-radius:10px;
            padding:0.65rem 0.9rem; font-size:0.85rem;
            font-family:'Nunito',sans-serif; color:#1D2458;
            outline:none; transition:border-color 0.2s;
            margin-bottom:1rem;
        }
        .form-input:focus { border-color:#1CABE2; }
        .btn-submit {
            width:100%; background:#1D2458; color:white;
            padding:0.8rem; border-radius:10px;
            font-size:0.88rem; font-weight:900;
            border:none; cursor:pointer;
            font-family:'Nunito',sans-serif;
            transition:background 0.2s;
            margin-bottom:1rem;
        }
        .btn-submit:hover { background:#1CABE2; }
        .form-footer { text-align:center; font-size:0.76rem; color:#4A7A9A; font-weight:700; }
        .form-footer a { color:#1CABE2; font-weight:900; text-decoration:none; }
        .error-msg { font-size:0.72rem; color:#E53E3E; font-weight:700; margin-top:-8px; margin-bottom:8px; }
        @media(max-width:768px){
            .auth-grid { grid-template-columns:1fr; }
            .auth-left { display:none; }
        }
    </style>
</head>
<body class="fade-in">
<div class="auth-grid">
    <div class="auth-left">
    <div style="position:relative;z-index:1;">
        <div class="tag" style="display:inline-flex;align-items:center;gap:6px;background:rgba(28,171,226,0.15);border:1px solid rgba(28,171,226,0.3);border-radius:999px;padding:5px 14px;margin-bottom:1.5rem;width:fit-content;">
            <div style="width:7px;height:7px;border-radius:50%;background:#1CABE2;"></div>
            <span style="font-size:0.75rem;color:#1CABE2;font-weight:700;">Plataforma educativa que promueve la Indagación</span>
        </div>

        <img src="{{ asset('images/tupac.png') }}"
             alt="Tupaq"
             style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid rgba(28,171,226,0.4);margin-bottom:1.2rem;">

        <div class="brand">Yachaq<br>Kawsay</div>
        <div class="brand-sub">"El conocimiento que da vida"</div>

        <img src="{{ asset('images/hero-andino.png') }}"
             alt="Yachaq Kawsay"
             style="width:100%;max-width:340px;border-radius:16px;object-fit:cover;margin-bottom:1.5rem;">

        <div class="feature-item"><div class="feature-box">🦙</div><div class="feature-text">Tupaq te guía con IA en cada misión</div></div>
        <div class="feature-item"><div class="feature-box">🔬</div><div class="feature-text">Misiones de indagación andina</div></div>
        <div class="feature-item"><div class="feature-box">🏆</div><div class="feature-text">Insignias en quechua</div></div>
        <div class="feature-item"><div class="feature-box">👨‍🏫</div><div class="feature-text">Seguimiento docente en tiempo real</div></div>
    </div>
</div>
    <div style="display:flex;align-items:center;justify-content:center;padding:2rem;">
        <div style="width:100%;max-width:400px;">
            <div class="form-title">¡Bienvenido!</div>
            <div class="form-sub">Ingresa a tu cuenta para continuar</div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <label class="form-label">Correo electrónico</label>
                <input class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="tu@correo.com">
                @error('email')<div class="error-msg">{{ $message }}</div>@enderror

                <label class="form-label">Contraseña</label>
                <input class="form-input" type="password" name="password" required placeholder="••••••••">
                @error('password')<div class="error-msg">{{ $message }}</div>@enderror

                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;">
                    <label style="display:flex;align-items:center;gap:6px;font-size:0.75rem;color:#4A7A9A;font-weight:700;cursor:pointer;">
                        <input type="checkbox" name="remember"> Recordarme
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="font-size:0.75rem;color:#1CABE2;font-weight:800;text-decoration:none;">¿Olvidaste tu contraseña?</a>
                    @endif
                </div>

                <button type="submit" class="btn-submit">Iniciar sesión</button>
            </form>

            <div class="form-footer">
                ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate gratis</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>