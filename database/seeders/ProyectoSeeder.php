<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProyectoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('proyectos')->insert([
            [
                'id' => 1,
                'no_registro' => 'PROY-2024-001',
                'titulo' => 'Desarrollo de Algoritmos de Inteligencia Artificial para Optimización de Procesos',
                'fecha_inicio' => '2024-01-15',
                'fecha_termino' => '2024-12-15',
                'area_uan' => 'ciencias_basicas_ingenierias',
                'area_inegi' => 'ciencias_naturales_exactas_computacion',
                'palabras_clave' => 'inteligencia artificial, optimización, algoritmos, machine learning',
                'tipo_financiamiento' => 'interno',
                'fuente_financiamiento' => 'UAN - Dirección de Investigación',
                'monto_aprobado' => 150000.00,
                'tipo_fondo' => 'propios',
                'tipo_investigacion' => 'aplicada',
                'descripcion_breve' => 'Proyecto enfocado en el desarrollo de algoritmos de IA para mejorar la eficiencia en procesos industriales mediante técnicas de optimización.',
                'archivo_protocolo' => 'protocolos/proyecto_001_protocolo.pdf',
                'productos_entregables' => json_encode([
                    'articulos' => 2,
                    'ponencias' => 3,
                    'software' => 1,
                    'tesis' => 1
                ]),
                'estado' => 'aprobado',
                'investigador_id' => 1,
                'personal_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'no_registro' => 'PROY-2024-002',
                'titulo' => 'Estudio de la Biodiversidad Marina en las Costas de Nayarit',
                'fecha_inicio' => '2024-03-01',
                'fecha_termino' => '2025-02-28',
                'area_uan' => 'ciencias_biologicas_agropecuarias',
                'area_inegi' => 'agronomia_veterinaria',
                'palabras_clave' => 'biodiversidad, ecosistemas marinos, conservación, Nayarit',
                'tipo_financiamiento' => 'externo',
                'fuente_financiamiento' => 'CONACYT',
                'monto_aprobado' => 300000.00,
                'tipo_fondo' => 'fondos_publicos',
                'tipo_investigacion' => 'basica',
                'descripcion_breve' => 'Investigación sobre la diversidad de especies marinas en las costas nayaritas y su estado de conservación.',
                'archivo_protocolo' => 'protocolos/proyecto_002_protocolo.pdf',
                'productos_entregables' => json_encode([
                    'articulos' => 3,
                    'libro' => 1,
                    'base_datos' => 1
                ]),
                'estado' => 'en_revision',
                'investigador_id' => 2,
                'personal_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
