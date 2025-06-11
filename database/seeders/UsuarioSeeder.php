<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'dni' => '41811578',
                'apellido_nombre' => 'Lucas Admin',
                'correo' => 'lucashaberles811@gmail.com',
                'estado' => 1,
                'password' => Hash::make('Admin1234'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'dni' => '41811577',
                'apellido_nombre' => 'Usuario JC',
                'correo' => 'lucashaberles811@gmail.com',
                'estado' => 1,
                'password' => Hash::make('Usuario1234'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]
        ]);
    }
}
