<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('rols')->insert([
            ['id_rol' => 1, 'nombre' => 'Admin General'],
            ['id_rol' => 2, 'nombre' => 'Admin Web'],
            ['id_rol' => 3, 'nombre' => 'Ventas'],
            ['id_rol' => 4, 'nombre' => 'Compras'],
            ['id_rol' => 5, 'nombre' => 'Reparacion']
        ]);


        //DB::table('users')->insert([
        //    'dni' => 13345678, 
        //    'apellido_nombre' => 'HABERLES', 
        //    'password' => bcrypt('password'), 
        //    'estado' => true,
        //]);
    }
}
