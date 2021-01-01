<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Booking;
use App\Models\BookingSupportPerson;
use App\Models\Instructor;
use App\Models\InstructorArea;
use App\Models\Program;
use App\Models\Campus;
use App\Models\PhysicalRoom;
use App\Models\VirtualRoom;
use App\Models\VirtualMeetingLink;
use App\Models\SupportPerson;
use App\Models\SupportPersonRole;
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

        Program::create([
            'name'=>'Maestría en Dirección de Empresas 2019 Quito Paralelo 1',
            'mnemonic' =>'MDE2019UIOP1',
            'short_name' => ' MDE 2019 Quito P1',
            'start_date' => '2019-08-01 00:00:00',
            'end_date' => '2021-11-24 00:00:00',
        ]);

        Program::create([
            'name'=>'Maestría en Dirección de Empresas 2019 Guayaquil Paralelo 2',
            'mnemonic' =>'MDE2019GYEP2',
            'short_name' => ' MDE 2019 Guayaquil P2',
            'start_date' => '2019-08-01 00:00:00',
            'end_date' => '2021-11-24 00:00:00',
        ]);

        Program::create([
            'name'=>'Maestría en Dirección de Empresas 2019 Quito Paralelo 2',
            'mnemonic' =>'MDE2019UIOP2',
            'short_name' => ' MDE 2019 Quito P2',
            'start_date' => '2019-08-01 00:00:00',
            'end_date' => '2021-11-24 00:00:00',
        ]);

        Program::create([
            'name'=>'Maestría en Dirección de Empresas 2021 Guayaquil Paralelo 1',
            'mnemonic' =>'MDE2021GYEP1',
            'short_name' => ' MDE 2021 Guayaquil P1',
            'start_date' => '2019-08-01 00:00:00',
            'end_date' => '2021-11-24 00:00:00',
        ]);

        Program::create([
            'name'=>'Maestría en Dirección de Empresas 2021 Quito Paralelo 1',
            'mnemonic' =>'MDE2021UIOP1',
            'short_name' => ' MDE 2021 Quito P1',
            'start_date' => '2019-08-01 00:00:00',
            'end_date' => '2021-11-24 00:00:00',
        ]);

        Program::create([
            'name'=>'Incompany FarmaEnlace',
            'mnemonic' =>'FarmaEnlace 2021',
            'short_name' => ' Inco FarmaEnlace 2021',
            'start_date' => '2019-08-01 00:00:00',
            'end_date' => '2021-11-24 00:00:00',
        ]);


       SupportPersonRole::Create([
            'name' => 'Coordinación Académica',

        ]);


       SupportPersonRole::Create([
            'name' => 'Soporte Académico',

        ]);


       SupportPersonRole::Create([
            'name' => 'Soporte TI',

         ]);


        SupportPerson::create([
            'name'=>'Karina San Martín',
            'mnemonic' =>'KSM',
            'support_person_role_id' => 1,

        ]);

        SupportPerson::create([
            'name'=>'Karina San Martín',
            'mnemonic' =>'KSM',
            'support_person_role_id' => 2,

        ]);


        SupportPerson::create([
            'name'=>'Karina San Martín',
            'mnemonic' =>'KSM',
            'support_person_role_id' => 3,

        ]);

        SupportPerson::create([
            'name'=>'Martha Triana',
            'mnemonic' =>'MT',
            'support_person_role_id' => 1,

        ]);

        SupportPerson::create([
            'name'=>'Martha Triana',
            'mnemonic' =>'MT',
            'support_person_role_id' => 2,

        ]);

        SupportPerson::create([
            'name'=>'Martha Triana',
            'mnemonic' =>'MT',
            'support_person_role_id' => 3,

        ]);

        SupportPerson::create([
            'name'=>'Helen Cadena',
            'mnemonic' =>'HC',
            'support_person_role_id' => 1,

        ]);


        SupportPerson::create([
            'name'=>'Helen Cadena',
            'mnemonic' =>'HC',
            'support_person_role_id' => 2,

        ]);

        SupportPerson::create([
            'name'=>'Helen Cadena',
            'mnemonic' =>'HC',
            'support_person_role_id' => 3,

        ]);

        SupportPerson::create([
            'name'=>'María Fernanda Bustamante',
            'mnemonic' =>'MFB',
            'support_person_role_id' => 1,

        ]);

        SupportPerson::create([
            'name'=>'María Fernanda Bustamante',
            'mnemonic' =>'MFB',
            'support_person_role_id' => 2,

        ]);

        SupportPerson::create([
            'name'=>'María Fernanda Bustamante',
            'mnemonic' =>'MFB',
            'support_person_role_id' => 3,

        ]);

        SupportPerson::create([
            'name'=>'Soledad Crespo',
            'mnemonic' =>'SC',
            'support_person_role_id' => 1,

        ]);

        SupportPerson::create([
            'name'=>'Soledad Crespo',
            'mnemonic' =>'SC',
            'support_person_role_id' => 2,

        ]);

        SupportPerson::create([
            'name'=>'Soledad Crespo',
            'mnemonic' =>'SC',
            'support_person_role_id' => 3,

        ]);

        SupportPerson::create([
            'name'=>'Marco Salazar',
            'mnemonic' =>'MS',
            'support_person_role_id' => 1,

        ]);

        SupportPerson::create([
            'name'=>'Marco Salazar',
            'mnemonic' =>'MS',
            'support_person_role_id' => 2,

        ]);

        SupportPerson::create([
            'name'=>'Marco Salazar',
            'mnemonic' =>'MS',
            'support_person_role_id' => 3,

        ]);

        SupportPerson::create([
            'name'=>'Rafael Castillo',
            'mnemonic' =>'RC',
            'support_person_role_id' => 1,

        ]);

        SupportPerson::create([
            'name'=>'Rafael Castillo',
            'mnemonic' =>'RC',
            'support_person_role_id' => 2,

        ]);

        SupportPerson::create([
            'name'=>'Rafael Castillo',
            'mnemonic' =>'RC',
            'support_person_role_id' => 3,

        ]);

        SupportPerson::create([
            'name'=>'Stefany Acuña',
            'mnemonic' =>'SA',
            'support_person_role_id' => 1,
        ]);


        SupportPerson::create([
            'name'=>'Stefany Acuña',
            'mnemonic' =>'SA',
            'support_person_role_id' => 2,
        ]);


        SupportPerson::create([
            'name'=>'Stefany Acuña',
            'mnemonic' =>'SA',
            'support_person_role_id' => 3,
        ]);

        SupportPerson::create([
            'name'=>'Santiago Ullauri',
            'mnemonic' =>'SU',
            'support_person_role_id' => 1,

        ]);

        SupportPerson::create([
            'name'=>'Santiago Ullauri',
            'mnemonic' =>'SU',
            'support_person_role_id' => 2,

        ]);

        SupportPerson::create([
            'name'=>'Santiago Ullauri',
            'mnemonic' =>'SU',
            'support_person_role_id' => 3,

        ]);

        SupportPerson::create([
            'name'=>'Xavier Dyer',
            'mnemonic' =>'XD',
            'support_person_role_id' => 1,

        ]);

        SupportPerson::create([
            'name'=>'Xavier Dyer',
            'mnemonic' =>'XD',
            'support_person_role_id' => 2,

        ]);

        SupportPerson::create([
            'name'=>'Xavier Dyer',
            'mnemonic' =>'XD',
            'support_person_role_id' => 3,

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
            'name' => 'Aula 1 - Guayaquil',
            'mnemonic' => 'GYE-A1' ,
            'capacity' => '34' ,
        ]);

        PhysicalRoom::create([
            'campus_id' => 1,
            'name' => 'Aula 2 - Guayaquil',
            'mnemonic' => 'GYE-A2' ,
            'capacity' => '34' ,
        ]);

        PhysicalRoom::create([
            'campus_id' => 1,
            'name' => 'Aula 3 - Guayaquil',
            'mnemonic' => 'GYE-A3' ,
            'capacity' => '34' ,
        ]);

        PhysicalRoom::create([
            'campus_id' => 1,
            'name' => 'Aula 4 - Guayaquil',
            'mnemonic' => 'GYE-A4' ,
            'capacity' => '34' ,
        ]);

        PhysicalRoom::create([
            'campus_id' => 2,
            'name' => 'Aula 1 - Quito',
            'mnemonic' => 'UIO-A1' ,
            'capacity' => '34' ,
        ]);

        PhysicalRoom::create([
            'campus_id' => 2,
            'name' => 'Aula 2 Quito',
            'mnemonic' => 'UIO-A2' ,
            'capacity' => '34' ,
        ]);

        PhysicalRoom::create([
            'campus_id' => 2,
            'name' => 'Aula 3 - Quito',
            'mnemonic' => 'UIO-A3' ,
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

        VirtualMeetingLink::create([
            'topic' => 'Clases MDE 2019 GYE P1',
            'link' => 'https://zoom.us/j/407061210',
            'password' => 'MDE2021GYE' ,
            'waiting_room' => TRUE,
            'virtual_room_id' => 2,

        ]);

        Booking::create([
            'program_id' => 1,
            'area_id' => 3,
            'instructor_id' => 1,
            'virtual_meeting_link_id' => 1,
            'physical_room_id' => 1,
            'start_time' => '7.40',
            'end_time' => '7.40',

        ]);


        Booking::create([
            'program_id' => 2,
            'area_id' => 4,
            'instructor_id' => 5,
            'virtual_meeting_link_id' => 1,
            'physical_room_id' => 4,
            'start_time' => '8.40',
            'end_time' => '10.40',

        ]);

        Booking::create([
            'program_id' => 5,
            'area_id' => 5,
            'instructor_id' => 1,
            'virtual_meeting_link_id' => 1,
            'physical_room_id' => 2,
            'start_time' => '10.40',
            'end_time' => '13.40',

        ]);

        BookingSupportPerson::create([
            'booking_id' => 1,
            'support_person_id' => 1,

        ]);

        BookingSupportPerson::create([
            'booking_id' => 1,
            'support_person_id' => 4,
        ]);


        BookingSupportPerson::create([
            'booking_id' => 1,
            'support_person_id' => 30,
        ]);


    }
}
