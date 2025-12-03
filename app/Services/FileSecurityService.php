<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;

class FileSecurityService
{
    /**
     * Extensiones permitidas
     */
    private const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx'];

    /**
     * Tamaño máximo de archivo en bytes (10MB)
     */
    private const MAX_FILE_SIZE = 10485760;

    /**
     * Tipos MIME permitidos
     */
    private const ALLOWED_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    /**
     * Valida el archivo subido
     *
     * @param UploadedFile $file
     * @return array
     * @throws Exception
     */
    public function validateFile(UploadedFile $file): array
    {
        // Validar que el archivo existe
        if (!$file->isValid()) {
            throw new Exception('El archivo no es válido o está corrupto.');
        }

        // Validar tamaño
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new Exception('El archivo excede el tamaño máximo permitido de 10MB.');
        }

        // Validar extensión
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new Exception('Tipo de archivo no permitido. Solo se permiten: ' . implode(', ', self::ALLOWED_EXTENSIONS));
        }

        // Validar MIME type
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
            throw new Exception('Tipo MIME no válido. El archivo podría estar corrupto o ser malicioso.');
        }

        // Escanear contenido del archivo (básico)
        $this->scanFileContent($file);

        return [
            'original_name' => $file->getClientOriginalName(),
            'extension' => $extension,
            'mime_type' => $mimeType,
            'size' => $file->getSize(),
        ];
    }

    /**
     * Escanea el contenido del archivo en busca de patrones maliciosos
     *
     * @param UploadedFile $file
     * @throws Exception
     */
    private function scanFileContent(UploadedFile $file): void
    {
        // Leer los primeros bytes del archivo
        $handle = fopen($file->getRealPath(), 'rb');
        $content = fread($handle, 8192);
        fclose($handle);

        // Patrones sospechosos
        $maliciousPatterns = [
            '/<script[\s\S]*?>/i',
            '/javascript:/i',
            '/on\w+\s*=/i', // onclick, onerror, etc.
            '/<iframe/i',
            '/<embed/i',
            '/<object/i',
        ];

        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                throw new Exception('El archivo contiene contenido potencialmente malicioso.');
            }
        }
    }

    /**
     * Procesa y convierte el archivo a PDF de manera segura
     *
     * @param UploadedFile $file
     * @param string $directory Directorio donde guardar el archivo
     * @return string Ruta del archivo PDF guardado
     * @throws Exception
     */
    public function processAndConvertToPdf(UploadedFile $file, string $directory = 'protocolos'): string
    {
        try {
            // Validar el archivo
            $fileInfo = $this->validateFile($file);

            // Generar nombre seguro para el archivo
            $safeName = $this->generateSafeFileName($fileInfo['original_name']);
            $extension = $fileInfo['extension'];

            // Si ya es PDF, solo guardarlo de forma segura
            if ($extension === 'pdf') {
                return $this->savePdfSecurely($file, $directory, $safeName);
            }

            // Si es DOC o DOCX, convertir a PDF
            if (in_array($extension, ['doc', 'docx'])) {
                return $this->convertWordToPdf($file, $directory, $safeName);
            }

            throw new Exception('Tipo de archivo no soportado para conversión.');

        } catch (Exception $e) {
            throw new Exception('Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Guarda un PDF de forma segura
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $safeName
     * @return string
     */
    private function savePdfSecurely(UploadedFile $file, string $directory, string $safeName): string
    {
        $fileName = $safeName . '.pdf';
        $path = $file->storeAs($directory, $fileName, 'private');

        return $path;
    }

    /**
     * Convierte un archivo Word (DOC/DOCX) a PDF
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $safeName
     * @return string
     * @throws Exception
     */
    private function convertWordToPdf(UploadedFile $file, string $directory, string $safeName): string
    {
        try {
            // Cargar el documento Word
            $phpWord = IOFactory::load($file->getRealPath());

            // Convertir a HTML primero
            $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');

            $tempHtmlPath = storage_path('app/temp/' . Str::uuid() . '.html');

            // Asegurar que el directorio temp existe
            if (!file_exists(dirname($tempHtmlPath))) {
                mkdir(dirname($tempHtmlPath), 0755, true);
            }

            $htmlWriter->save($tempHtmlPath);

            // Leer el HTML generado
            $htmlContent = file_get_contents($tempHtmlPath);

            // Sanitizar el HTML
            $htmlContent = $this->sanitizeHtml($htmlContent);

            // Configurar Dompdf
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', false);
            $options->set('isRemoteEnabled', false);
            $options->set('defaultFont', 'Arial');

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($htmlContent);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Guardar el PDF
            $pdfContent = $dompdf->output();
            $fileName = $safeName . '.pdf';
            $fullPath = storage_path('app/private/' . $directory . '/' . $fileName);

            // Asegurar que el directorio existe
            $dirPath = dirname($fullPath);
            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0755, true);
            }

            file_put_contents($fullPath, $pdfContent);

            // Limpiar archivos temporales
            @unlink($tempHtmlPath);

            return $directory . '/' . $fileName;

        } catch (Exception $e) {
            // Limpiar archivos temporales en caso de error
            if (isset($tempHtmlPath) && file_exists($tempHtmlPath)) {
                @unlink($tempHtmlPath);
            }

            throw new Exception('Error al convertir documento Word a PDF: ' . $e->getMessage());
        }
    }

    /**
     * Sanitiza contenido HTML
     *
     * @param string $html
     * @return string
     */
    private function sanitizeHtml(string $html): string
    {
        // Remover scripts y otros elementos potencialmente peligrosos
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        $html = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $html);
        $html = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is', '', $html);
        $html = preg_replace('/<embed\b[^>]*>/is', '', $html);
        $html = preg_replace('/on\w+\s*=\s*["\'].*?["\']/is', '', $html);

        return $html;
    }

    /**
     * Genera un nombre de archivo seguro
     *
     * @param string $originalName
     * @return string
     */
    private function generateSafeFileName(string $originalName): string
    {
        // Remover extensión
        $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);

        // Sanitizar nombre
        $safeName = Str::slug($nameWithoutExtension);

        // Si el nombre queda vacío después de sanitizar, usar un UUID
        if (empty($safeName)) {
            $safeName = 'document';
        }

        // Agregar timestamp y hash para unicidad
        return $safeName . '_' . time() . '_' . substr(md5($originalName . microtime()), 0, 8);
    }

    /**
     * Elimina un archivo de forma segura
     *
     * @param string $path
     * @return bool
     */
    public function deleteFile(string $path): bool
    {
        try {
            if (Storage::disk('private')->exists($path)) {
                return Storage::disk('private')->delete($path);
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Obtiene información de un archivo guardado
     *
     * @param string $path
     * @return array|null
     */
    public function getFileInfo(string $path): ?array
    {
        try {
            if (Storage::disk('private')->exists($path)) {
                return [
                    'size' => Storage::disk('private')->size($path),
                    'last_modified' => Storage::disk('private')->lastModified($path),
                    'path' => $path,
                ];
            }
            return null;
        } catch (Exception $e) {
            return null;
        }
    }
}
