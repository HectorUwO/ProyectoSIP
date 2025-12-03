<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeFileUploads
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Validar que todos los archivos subidos cumplan con requisitos de seguridad
        $allFiles = $request->allFiles();

        if (!empty($allFiles)) {
            foreach ($allFiles as $key => $file) {
                if (is_array($file)) {
                    foreach ($file as $singleFile) {
                        $this->validateUploadedFile($singleFile, $key);
                    }
                } else {
                    $this->validateUploadedFile($file, $key);
                }
            }
        }

        return $next($request);
    }

    /**
     * Validar archivo subido
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $fieldName
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateUploadedFile($file, $fieldName): void
    {
        // Validar que el archivo es válido
        if (!$file->isValid()) {
            abort(422, "El archivo en el campo '{$fieldName}' no es válido.");
        }

        // Validar tamaño máximo (10MB)
        $maxSize = 10 * 1024 * 1024; // 10MB en bytes
        if ($file->getSize() > $maxSize) {
            abort(422, "El archivo en el campo '{$fieldName}' excede el tamaño máximo de 10MB.");
        }

        // Validar extensión
        $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            abort(422, "El tipo de archivo en el campo '{$fieldName}' no está permitido.");
        }

        // Validar que el contenido coincide con la extensión (magic bytes)
        $this->validateFileContent($file, $extension, $fieldName);
    }

    /**
     * Validar contenido del archivo mediante magic bytes
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $extension
     * @param string $fieldName
     */
    private function validateFileContent($file, $extension, $fieldName): void
    {
        $handle = fopen($file->getRealPath(), 'rb');
        $bytes = fread($handle, 8);
        fclose($handle);

        // Magic bytes para diferentes tipos de archivo
        $magicBytes = [
            'pdf' => ['25504446'], // %PDF
            'doc' => ['D0CF11E0'], // Microsoft Office
            'docx' => ['504B0304'], // ZIP (DOCX es un ZIP)
            'jpg' => ['FFD8FF'],
            'jpeg' => ['FFD8FF'],
            'png' => ['89504E47'],
        ];

        if (isset($magicBytes[$extension])) {
            $fileHeader = strtoupper(bin2hex(substr($bytes, 0, 4)));
            $valid = false;

            foreach ($magicBytes[$extension] as $validHeader) {
                if (strpos($fileHeader, $validHeader) === 0) {
                    $valid = true;
                    break;
                }
            }

            if (!$valid) {
                abort(422, "El archivo en el campo '{$fieldName}' parece estar corrupto o no corresponde al tipo declarado.");
            }
        }
    }
}
