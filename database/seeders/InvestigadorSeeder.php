<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InvestigadorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('investigadores')->insert([
            [
                'id' => 1,
                'nombre' => 'Dr. María Elena González',
                'email' => 'maria.gonzalez@uan.edu.mx',
                'password' => Hash::make('password123'),
                'clave_empleado' => 'INV001',
                'programa_academico' => 'Ingeniería en Sistemas Computacionales',
                'nivel_academico' => 'doctorado',
                'sni' => true,
                'perfil_prodep' => true,
                'cuerpo_academico' => 'Tecnologías de la Información',
                'grado_consolidacion_ca' => 'consolidado',
                'telefono' => '311-123-4567',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'Mtro. Carlos Alberto Ruiz',
                'email' => 'carlos.ruiz@uan.edu.mx',
                'password' => Hash::make('password123'),
                'clave_empleado' => 'INV002',
                'programa_academico' => 'Biología',
                'nivel_academico' => 'maestria',
                'sni' => false,
                'perfil_prodep' => true,
                'cuerpo_academico' => 'Ecología y Conservación',
                'grado_consolidacion_ca' => 'en_consolidacion',
                'telefono' => '311-765-4321',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
