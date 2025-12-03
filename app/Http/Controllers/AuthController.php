<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Investigador;
use App\Models\Personal;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        // Buscar usuario en ambas tablas
        $investigador = Investigador::where('email', $credentials['email'])->first();
        $personal = Personal::where('email', $credentials['email'])->first();

        Log::info('Login attempt', ['email' => $credentials['email']]);

        if (!$investigador && !$personal) {
            Log::warning('User not found', ['email' => $credentials['email']]);
            return back()->withErrors([
                'email' => 'No se encontró ningún usuario con ese email.',
            ])->onlyInput('email');
        }

        // Intentar autenticación como investigador
        if ($investigador) {
            Log::info('Trying investigador auth', ['user_id' => $investigador->id]);

            if (Hash::check($credentials['password'], $investigador->password)) {
                Auth::guard('investigador')->login($investigador, $request->filled('remember'));
                $request->session()->regenerate();
                Log::info('Investigador login successful', ['user_id' => $investigador->id]);
                return redirect()->route('user');
            }
        }

        // Intentar autenticación como personal
        if ($personal) {
            Log::info('Trying personal auth', ['user_id' => $personal->id]);

            if (Hash::check($credentials['password'], $personal->password)) {
                Auth::guard('personal')->login($personal, $request->filled('remember'));
                $request->session()->regenerate();
                Log::info('Personal login successful', ['user_id' => $personal->id]);
                return redirect()->route('admin.dashboard');
            }
        }

        Log::warning('Login failed - invalid credentials', ['email' => $credentials['email']]);
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('investigador')->logout();
        Auth::guard('personal')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
