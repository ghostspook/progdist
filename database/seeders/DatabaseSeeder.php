<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Instructor;
use App\Models\InstructorArea;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Instructor::create(['name' => 'Daniel Susaeta', 'mnemonic' => 'DS']);
        Instructor::create(['name' => 'Jorge Monckeberg', 'mnemonic' => 'JMB']);
        Instructor::create(['name' => 'Josemaría Vázquez', 'mnemonic' => 'JV']);
        Instructor::create(['name' => 'Roberto Estrada', 'mnemonic' => 'RE']);
        Instructor::create(['name' => 'Abel Defina', 'mnemonic' => 'AD']);
        Instructor::create(['name' => 'Raúl Moncayo', 'mnemonic' => 'RM']);
        Instructor::create(['name' => 'Antonio Villasís', 'mnemonic' => 'AV']);
        Instructor::create(['name' => 'Patricio Vergara', 'mnemonic' => 'PV']);
        Instructor::create(['name' => 'José Aulestia', 'mnemonic' => 'JA']);

        Area::create(['name' => 'Dirección de Operaciones', 'mnemonic' => 'OPE']);
        Area::create(['name' => 'Dirección Financiera', 'mnemonic' => 'FIN']);
        Area::create(['name' => 'Dirección Estratégica', 'mnemonic' => 'DIRE']);
        Area::create(['name' => 'Dirección de Personas', 'mnemonic' => 'DP']);
        Area::create(['name' => 'Dirección Comercial', 'mnemonic' => 'COM']);
        Area::create(['name' => 'Análisis de Decisiones', 'mnemonic' => 'ADD']);
        Area::create(['name' => 'Sistemas de Dirección y Control', 'mnemonic' => 'SDC']);
        Area::create(['name' => 'Análisis de Situación de Negocios', 'mnemonic' => 'ASN']);

        InstructorArea::create(['instructor_id' => 1, 'area_id' => 1]);
        InstructorArea::create(['instructor_id' => 1, 'area_id' => 8]);
        InstructorArea::create(['instructor_id' => 2, 'area_id' => 2]);
        InstructorArea::create(['instructor_id' => 3, 'area_id' => 3]);
        InstructorArea::create(['instructor_id' => 4, 'area_id' => 4]);
        InstructorArea::create(['instructor_id' => 5, 'area_id' => 2]);
        InstructorArea::create(['instructor_id' => 6, 'area_id' => 5]);
        InstructorArea::create(['instructor_id' => 7, 'area_id' => 6]);
        InstructorArea::create(['instructor_id' => 8, 'area_id' => 7]);
        InstructorArea::create(['instructor_id' => 9, 'area_id' => 5]);

    }
}
