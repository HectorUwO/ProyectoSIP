<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PersonalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('personal')->insert([
            [
                'id' => 1,
                'nombre' => 'Lic. Ana Patricia López',
                'email' => 'ana.lopez@uan.edu.mx',
                'password' => Hash::make('admin123'),
                'clave_empleado' => 'ADM001',
                'cargo' => 'coordinador',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'Lic. Roberto Hernández',
                'email' => 'roberto.hernandez@uan.edu.mx',
                'password' => Hash::make('revisor123'),
                'clave_empleado' => 'REV001',
                'cargo' => 'revisor',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
