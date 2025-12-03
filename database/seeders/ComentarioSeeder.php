<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComentarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('comentarios')->insert([
            [
                'comentario' => 'Se requiere mayor detalle en la metodología de muestreo. Favor de revisar la sección 3.2.',
                'proyecto_id' => 2,
                'personal_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'comentario' => 'Los objetivos específicos necesitan ser más precisos y medibles.',
                'proyecto_id' => 2,
                'personal_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
