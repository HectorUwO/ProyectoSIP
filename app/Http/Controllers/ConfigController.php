<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\Investigador;
use App\Models\Personal;

class ConfigController extends Controller
{
    /**
     * Show the configuration page
     */
    public function index()
    {
        // Get the authenticated user
        if (Auth::guard('investigador')->check()) {
            $user = Auth::guard('investigador')->user();
        } elseif (Auth::guard('personal')->check()) {
            $user = Auth::guard('personal')->user();
        } else {
            return redirect()->route('login')->with('error', 'No estás autenticado');
        }

        return view('config', compact('user'));
    }

    /**
     * Update user profile information
     */
    public function updateProfile(Request $request)
    {
        // Determine which guard is authenticated and get the proper model instance
        $guard = null;
        $user = null;
        $configRoute = null;

        if (Auth::guard('investigador')->check()) {
            $guard = 'investigador';
            $user = Investigador::find(Auth::guard('investigador')->id());
            $configRoute = 'investigador.config';
        } elseif (Auth::guard('personal')->check()) {
            $guard = 'personal';
            $user = Personal::find(Auth::guard('personal')->id());
            $configRoute = 'admin.config';
        }

        if (!$user) {
            return redirect()->route('login')->with('error', 'No estás autenticado');
        }

        // Base validation rules
        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . ($guard === 'investigador' ? 'investigadores' : 'personal') . ',email,' . $user->id],
            'clave_empleado' => ['required', 'string', 'max:50', 'unique:' . ($guard === 'investigador' ? 'investigadores' : 'personal') . ',clave_empleado,' . $user->id],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];

        // Add specific validation rules for investigators
        if ($guard === 'investigador') {
            $rules = array_merge($rules, [
                'programa_academico' => ['nullable', 'string', 'max:255'],
                'nivel_academico' => ['nullable', 'string', 'in:Licenciatura,Maestría,Doctorado'],
                'telefono' => ['nullable', 'string', 'max:20'],
                'cuerpo_academico' => ['nullable', 'string', 'max:255'],
                'sni' => ['nullable', 'boolean'],
                'perfil_prodep' => ['nullable', 'boolean'],
                'grado_consolidacion_ca' => ['nullable', 'string', 'in:en_formacion,en_consolidacion,consolidado'],
            ]);
        } else {
            // Add specific validation rules for administrative staff
            $rules['cargo'] = ['nullable', 'string', 'max:255'];
        }

        $request->validate($rules);

        try {
            // Update basic information
            $user->nombre = $request->nombre;
            $user->email = $request->email;
            $user->clave_empleado = $request->clave_empleado;

            // Handle photo upload
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                // Delete old photo if exists
                if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                    Storage::disk('public')->delete($user->foto);
                }

                // Store new photo
                $photoPath = $request->file('foto')->store('fotos', 'public');
                $user->foto = $photoPath;
            }

            // Update specific fields based on user type
            if ($guard === 'investigador') {
                $user->programa_academico = $request->programa_academico;
                $user->nivel_academico = $request->nivel_academico;
                $user->telefono = $request->telefono;
                $user->cuerpo_academico = $request->cuerpo_academico;
                $user->sni = $request->has('sni') ? 1 : 0;
                $user->perfil_prodep = $request->has('perfil_prodep') ? 1 : 0;
                $user->grado_consolidacion_ca = $request->grado_consolidacion_ca;
            } else {
                $user->cargo = $request->cargo;
            }

            $user->save();

            return redirect()->route($configRoute)->with('success', 'Perfil actualizado correctamente');

        } catch (\Exception $e) {
            return redirect()->route($configRoute)->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        // Get the proper model instance
        $user = null;
        $configRoute = null;

        if (Auth::guard('investigador')->check()) {
            $user = Investigador::find(Auth::guard('investigador')->id());
            $configRoute = 'investigador.config';
        } elseif (Auth::guard('personal')->check()) {
            $user = Personal::find(Auth::guard('personal')->id());
            $configRoute = 'admin.config';
        }

        if (!$user) {
            return redirect()->route('login')->with('error', 'No estás autenticado');
        }

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
        ]);

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route($configRoute)->withErrors(['current_password' => 'La contraseña actual es incorrecta']);
        }

        try {
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route($configRoute)->with('success', 'Contraseña actualizada correctamente');

        } catch (\Exception $e) {
            return redirect()->route($configRoute)->with('error', 'Error al actualizar la contraseña: ' . $e->getMessage());
        }
    }
}
