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

class AreasSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
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




    }
}
