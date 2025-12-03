<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Proyecto;

class FileDownloadController extends Controller
{
    /**
     * Descargar archivo de protocolo de forma segura
     *
     * @param Proyecto $proyecto
     * @return \Illuminate\Http\Response
     */
    public function downloadProtocolo(Proyecto $proyecto)
    {
        // Verificar que el usuario tenga permiso para ver el archivo
        $user = Auth::user();

        // Si es investigador, solo puede ver sus propios proyectos
        if ($user instanceof \App\Models\Investigador) {
            if ($proyecto->investigador_id !== $user->id) {
                abort(403, 'No tienes permiso para descargar este archivo.');
            }
        }

        // Si es personal, puede ver todos los proyectos
        // (No necesita validación adicional)

        // Verificar que el archivo existe
        if (!$proyecto->archivo_protocolo || !Storage::disk('private')->exists($proyecto->archivo_protocolo)) {
            abort(404, 'El archivo no existe.');
        }

        // Obtener información del archivo
        $filePath = $proyecto->archivo_protocolo;
        $fileName = 'protocolo_' . $proyecto->no_registro . '.pdf';
        $content = Storage::disk('private')->get($filePath);

        // Retornar el archivo para descarga
        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
        ]);
    }

    /**
     * Visualizar archivo de protocolo en el navegador (sin descargar)
     *
     * @param Proyecto $proyecto
     * @return \Illuminate\Http\Response
     */
    public function viewProtocolo(Proyecto $proyecto)
    {
        // Verificar que el usuario tenga permiso para ver el archivo
        $user = Auth::user();

        // Si es investigador, solo puede ver sus propios proyectos
        if ($user instanceof \App\Models\Investigador) {
            if ($proyecto->investigador_id !== $user->id) {
                abort(403, 'No tienes permiso para ver este archivo.');
            }
        }

        // Verificar que el archivo existe
        if (!$proyecto->archivo_protocolo || !Storage::disk('private')->exists($proyecto->archivo_protocolo)) {
            abort(404, 'El archivo no existe.');
        }

        // Obtener el contenido del archivo
        $filePath = $proyecto->archivo_protocolo;
        $content = Storage::disk('private')->get($filePath);

        // Retornar respuesta con el PDF para visualización
        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="protocolo_' . $proyecto->no_registro . '.pdf"',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-XSS-Protection' => '1; mode=block',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
