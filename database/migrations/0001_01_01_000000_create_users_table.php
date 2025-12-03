<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ==========================================
        // 0. TABLAS DE SISTEMA (Auth & Sessions)
        // ==========================================

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            // Al tener dos tablas de usuarios, este user_id guardará el ID
            // del usuario logueado (sea investigador o personal).
            // Laravel gestionará esto mediante los "Guards".
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // ==========================================
        // 1. TABLAS DE NEGOCIO
        // ==========================================

        // 1. Tabla Investigadores
        Schema::create('investigadores', function (Blueprint $table) {
            $table->id();

            // Auth y Datos
            $table->string('nombre');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('foto')->nullable();
            $table->rememberToken();

            // Perfil Académico
            $table->string('clave_empleado')->unique()->nullable();
            $table->string('programa_academico')->nullable();
            $table->enum('nivel_academico', ['licenciatura', 'maestria', 'doctorado'])->nullable();
            $table->boolean('sni')->default(false);
            $table->boolean('perfil_prodep')->default(false);
            $table->string('cuerpo_academico')->nullable();
            $table->enum('grado_consolidacion_ca', ['en_formacion', 'en_consolidacion', 'consolidado'])->nullable();
            $table->string('telefono')->nullable();

            $table->timestamps();
        });

        // 2. Tabla Personal (Administrativos / Revisores)
        Schema::create('personal', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('clave_empleado')->unique();
            $table->string('foto')->nullable();
            $table->rememberToken();

            $table->string('cargo')->default('revisor');

            $table->timestamps();
        });

        // 3. Tabla Proyectos
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('no_registro')->unique()->nullable();

            // Datos Generales
            $table->string('titulo');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_termino')->nullable();

            // Enums de Áreas
            $table->enum('area_uan', [
                'ciencias_basicas_ingenierias', 'ciencias_biologicas_agropecuarias',
                'ciencias_economicas_administrativas', 'ciencias_salud',
                'ciencias_sociales_humanidades', 'artes'
            ])->nullable();

            $table->enum('area_inegi', [
                'educacion', 'artes_humanidades', 'ciencias_sociales_derecho',
                'ciencias_naturales_exactas_computacion', 'ingenieria_manufactura_construccion',
                'agronomia_veterinaria', 'salud', 'servicios', 'administracion_negocios',
                'tecnologia_informacion'
            ])->nullable();

            $table->string('palabras_clave')->nullable();

            // Financiamiento
            $table->enum('tipo_financiamiento', ['sin_financiamiento', 'interno', 'externo'])->default('sin_financiamiento');
            $table->string('fuente_financiamiento')->nullable();
            $table->decimal('monto_aprobado', 10, 2)->nullable();
            $table->enum('tipo_fondo', [
                'propios', 'privado', 'gobierno', 'fondos_publicos',
                'instituciones_privadas_no_lucrativas', 'exterior'
            ])->nullable();
            $table->string('accion_transferencia')->nullable();

            // Protocolo (Archivo + Resumen)
            $table->enum('tipo_investigacion', [
                'basica', 'aplicada', 'experimental', 'educativa', 'intervencion_social', 'otra'
            ]);
            $table->text('descripcion_breve')->nullable();
            $table->string('archivo_protocolo')->comment('Ruta al PDF/DOCX');

            // Entregables individuales
            $table->integer('articulos_indexada')->default(0);
            $table->integer('articulos_arbitrada')->default(0);
            $table->integer('libros')->default(0);
            $table->integer('capitulo_libro')->default(0);
            $table->integer('memorias_congreso')->default(0);
            $table->integer('tesis')->default(0);
            $table->integer('material_didactico')->default(0);
            $table->string('otros_entregables')->nullable();

            // Entregables (JSON) - Mantener para compatibilidad
            $table->json('productos_entregables')->nullable();

            // Resultados e Impactos
            $table->text('resultados_esperados')->nullable();
            $table->text('usuario_especifico')->nullable();
            $table->json('impactos')->nullable()->comment('JSON con impactos: cientifico, tecnologico, social, economico, ambiental');

            // Cronograma
            $table->json('cronograma_actividades')->nullable()->comment('JSON con actividades y fechas del cronograma');

            // Estado y Relaciones
            $table->enum('estado', ['en_revision', 'aprobado', 'rechazado', 'con_observaciones'])->default('en_revision');

            // Dueño del proyecto (Investigador)
            $table->foreignId('investigador_id')->constrained('investigadores')->onDelete('cascade');

            // Revisor asignado (Personal)
            $table->foreignId('personal_id')->nullable()->constrained('personal')->onDelete('set null');

            $table->timestamps();
        });

        // 4. Tabla Colaboradores
        Schema::create('colaboradores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');

            $table->string('identificador')->nullable();
            $table->string('nombre_completo');
            $table->string('actividad')->nullable();
            $table->string('nivel_academico')->nullable();
            $table->enum('tipo_colaborador', ['profesor', 'estudiante']);
            $table->string('tipo_formacion_estudiante')->nullable();
        });

        // 5. Tabla Comentarios
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id();
            $table->text('comentario')->comment('Observación o error encontrado por el revisor');

            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');

            // Solo PERSONAL escribe comentarios
            $table->foreignId('personal_id')->constrained('personal')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios');
        Schema::dropIfExists('colaboradores');
        Schema::dropIfExists('proyectos');
        Schema::dropIfExists('personal');
        Schema::dropIfExists('investigadores');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
    }
};
