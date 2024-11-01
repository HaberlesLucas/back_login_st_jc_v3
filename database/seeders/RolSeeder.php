<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RolSeeder extends Seeder
{
    public function run(): void
    {
        //
        DB::table('rols')->insert([
            ['id_rol' => 1, 'nombre' => 'Admin Stream-Tech'],
            ['id_rol' => 2, 'nombre' => 'Admin JComplements'],
        ]);
    }
}
