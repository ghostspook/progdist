<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Instructor;
use App\Models\InstructorArea;
use App\Models\Program;
use App\Models\Campus;
use App\Models\PhysicalRoom;
use App\Models\VirtualRoom;



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
        Instructor::create(['name' => 'Josemaría Vázquez', 'mnemonic' => 'JMV']);
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

        Program::create([
            'name'=>'Maestría en Dirección de Empresas 2019 Guayaquil Paralelo 1',
            'mnemonic' =>'MDE2019GYEP1',
            'short_name' => ' MDE 2019 Guayaquil P1',
            'start_date' => '2019-08-01 00:00:00',
            'end_date' => '2021-11-24 00:00:00',
        ]);

        Campus::create([
            'name' => 'Sede Guayaquil', 
            'mnemonic' => 'GYE' , 
            'city' => 'Guayaquil',
            'address' => 'Km. 13 vía a la costa',
        ]);

        Campus::create([
            'name' => 'Sede Quito', 
            'mnemonic' => 'UIO' , 
            'city' => 'Quito',
            'address' => 'Nicolás López 518 y Marco Aguirre, sector Pinar Bajo.',
        ]);

        PhysicalRoom::create([
            'campus_id' => 1,
            'name' => 'Aula 1', 
            'mnemonic' => 'A1' , 
            'capacity' => '34' , 
        ]);

        PhysicalRoom::create([
            'campus_id' => 1,
            'name' => 'Aula 2', 
            'mnemonic' => 'A2' , 
            'capacity' => '34' , 
        ]);

        PhysicalRoom::create([
            'campus_id' => 1,
            'name' => 'Aula 3', 
            'mnemonic' => 'A3' , 
            'capacity' => '34' , 
        ]);
    
        PhysicalRoom::create([
            'campus_id' => 1,
            'name' => 'Aula 4', 
            'mnemonic' => 'A4' , 
            'capacity' => '34' , 
        ]);
       
        PhysicalRoom::create([
            'campus_id' => 2,
            'name' => 'Aula 1', 
            'mnemonic' => 'A1' , 
            'capacity' => '34' , 
        ]);

        PhysicalRoom::create([
            'campus_id' => 2,
            'name' => 'Aula 2', 
            'mnemonic' => 'A2' , 
            'capacity' => '34' , 
        ]);

        PhysicalRoom::create([
            'campus_id' => 2,
            'name' => 'Aula 3', 
            'mnemonic' => 'A3' , 
            'capacity' => '34' , 
        ]);

        VirtualRoom::create([
            'name' => 'Aula Virtual 1', 
            'mnemonic' => 'AV1' , 
            'zoom_account' => 'aula.virtual1@ide.edu.ec',
        ]);

        VirtualRoom::create([
            'name' => 'Aula Virtual 2', 
            'mnemonic' => 'AV2' , 
            'zoom_account' => 'aula.virtual2@ide.edu.ec',
        ]);

        VirtualRoom::create([
            'name' => 'Aula Virtual 3', 
            'mnemonic' => 'AV3' , 
            'zoom_account' => 'aula.virtual3@ide.edu.ec',
        ]);

        VirtualRoom::create([
            'name' => 'Aula Virtual 4', 
            'mnemonic' => 'AV4' , 
            'zoom_account' => 'aula.virtual4@ide.edu.ec',
        ]);

        VirtualRoom::create([
            'name' => 'Aula Virtual 5', 
            'mnemonic' => 'AV5' , 
            'zoom_account' => 'aula.virtual5@ide.edu.ec',
        ]);

        VirtualRoom::create([
            'name' => 'Aula Virtual 6', 
            'mnemonic' => 'AV6' , 
            'zoom_account' => 'aula.virtual6@ide.edu.ec',
        ]);

        VirtualRoom::create([
            'name' => 'Aula Virtual 7', 
            'mnemonic' => 'AV7' , 
            'zoom_account' => 'aula.virtual7@ide.edu.ec',
        ]);
    }
}
