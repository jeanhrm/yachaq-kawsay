<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro — Yachaq Kawsay</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { margin:0; font-family:'Nunito',sans-serif; background:#FFFFFF; }
        .auth-grid { display:grid; grid-template-columns:1fr 1fr; min-height:100vh; }
        .auth-left { background:#1D2458; padding:3rem; display:flex; flex-direction:column; justify-content:center; position:relative; overflow:hidden; }
        .auth-left::after { content:''; position:absolute; bottom:-80px; right:-80px; width:320px; height:320px; border-radius:50%; background:rgba(28,171,226,0.1); }
        .brand { font-family:'Fraunces',serif; font-size:1.8rem; color:white; font-weight:700; line-height:1.1; margin-bottom:0.4rem; }
        .brand-sub { font-family:'Fraunces',serif; font-style:italic; color:#1CABE2; font-size:0.88rem; margin-bottom:2rem; }
        .feature-item { display:flex; align-items:center; gap:10px; margin-bottom:10px; }
        .feature-box { width:28px; height:28px; border-radius:8px; background:rgba(28,171,226,0.15); border:1px solid rgba(28,171,226,0.25); display:flex; align-items:center; justify-content:center; font-size:0.85rem; flex-shrink:0; }
        .feature-text { font-size:0.78rem; color:rgba(255,255,255,0.6); font-weight:700; }
        .form-title { font-family:'Fraunces',serif; font-size:1.8rem; color:#1D2458; font-weight:700; margin-bottom:0.3rem; }
        .form-sub { font-size:0.78rem; color:#4A7A9A; font-weight:700; margin-bottom:1.5rem; }
        .form-label { font-size:0.72rem; color:#1D2458; font-weight:800; margin-bottom:4px; display:block; }
        .form-input { width:100%; border:2px solid #C8DCE8; border-radius:10px; padding:0.6rem 0.9rem; font-size:0.85rem; font-family:'Nunito',sans-serif; color:#1D2458; outline:none; transition:border-color 0.2s; margin-bottom:0.8rem; }
        .form-input:focus { border-color:#1CABE2; }
        .btn-submit { width:100%; background:#1D2458; color:white; padding:0.8rem; border-radius:10px; font-size:0.88rem; font-weight:900; border:none; cursor:pointer; font-family:'Nunito',sans-serif; transition:background 0.2s; margin-bottom:1rem; }
        .btn-submit:hover { background:#1CABE2; }
        .form-footer { text-align:center; font-size:0.76rem; color:#4A7A9A; font-weight:700; }
        .form-footer a { color:#1CABE2; font-weight:900; text-decoration:none; }
        .error-msg { font-size:0.72rem; color:#E53E3E; font-weight:700; margin-top:-6px; margin-bottom:6px; }
        .role-option { display:flex; align-items:center; gap:8px; padding:10px 14px; border:2px solid #C8DCE8; border-radius:10px; cursor:pointer; transition:border-color 0.2s; margin-bottom:8px; }
        .role-option:has(input:checked) { border-color:#1CABE2; background:#EEF7FC; }
        .role-option input { accent-color:#1CABE2; }
        .role-label { font-size:0.82rem; font-weight:800; color:#1D2458; }
        .role-desc { font-size:0.7rem; color:#4A7A9A; }
        @media(max-width:768px){ .auth-grid { grid-template-columns:1fr; } .auth-left { display:none; } }
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
    <div style="display:flex;align-items:center;justify-content:center;padding:2rem;overflow-y:auto;">
        <div style="width:100%;max-width:400px;">
            <div class="form-title">Crear cuenta</div>
            <div class="form-sub">Únete a Yachaq Kawsay gratis</div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <label class="form-label">Nombre completo</label>
                <input class="form-input" type="text" name="name" value="{{ old('name') }}" required placeholder="Tu nombre completo">
                @error('name')<div class="error-msg">{{ $message }}</div>@enderror

                <label class="form-label">Correo electrónico</label>
                <input class="form-input" type="email" name="email" value="{{ old('email') }}" required placeholder="tu@correo.com">
                @error('email')<div class="error-msg">{{ $message }}</div>@enderror

                <label class="form-label">Soy...</label>
                <label class="role-option" onclick="toggleCodigo(false)">
                    <input type="radio" name="role" value="docente" {{ old('role')==='docente' ? 'checked' : '' }}>
                    <div><div class="role-label">👨‍🏫 Docente</div><div class="role-desc">Creo aulas y hago seguimiento</div></div>
                </label>
                <label class="role-option" onclick="toggleCodigo(true)">
                    <input type="radio" name="role" value="estudiante" {{ old('role')==='estudiante' ? 'checked' : '' }}>
                    <div><div class="role-label">🎒 Estudiante</div><div class="role-desc">Juego misiones y aprendo</div></div>
                </label>
                @error('role')<div class="error-msg">{{ $message }}</div>@enderror

                {{-- Campos solo para estudiante --}}
                <div id="campo-estudiante" class="{{ old('role')==='estudiante' ? '' : 'hidden' }}" style="margin-top:4px;">

                    <label class="form-label">Institución educativa</label>
                    <input class="form-input" type="text" name="institucion"
                        value="{{ old('institucion') }}"
                        placeholder="Ej: IE Santa Ana, IE Manco Cápac...">
                    @error('institucion')<div class="error-msg">{{ $message }}</div>@enderror

                    <label class="form-label">Nivel educativo</label>
                    <select class="form-input" name="nivel_educativo" id="select-nivel"
                        onchange="actualizarGrados(this.value)">
                        <option value="">Selecciona tu nivel</option>
                        <option value="primaria" {{ old('nivel_educativo')==='primaria' ? 'selected' : '' }}>Primaria</option>
                        <option value="secundaria" {{ old('nivel_educativo')==='secundaria' ? 'selected' : '' }}>Secundaria</option>
                    </select>
                    @error('nivel_educativo')<div class="error-msg">{{ $message }}</div>@enderror

                    <label class="form-label">Grado</label>
                    <select class="form-input" name="grado" id="select-grado">
                        <option value="">Selecciona tu grado</option>
                    </select>
                    @error('grado')<div class="error-msg">{{ $message }}</div>@enderror

                    <div style="display:flex;gap:10px;">
                        <div style="flex:1;">
                            <label class="form-label">Sección</label>
                            <input class="form-input" type="text" name="seccion"
                                value="{{ old('seccion') }}"
                                placeholder="Ej: A"
                                style="text-transform:uppercase;">
                        </div>
                        <div style="flex:2;">
                            <label class="form-label">Código de aula <span style="color:#4A7A9A;font-weight:400;">(opcional)</span></label>
                            <input class="form-input" type="text" name="codigo"
                                value="{{ old('codigo') }}"
                                placeholder="Si tu docente te dio uno"
                                style="text-transform:uppercase;">
                        </div>
                    </div>

                </div>



                <label class="form-label">Contraseña</label>
                <input class="form-input" type="password" name="password" required placeholder="Mínimo 8 caracteres">
                @error('password')<div class="error-msg">{{ $message }}</div>@enderror

                <label class="form-label">Confirmar contraseña</label>
                <input class="form-input" type="password" name="password_confirmation" required placeholder="Repite tu contraseña">

                <button type="submit" class="btn-submit">Crear mi cuenta</button>
            </form>

            <div class="form-footer">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}">Iniciar sesión</a>
            </div>
        </div>
    </div>
</div>
<script>
function toggleCodigo(show) {
    document.getElementById('campo-estudiante').classList.toggle('hidden', !show);
}

function actualizarGrados(nivel) {
    const select = document.getElementById('select-grado');
    select.innerHTML = '<option value="">Selecciona tu grado</option>';
    if (nivel === 'primaria') {
        for (let i = 1; i <= 6; i++) {
            select.innerHTML += `<option value="${i}">${i}° Primaria</option>`;
        }
    } else if (nivel === 'secundaria') {
        for (let i = 1; i <= 5; i++) {
            select.innerHTML += `<option value="${i}">${i}° Secundaria</option>`;
        }
    }
}

const nivelGuardado = '{{ old('nivel_educativo') }}';
const gradoGuardado = '{{ old('grado') }}';
if (nivelGuardado) {
    actualizarGrados(nivelGuardado);
    if (gradoGuardado) {
        document.getElementById('select-grado').value = gradoGuardado;
    }
}
</script>
</body>
</html>