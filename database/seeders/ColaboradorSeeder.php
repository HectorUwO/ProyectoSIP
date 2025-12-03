<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColaboradorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('colaboradores')->insert([
            [
                'proyecto_id' => 1,
                'identificador' => 'PROF001',
                'nombre_completo' => 'Dr. Luis Fernando Martínez',
                'actividad' => 'Co-investigador',
                'nivel_academico' => 'Doctorado',
                'tipo_colaborador' => 'profesor',
                'tipo_formacion_estudiante' => null,
            ],
            [
                'proyecto_id' => 1,
                'identificador' => 'EST001',
                'nombre_completo' => 'Juan Carlos Pérez Mendoza',
                'actividad' => 'Desarrollo de software',
                'nivel_academico' => 'Licenciatura',
                'tipo_colaborador' => 'estudiante',
                'tipo_formacion_estudiante' => 'Tesis de licenciatura',
            ],
            [
                'proyecto_id' => 2,
                'identificador' => 'PROF002',
                'nombre_completo' => 'Dra. Sandra Luz Jiménez',
                'actividad' => 'Especialista en taxonomía',
                'nivel_academico' => 'Doctorado',
                'tipo_colaborador' => 'profesor',
                'tipo_formacion_estudiante' => null,
            ],
            [
                'proyecto_id' => 2,
                'identificador' => 'EST002',
                'nombre_completo' => 'María Fernanda Castro López',
                'actividad' => 'Trabajo de campo y muestreo',
                'nivel_academico' => 'Maestría',
                'tipo_colaborador' => 'estudiante',
                'tipo_formacion_estudiante' => 'Tesis de maestría',
            ]
        ]);
    }
}
