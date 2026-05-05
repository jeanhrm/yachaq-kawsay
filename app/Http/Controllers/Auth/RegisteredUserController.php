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
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role'     => ['required', 'in:docente,estudiante'],
                'codigo'   => ['required_if:role,estudiante', 'nullable', 'string'],
            ]);

            // Si es estudiante validar código de aula
            $aulaId = null;
            if ($request->role === 'estudiante') {
                $aula = \App\Models\Aula::where('codigo', strtoupper($request->codigo))->first();
                if (!$aula) {
                    return back()->withErrors(['codigo' => 'El código de aula no es válido.'])->withInput();
                }
                $aulaId = $aula->id;
            }

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
                'aula_id'  => $aulaId,
            ]);

            event(new Registered($user));
            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        }
    
}
