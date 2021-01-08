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
        Instructor::create(['name' => 'Abel DeFina' , 'mnemonic'=>'AD' ] );
        Instructor::create(['name' => 'Andrés Castro' , 'mnemonic'=>'AC' ] );
        Instructor::create(['name' => 'Alfredo Larrea' , 'mnemonic'=>'Alfredo Larrea' ] );
        Instructor::create(['name' => 'Alberto Rosado' , 'mnemonic'=>'AR' ] );
        Instructor::create(['name' => 'Agustín Vera' , 'mnemonic'=>'AVera' ] );
        Instructor::create(['name' => 'Antonio Villasís' , 'mnemonic'=>'AVillasis' ] );
        Instructor::create(['name' => 'Carlos Noboa' , 'mnemonic'=>'CN' ] );
        Instructor::create(['name' => 'Diego Alejandro Jaramillo' , 'mnemonic'=>'DAJ' ] );
        Instructor::create(['name' => 'Diego Montenegro' , 'mnemonic'=>'DM' ] );
        Instructor::create(['name' => 'Daniel Susaeta' , 'mnemonic'=>'DS' ] );
        Instructor::create(['name' => 'Ernesto Novoa' , 'mnemonic'=>'ENV' ] );
        Instructor::create(['name' => 'Enrique Pérez' , 'mnemonic'=>'EP' ] );
        Instructor::create(['name' => 'Facundo Scavonne' , 'mnemonic'=>'FS' ] );
        Instructor::create(['name' => 'Galo Pazmiño' , 'mnemonic'=>'GP' ] );
        Instructor::create(['name' => 'Guillermo Vela' , 'mnemonic'=>'GV' ] );
        Instructor::create(['name' => 'Ignacio Osuna' , 'mnemonic'=>'IO' ] );
        Instructor::create(['name' => 'José Aulestia' , 'mnemonic'=>'JA' ] );
        Instructor::create(['name' => 'Johan Dreher' , 'mnemonic'=>'JD' ] );
        Instructor::create(['name' => 'Julio José Prado' , 'mnemonic'=>'JJP' ] );
        Instructor::create(['name' => 'Javier Juncosa' , 'mnemonic'=>'JJuncosa' ] );
        Instructor::create(['name' => 'José Luis Iglesias' , 'mnemonic'=>'JLI' ] );
        Instructor::create(['name' => 'José María Corrales' , 'mnemonic'=>'JMC' ] );
        Instructor::create(['name' => 'Juan Montero' , 'mnemonic'=>'JMontero' ] );
        Instructor::create(['name' => 'Juan Manuel Parra' , 'mnemonic'=>'JMP' ] );
        Instructor::create(['name' => 'Josemaría Vázquez' , 'mnemonic'=>'JMV' ] );
        Instructor::create(['name' => 'Juan Pablo Jaramillo' , 'mnemonic'=>'JPJ' ] );
        Instructor::create(['name' => 'José Ramón Pin' , 'mnemonic'=>'JRP' ] );
        Instructor::create(['name' => 'Julio Sánchez Loppacher' , 'mnemonic'=>'JSL' ] );
        Instructor::create(['name' => 'Leoncio Barzallo' , 'mnemonic'=>'LB' ] );
        Instructor::create(['name' => 'Leonardo Astudillo' , 'mnemonic'=>'Leonardo Astudillo' ] );
        Instructor::create(['name' => 'Lucía Muñoz' , 'mnemonic'=>'LMuñoz' ] );
        Instructor::create(['name' => 'Marcelo Albuja' , 'mnemonic'=>'MA' ] );
        Instructor::create(['name' => 'Martín Schleicher' , 'mnemonic'=>'MS' ] );
        Instructor::create(['name' => 'Mónica Torresado' , 'mnemonic'=>'MT' ] );
        Instructor::create(['name' => 'Marlon Viera' , 'mnemonic'=>'Mviera' ] );
        Instructor::create(['name' => 'Omar Vargas' , 'mnemonic'=>'OV' ] );
        Instructor::create(['name' => 'Pablo Alegre' , 'mnemonic'=>'PA' ] );
        Instructor::create(['name' => 'Patricio Córdova' , 'mnemonic'=>'PC' ] );
        Instructor::create(['name' => 'Peter Montes' , 'mnemonic'=>'Peter Montes' ] );
        Instructor::create(['name' => 'Pol Herrman' , 'mnemonic'=>'POL HER' ] );
        Instructor::create(['name' => 'Patricio Vergara' , 'mnemonic'=>'PV' ] );
        Instructor::create(['name' => 'Rodrigo Andrade' , 'mnemonic'=>'RA' ] );
        Instructor::create(['name' => 'Roberto Estrada' , 'mnemonic'=>'RE' ] );
        Instructor::create(['name' => 'Roberto Housser' , 'mnemonic'=>'RHouser' ] );
        Instructor::create(['name' => 'Ricardo Serrano' , 'mnemonic'=>'Ricardo Serrano' ] );
        Instructor::create(['name' => 'Raúl Lagomarsino' , 'mnemonic'=>'RLM' ] );
        Instructor::create(['name' => 'Roberto Luchi' , 'mnemonic'=>'RLU' ] );
        Instructor::create(['name' => 'Raúl Moncayo' , 'mnemonic'=>'RM' ] );
        Instructor::create(['name' => 'Santiago Caviedes' , 'mnemonic'=>'Santiago Caviedes' ] );
        Instructor::create(['name' => 'Santiago Barragán' , 'mnemonic'=>'SB' ] );
        Instructor::create(['name' => 'Sergio Torassa' , 'mnemonic'=>'ST' ] );
        Instructor::create(['name' => 'Wilson Jácome' , 'mnemonic'=>'WJC' ] );


        Area::create(['name' => 'Análisis de Situación de Negocios' , 'mnemonic'=>'ASN' ] );
        Area::create(['name' => 'Análisis de Decisiones' , 'mnemonic'=>'ADD' ] );
        Area::create(['name' => 'Agile' , 'mnemonic'=>'Agile' ] );
        Area::create(['name' => 'El Director y el hombre' , 'mnemonic'=>'ANTR' ] );
        Area::create(['name' => 'Dirección Comercial' , 'mnemonic'=>'COM' ] );
        Area::create(['name' => 'Desing Thinking' , 'mnemonic'=>'Desing T' ] );
        Area::create(['name' => 'Dirección de Personas' , 'mnemonic'=>'DP' ] );
        Area::create(['name' => 'Empresa y Familia' , 'mnemonic'=>'E&F' ] );
        Area::create(['name' => 'Empresas Familiares' , 'mnemonic'=>'EF' ] );
        Area::create(['name' => 'Entorno Económico' , 'mnemonic'=>'ENE' ] );
        Area::create(['name' => 'Dirección Estratégica' , 'mnemonic'=>'ESTR' ] );
        Area::create(['name' => 'Dirección Financiera' , 'mnemonic'=>'FIN' ] );
        Area::create(['name' => 'Escenario de Negocios Latinoamericanos' , 'mnemonic'=>'IAE' ] );
        Area::create(['name' => 'Modelos de Innovación y Emprendimiento' , 'mnemonic'=>'INN' ] );
        Area::create(['name' => 'Invitado' , 'mnemonic'=>'Invitado' ] );
        Area::create(['name' => 'Negociación' , 'mnemonic'=>'NEG' ] );
        Area::create(['name' => 'Dirección de Operaciones' , 'mnemonic'=>'OPE' ] );
        Area::create(['name' => 'Outdoors' , 'mnemonic'=>'Outdoors' ] );
        Area::create(['name' => 'Política de Empresas' , 'mnemonic'=>'POL' ] );
        Area::create(['name' => 'Gestión de Responsabilidad Social' , 'mnemonic'=>'GRS' ] );
        Area::create(['name' => 'Recursos Humanos' , 'mnemonic'=>'RRHH' ] );
        Area::create(['name' => 'Sistemas de Dirección y Control' , 'mnemonic'=>'SDC' ] );


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
            'short_name' => 'InCo FarmaEnlace 2021',
        ]);

        Program::create([
            'name'=>'Incompany NIRSA 2021 - Programa Estratégico de Finanzas',
            'mnemonic' =>'NIRSA 2021',
            'short_name' => 'InCo NIRSA-PEF 2021',
        ]);

        Program::create([
            'name'=>'Incompany Acuacultura 2020',
            'mnemonic' =>'Acuacultura 2020',
            'short_name' => 'InCo Acuacultura 2020',
        ]);

        Program::create([
            'name'=>'Incompany B.Braun Medicals 2020 - Liderazgo y Desarrollo',
            'mnemonic' =>'BBraun 2020',
            'short_name' => 'InCo BBraun 2020',
        ]);

        Program::create([
            'name'=>'Incompany Bananeros 2020 - Programa de Eficiencia en Dirección de Empresas para el sector Bananero',
            'mnemonic' =>'Bananeros 2020',
            'short_name' => 'InCo Bananeros 2020',
        ]);

        Program::create([
            'name'=>'Seminario Programa Ejecutivo en Finanzas 2020',
            'mnemonic' =>'PEF 2020',
            'short_name' => 'PEF 2020',
        ]);

        Program::create([
            'name'=>'Seminario Programa de Liderazgo y Dirección de Personas 2020',
            'mnemonic' =>'PLDP 2020',
            'short_name' => 'PLDP 2020',
        ]);

        Program::create([
            'name'=>'Seminario Programa en Eficiencia de Operaciones 2020',
            'mnemonic' =>'PEOP 2020',
            'short_name' => 'PEOP 2020',
        ]);

        Program::create([
            'name'=>'Programa para Propietario de Empresas',
            'mnemonic' =>'PPE 2020',
            'short_name' => 'PPE 2020',
        ]);


        SupportPerson::create([
            'name'=>'Karina San Martín',
            'mnemonic' =>'KSM',

        ]);



        SupportPerson::create([
            'name'=>'Martha Triana',
            'mnemonic' =>'MT',

        ]);


        SupportPerson::create([
            'name'=>'Helen Cadena',
            'mnemonic' =>'HC',

        ]);



        SupportPerson::create([
            'name'=>'María Fernanda Bustamante',
            'mnemonic' =>'MFB',
        ]);



        SupportPerson::create([
            'name'=>'Soledad Crespo',
            'mnemonic' =>'SC',
        ]);



        SupportPerson::create([
            'name'=>'Marco Salazar',
            'mnemonic' =>'MS',
        ]);



        SupportPerson::create([
            'name'=>'Rafael Castillo',
            'mnemonic' =>'RC',

        ]);



        SupportPerson::create([
            'name'=>'Stefany Acuña',
            'mnemonic' =>'SA',
        ]);



        SupportPerson::create([
            'name'=>'Santiago Ullauri',
            'mnemonic' =>'SU',

        ]);



        SupportPerson::create([
            'name'=>'Xavier Dyer',
            'mnemonic' =>'XD',

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


        VirtualMeetingLink::create([
            'topic' => 'Clases InCO NIRSA - PEF 2021',
            'link' => ' https://us02web.zoom.us/j/87291770504',
            'password' => '2021' ,
            'waiting_room' => TRUE,
            'virtual_room_id' => 1,

        ]);


        Booking::create([
            'program_id' => 1,
            'area_id' => 3,
            'instructor_id' => 1,
            'virtual_meeting_link_id' => 1,
            'physical_room_id' => 1,
            'start_time' => '07:40',
            'end_time' => '07:40',
            'booking_date' => '2020-01-08',

        ]);


        Booking::create([
            'program_id' => 2,
            'area_id' => 4,
            'instructor_id' => 5,
            'virtual_meeting_link_id' => 1,
            'physical_room_id' => 4,
            'start_time' => '08:40',
            'end_time' => '10:40',
            'booking_date' => '2020-01-08',

        ]);

        Booking::create([
            'program_id' => 5,
            'area_id' => 5,
            'instructor_id' => 1,
            'virtual_meeting_link_id' => 1,
            'physical_room_id' => 2,
            'start_time' => '10:40',
            'end_time' => '13:40',
            'booking_date' => '2020-01-08',

        ]);

        BookingSupportPerson::create([
            'booking_id' => 1,
            'support_person_id' => 1,
            'support_type' => 1,
            'support_role' => 1,
        ]);

        BookingSupportPerson::create([
            'booking_id' => 1,
            'support_person_id' => 4,
            'support_type' => 1,
            'support_role' => 2,
        ]);


        BookingSupportPerson::create([
            'booking_id' => 1,
            'support_person_id' =>3,
            'support_type' => 1,
            'support_role' => 3,
        ]);


    }
}
