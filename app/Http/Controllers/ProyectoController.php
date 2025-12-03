<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Proyecto;
use App\Models\Colaborador;
use App\Services\FileSecurityService;

class ProyectoController extends Controller
{
    /**
     * Mostrar la vista del formulario de solicitud
     */
    public function create()
    {
        return view('solicitud');
    }

    /**
     * Validar y almacenar un nuevo proyecto
     */
    public function store(Request $request)
    {
        try {
            // Obtener el investigador autenticado
            $investigador = Auth::guard('investigador')->user();

            if (!$investigador) {
                return redirect()->route('login')->with('error', 'Debe iniciar sesión para registrar un proyecto');
            }

            // Validación de datos del formulario
            $validated = $request->validate([
                // Step 1 - Tipo de proyecto
                'tipo_investigacion' => 'required|in:desarrollo-experimental,investigacion-basica,investigacion-aplicada',
                'financiamiento' => 'required|in:sin-financiamiento,interno,externo',

                // Step 2 - Detalles del financiamiento (condicional)
                'tipo_fondo' => 'nullable|string',
                'accion_transferencia' => 'nullable|string|max:200',

                // Step 3 - Detalles del proyecto
                'nombre_proyecto' => 'required|string|max:150',
                'vigencia_inicio' => 'required|date',
                'vigencia_fin' => 'required|date|after:vigencia_inicio',
                'area_academica' => 'required|string',
                'area_inegi' => 'required|string',

                // Step 4 - Entregables
                'articulos_indexada' => 'nullable|integer',
                'articulos_arbitrada' => 'nullable|integer',
                'libros' => 'nullable|integer',
                'capitulo_libro' => 'nullable|integer',
                'memorias_congreso' => 'nullable|integer',
                'tesis' => 'nullable|integer',
                'material_didactico' => 'nullable|integer',
                'otros_entregables' => 'nullable|string|max:200',

                // Step 6 - Protocolo de investigación
                'protocolo_investigacion' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max

                // Step 7 - Resultados esperados
                'resultados_esperados' => 'required|string',

                // Step 8 - Impactos
                'usuario_especifico' => 'required|string',
                'impacto_cientifico' => 'nullable|string',
                'impacto_tecnologico' => 'nullable|string',
                'impacto_social' => 'nullable|string',
                'impacto_economico' => 'nullable|string',
                'impacto_ambiental' => 'nullable|string',

                // JSON fields
                'colaboradores' => 'nullable|string',
                'cronograma' => 'nullable|string',
            ]);

            // Procesar archivo de protocolo con seguridad y conversión a PDF
            $protocoloPath = null;
            if ($request->hasFile('protocolo_investigacion')) {
                $fileService = new FileSecurityService();
                try {
                    $protocoloPath = $fileService->processAndConvertToPdf(
                        $request->file('protocolo_investigacion'),
                        'protocolos'
                    );
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->withErrors(['protocolo_investigacion' => $e->getMessage()])
                        ->withInput();
                }
            }

            // Mapear enums según la base de datos
            $areaUanMap = [
                'ciencias-basicas' => 'ciencias_basicas_ingenierias',
                'ciencias-biologicas' => 'ciencias_biologicas_agropecuarias',
                'ciencias-economicas' => 'ciencias_economicas_administrativas',
                'ciencias-salud' => 'ciencias_salud',
                'ciencias-sociales' => 'ciencias_sociales_humanidades',
                'artes' => 'artes'
            ];

            $areaInegiMap = [
                'educacion' => 'educacion',
                'tecnologia' => 'tecnologia_informacion',
                'salud' => 'salud',
                'ingenieria' => 'ingenieria_manufactura_construccion',
                'economia' => 'administracion_negocios'
            ];

            $tipoInvestigacionMap = [
                'desarrollo-experimental' => 'experimental',
                'investigacion-basica' => 'basica',
                'investigacion-aplicada' => 'aplicada'
            ];

            // Procesar entregables
            $entregables = [
                'articulos_indexada' => $validated['articulos_indexada'] ?? 0,
                'articulos_arbitrada' => $validated['articulos_arbitrada'] ?? 0,
                'libros' => $validated['libros'] ?? 0,
                'capitulo_libro' => $validated['capitulo_libro'] ?? 0,
                'memorias_congreso' => $validated['memorias_congreso'] ?? 0,
                'tesis' => $validated['tesis'] ?? 0,
                'material_didactico' => $validated['material_didactico'] ?? 0,
                'otros' => $validated['otros_entregables'] ?? null,
            ];

            // Procesar impactos
            $impactos = [];
            if (!empty($validated['impacto_cientifico'])) {
                $impactos['cientifico'] = $validated['impacto_cientifico'];
            }
            if (!empty($validated['impacto_tecnologico'])) {
                $impactos['tecnologico'] = $validated['impacto_tecnologico'];
            }
            if (!empty($validated['impacto_social'])) {
                $impactos['social'] = $validated['impacto_social'];
            }
            if (!empty($validated['impacto_economico'])) {
                $impactos['economico'] = $validated['impacto_economico'];
            }
            if (!empty($validated['impacto_ambiental'])) {
                $impactos['ambiental'] = $validated['impacto_ambiental'];
            }

            // Mapear tipo_fondo
            $tipoFondoMap = [
                'fondos-publicos' => 'fondos_publicos',
                'instituciones-privadas' => 'instituciones_privadas_no_lucrativas',
            ];

            // Procesar cronograma si existe
            $cronograma = null;
            if ($request->has('cronograma')) {
                $cronogramaData = json_decode($request->input('cronograma'), true);
                if (is_array($cronogramaData)) {
                    $cronograma = $cronogramaData;
                }
            }

            // Crear el proyecto
            $proyecto = Proyecto::create([
                'titulo' => $validated['nombre_proyecto'],
                'fecha_inicio' => $validated['vigencia_inicio'],
                'fecha_termino' => $validated['vigencia_fin'],
                'area_uan' => $areaUanMap[$validated['area_academica']] ?? 'ciencias_economicas_administrativas',
                'area_inegi' => $areaInegiMap[$validated['area_inegi']] ?? 'educacion',
                'tipo_financiamiento' => $validated['financiamiento'] === 'sin-financiamiento' ? 'sin_financiamiento' : $validated['financiamiento'],
                'fuente_financiamiento' => $validated['accion_transferencia'] ?? null,
                'tipo_fondo' => isset($validated['tipo_fondo']) ? ($tipoFondoMap[$validated['tipo_fondo']] ?? $validated['tipo_fondo']) : null,
                'tipo_investigacion' => $tipoInvestigacionMap[$validated['tipo_investigacion']] ?? 'experimental',
                'descripcion_breve' => substr($validated['resultados_esperados'], 0, 500),
                'resultados_esperados' => $validated['resultados_esperados'],
                'usuario_especifico' => $validated['usuario_especifico'],
                'archivo_protocolo' => $protocoloPath,
                'productos_entregables' => json_encode($entregables),
                'impactos' => json_encode($impactos),
                'cronograma_actividades' => $cronograma ? json_encode($cronograma) : null,
                'articulos_indexada' => $entregables['articulos_indexada'],
                'articulos_arbitrada' => $entregables['articulos_arbitrada'],
                'libros' => $entregables['libros'],
                'capitulo_libro' => $entregables['capitulo_libro'],
                'memorias_congreso' => $entregables['memorias_congreso'],
                'tesis' => $entregables['tesis'],
                'material_didactico' => $entregables['material_didactico'],
                'otros_entregables' => $entregables['otros'],
                'estado' => 'en_revision',
                'investigador_id' => $investigador->id,
            ]);

            // Procesar colaboradores
            if ($request->has('colaboradores')) {
                $colaboradoresData = json_decode($request->input('colaboradores'), true);

                if (isset($colaboradoresData['profesores'])) {
                    foreach ($colaboradoresData['profesores'] as $profesor) {
                        Colaborador::create([
                            'proyecto_id' => $proyecto->id,
                            'nombre_completo' => $profesor['nombre'],
                            'actividad' => $profesor['role'] ?? 'Docente investigador',
                            'tipo_colaborador' => 'profesor',
                        ]);
                    }
                }

                if (isset($colaboradoresData['estudiantes'])) {
                    foreach ($colaboradoresData['estudiantes'] as $estudiante) {
                        Colaborador::create([
                            'proyecto_id' => $proyecto->id,
                            'nombre_completo' => $estudiante['nombre'],
                            'actividad' => $estudiante['role'] ?? 'Estudiante',
                            'tipo_colaborador' => 'estudiante',
                        ]);
                    }
                }
            }

            // Generar número de registro único
            $proyecto->update([
                'no_registro' => 'SIP-' . date('Y') . '-' . str_pad($proyecto->id, 4, '0', STR_PAD_LEFT)
            ]);

            return redirect()->route('user')->with('success', 'Proyecto registrado exitosamente con número: ' . $proyecto->no_registro);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al procesar el formulario: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar todos los proyectos (para admin/personal)
     */
    public function index()
    {
        $proyectos = Proyecto::with(['colaboradores'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user', compact('proyectos'));
    }

    /**
     * Mostrar un proyecto específico
     */
    public function show(Proyecto $proyecto)
    {
        // Load relationships
        $proyecto->load(['investigador', 'colaboradores', 'comentarios.personal']);

        return view('proyecto', compact('proyecto'));
    }
}
