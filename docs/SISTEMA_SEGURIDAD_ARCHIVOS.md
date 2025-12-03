# Sistema de Seguridad de Archivos

## Descripción General

Este sistema implementa múltiples capas de seguridad para la carga y gestión de archivos en el proyecto, específicamente para los protocolos de investigación y otros documentos.

## Características de Seguridad

### 1. Validación de Archivos

**Validaciones implementadas:**
- ✅ Tamaño máximo: 10MB
- ✅ Extensiones permitidas: PDF, DOC, DOCX
- ✅ Validación de tipos MIME
- ✅ Verificación de magic bytes (firma del archivo)
- ✅ Escaneo de contenido malicioso (scripts, iframes, etc.)

### 2. Conversión Automática a PDF

**Todos los archivos se convierten a PDF:**
- Los archivos DOC/DOCX se convierten automáticamente a PDF
- Los archivos PDF se validan y se almacenan de forma segura
- El contenido HTML generado durante la conversión es sanitizado

**Beneficios:**
- Formato unificado para todos los documentos
- Eliminación de macros maliciosas
- Prevención de ejecución de código embebido
- Mejor compatibilidad y visualización

### 3. Almacenamiento Seguro

**Configuración de discos:**
- `private`: Almacena archivos fuera del directorio público
- Los archivos no son accesibles directamente vía URL
- Requiere autenticación para descargar/visualizar

**Ubicación:**
```
storage/
  app/
    private/
      protocolos/
        [archivos_convertidos].pdf
```

### 4. Control de Acceso

**Restricciones implementadas:**
- Investigadores solo pueden acceder a sus propios archivos
- Personal administrativo puede acceder a todos los archivos
- Rutas protegidas con middleware de autenticación
- Headers de seguridad en todas las descargas

### 5. Sanitización de Contenido

**Elementos removidos durante la conversión:**
- `<script>` tags
- `<iframe>` tags
- `<object>` y `<embed>` tags
- Atributos de eventos (onclick, onerror, etc.)
- JavaScript embebido

## Uso del Sistema

### Subir un Archivo (Investigador)

```php
// En el formulario de solicitud
<input type="file" 
       name="protocolo_investigacion" 
       accept=".pdf,.doc,.docx" 
       required>
```

El archivo se procesa automáticamente:
1. Validación de seguridad
2. Conversión a PDF (si es necesario)
3. Almacenamiento seguro
4. Registro en base de datos

### Descargar un Archivo

**Ruta para descargar:**
```
GET /proyecto/{no_registro}/protocolo/download
```

**Ruta para visualizar (en navegador):**
```
GET /proyecto/{no_registro}/protocolo/view
```

**Ejemplo en Blade:**
```php
<a href="{{ route('proyectos.download-protocolo', $proyecto->no_registro) }}" 
   class="btn btn-primary">
    Descargar Protocolo
</a>

<a href="{{ route('proyectos.view-protocolo', $proyecto->no_registro) }}" 
   target="_blank" 
   class="btn btn-secondary">
    Ver Protocolo
</a>
```

## Clases y Servicios

### FileSecurityService

**Ubicación:** `app/Services/FileSecurityService.php`

**Métodos principales:**
- `validateFile(UploadedFile $file)`: Valida el archivo
- `processAndConvertToPdf(UploadedFile $file, string $directory)`: Procesa y convierte
- `deleteFile(string $path)`: Elimina archivo de forma segura
- `getFileInfo(string $path)`: Obtiene información del archivo

**Uso:**
```php
use App\Services\FileSecurityService;

$fileService = new FileSecurityService();
$path = $fileService->processAndConvertToPdf($request->file('documento'), 'protocolos');
```

### FileDownloadController

**Ubicación:** `app/Http/Controllers/FileDownloadController.php`

**Métodos:**
- `downloadProtocolo(Proyecto $proyecto)`: Descarga segura
- `viewProtocolo(Proyecto $proyecto)`: Visualización en navegador

### SanitizeFileUploads Middleware

**Ubicación:** `app/Http/Middleware/SanitizeFileUploads.php`

**Función:**
- Se ejecuta automáticamente en todas las peticiones
- Valida archivos antes de que lleguen al controlador
- Verifica magic bytes para prevenir spoofing de extensiones

## Configuración

### Filesystem (config/filesystems.php)

```php
'disks' => [
    'private' => [
        'driver' => 'local',
        'root' => storage_path('app/private'),
        'visibility' => 'private',
    ],
],
```

### Permisos de Directorios

Asegurar que los siguientes directorios tengan permisos correctos:

```bash
chmod -R 755 storage/app/private
chmod -R 755 storage/app/temp
```

## Dependencias

**Paquetes requeridos:**
- `phpoffice/phpword`: ^1.4 - Para leer archivos Word
- `dompdf/dompdf`: ^3.1 - Para generar PDFs
- `spatie/pdf-to-text`: ^1.54 - Para extraer texto de PDFs

**Instalación:**
```bash
composer require phpoffice/phpword dompdf/dompdf spatie/pdf-to-text
```

## Headers de Seguridad

Todos los archivos descargados incluyen:

```
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Content-Type: application/pdf
Cache-Control: no-cache, no-store, must-revalidate
```

## Prevención de Vulnerabilidades

### Protección contra:
- ✅ **File Upload Attacks**: Validación estricta de tipos
- ✅ **Directory Traversal**: Nombres sanitizados
- ✅ **XSS**: Contenido HTML sanitizado
- ✅ **Malware**: Conversión elimina macros y scripts
- ✅ **MIME Sniffing**: Headers apropiados
- ✅ **Path Disclosure**: Almacenamiento privado
- ✅ **Direct File Access**: Autenticación requerida

## Mantenimiento

### Limpiar archivos temporales

```bash
php artisan storage:cleanup-temp
```

### Ver espacio usado

```bash
du -sh storage/app/private/protocolos
```

### Logs de seguridad

Los errores de validación se registran en:
```
storage/logs/laravel.log
```

## Mejoras Futuras

- [ ] Integrar antivirus (ClamAV)
- [ ] Implementar watermarking de PDFs
- [ ] Agregar firma digital
- [ ] Implementar versionado de archivos
- [ ] Agregar compresión automática
- [ ] Implementar límites por usuario

## Soporte

Para reportar problemas de seguridad:
- Email: security@proyecto.uan.mx
- No publicar vulnerabilidades en issues públicos
