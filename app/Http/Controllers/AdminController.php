<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Comentario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display the administrative dashboard
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'date-desc');

        // Base query
        $query = Proyecto::with(['investigador', 'colaboradores']);

        // Apply filters
        if ($filter !== 'all') {
            $statusMap = [
                'pending' => 'en_revision',
                'approved' => 'aprobado',
                'rejected' => 'rechazado',
                'revision' => 'con_observaciones'
            ];

            if (isset($statusMap[$filter])) {
                $query->where('estado', $statusMap[$filter]);
            }
        }

        // Apply search
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descripcion_breve', 'like', "%{$search}%")
                  ->orWhere('palabras_clave', 'like', "%{$search}%")
                  ->orWhereHas('investigador', function($q) use ($search) {
                      $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido_paterno', 'like', "%{$search}%")
                        ->orWhere('apellido_materno', 'like', "%{$search}%");
                  });
            });
        }

        // Apply sorting
        switch ($sort) {
            case 'date-desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'date-asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title-asc':
                $query->orderBy('titulo', 'asc');
                break;
            case 'title-desc':
                $query->orderBy('titulo', 'desc');
                break;
            case 'status':
                $query->orderBy('estado', 'asc');
                break;
        }

        $proyectos = $query->paginate(5)->withQueryString();

        // Get statistics
        $stats = [
            'pending' => Proyecto::where('estado', 'en_revision')->count(),
            'approved' => Proyecto::where('estado', 'aprobado')->count(),
            'rejected' => Proyecto::where('estado', 'rechazado')->count(),
            'revision' => Proyecto::where('estado', 'con_observaciones')->count(),
        ];

        return view('administrativo', compact('proyectos', 'stats', 'filter', 'search', 'sort'));
    }

    /**
     * Show revision view for a project
     */
    public function revision(Proyecto $proyecto)
    {
        $proyecto->load(['investigador', 'colaboradores', 'comentarios.personal']);
        return view('admin-revision', compact('proyecto'));
    }

    /**
     * Approve a project
     */
    public function approve(Proyecto $proyecto)
    {
        try {
            $proyecto->estado = 'aprobado';
            $proyecto->save();

            return response()->json([
                'success' => true,
                'message' => 'Proyecto aprobado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al aprobar el proyecto: ' . $e->getMessage()
            ], 500);
        }
    }    /**
     * Reject a project
     */
    public function reject(Request $request, Proyecto $proyecto)
    {
        $validated = $request->validate([
            'comments' => 'required|string|max:2000'
        ]);

        DB::beginTransaction();
        try {
            $proyecto->estado = 'rechazado';
            $proyecto->save();

            // Add rejection comment
            $personal = Auth::guard('personal')->user();

            Comentario::create([
                'proyecto_id' => $proyecto->id,
                'personal_id' => $personal->id,
                'contenido' => $validated['comments'],
                'tipo' => 'rechazo'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Proyecto rechazado. Se ha notificado al investigador.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al rechazar el proyecto: ' . $e->getMessage()
            ], 500);
        }
    }
}
