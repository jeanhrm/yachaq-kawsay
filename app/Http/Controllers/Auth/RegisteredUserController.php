<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'        => ['required', 'confirmed', Rules\Password::defaults()],
            'role'            => ['required', 'in:docente,estudiante'],
            'institucion'     => ['required_if:role,estudiante', 'nullable', 'string', 'max:200'],
            'nivel_educativo' => ['required_if:role,estudiante', 'nullable', 'in:primaria,secundaria'],
            'grado'           => ['required_if:role,estudiante', 'nullable', 'integer', 'min:1', 'max:6'],
            'seccion'         => ['nullable', 'string', 'max:5'],
            'codigo'          => ['nullable', 'string'], // opcional ahora
        ]);

        // Código de aula es opcional
        $aulaId = null;
        if ($request->filled('codigo')) {
            $aula = \App\Models\Aula::where('codigo', strtoupper($request->codigo))->first();
            if ($aula) $aulaId = $aula->id;
        }

        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'role'            => $request->role,
            'aula_id'         => $aulaId,
            'nivel_educativo' => $request->nivel_educativo,
            'grado'           => $request->grado,
            'seccion'         => $request->seccion,
            'institucion'     => $request->institucion,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard');
    }
    
}
