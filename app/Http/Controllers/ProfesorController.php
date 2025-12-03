<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investigador;

class ProfesorController extends Controller
{
    /**
     * Buscar profesores por nombre
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $profesores = Investigador::where('nombre', 'LIKE', "%{$query}%")
            ->select('id', 'nombre', 'nivel_academico', 'programa_academico')
            ->limit(10)
            ->get()
            ->map(function ($profesor) {
                // Mapear nivel_academico de la BD al formato esperado
                $gradoMap = [
                    'licenciatura' => 'Licenciatura',
                    'maestria' => 'Maestría',
                    'doctorado' => 'Doctorado'
                ];

                return [
                    'id' => $profesor->id,
                    'nombre' => $profesor->nombre,
                    'grado' => $gradoMap[$profesor->nivel_academico] ?? 'Doctorado',
                    'actividad' => 'Docente investigador'
                ];
            });

        return response()->json($profesores);
    }

    /**
     * Registrar un nuevo profesor
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'profesor_nombre' => 'required|string|max:100',
            'profesor_actividad' => 'required|string|max:100',
            'profesor_grado' => 'required|in:Licenciatura,Maestría,Doctorado'
        ]);

        // Mapear grado académico al formato de la BD
        $nivelMap = [
            'Licenciatura' => 'licenciatura',
            'Maestría' => 'maestria',
            'Doctorado' => 'doctorado'
        ];

        // Generar un email temporal y clave de empleado única
        $emailBase = strtolower(str_replace(' ', '.', $validated['profesor_nombre']));
        $email = $emailBase . '@temp.ipn.mx';

        // Asegurarse de que el email sea único
        $counter = 1;
        while (Investigador::where('email', $email)->exists()) {
            $email = $emailBase . $counter . '@temp.ipn.mx';
            $counter++;
        }

        // Generar clave de empleado única
        $claveBase = 'TEMP' . rand(10000, 99999);
        while (Investigador::where('clave_empleado', $claveBase)->exists()) {
            $claveBase = 'TEMP' . rand(10000, 99999);
        }

        $investigador = Investigador::create([
            'nombre' => $validated['profesor_nombre'],
            'email' => $email,
            'password' => bcrypt('temporal123'), // Password temporal
            'clave_empleado' => $claveBase,
            'programa_academico' => $validated['profesor_actividad'],
            'nivel_academico' => $nivelMap[$validated['profesor_grado']],
            'sni' => false,
            'perfil_prodep' => false
        ]);

        return response()->json([
            'success' => true,
            'profesor' => [
                'id' => $investigador->id,
                'nombre' => $investigador->nombre,
                'grado' => $validated['profesor_grado'], // Retornar en formato original
                'actividad' => 'Docente investigador'
            ]
        ]);
    }
}
