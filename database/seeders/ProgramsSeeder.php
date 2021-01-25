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

class ProgramsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Program::create(['name' => 'Maestría en Dirección de Empresas 2019 Guayaquil Paralelo 1','short_name' => 'MDE2019GYEP1', 'mnemonic' => 'MDE2019GYEP1']);
        Program::create(['name' => 'Maestría en Dirección de Empresas 2019 Quito Paralelo 1','short_name' => 'MDE2019UIOP1', 'mnemonic' => 'MDE2019UIOP1']);
        Program::create(['name' => 'Maestría en Dirección de Empresas 2019 Guayaquil Paralelo 2','short_name' => 'MDE2019GYEP2', 'mnemonic' => 'MDE2019GYEP2']);
        Program::create(['name' => 'Maestría en Dirección de Empresas 2019 Quito  Paralelo 2','short_name' => 'MDE2019UIOP2', 'mnemonic' => 'MDE2019UIOP2']);
        Program::create(['name' => 'Maestría en Dirección de Empresas 2020 Guayaquil Paralelo 1','short_name' => 'MDE2020GYEP1', 'mnemonic' => 'MDE2020GYEP1']);
        Program::create(['name' => 'Maestría en Dirección de Empresas 2020 Quito Paralelo 2','short_name' => 'MDE2020UIOP1', 'mnemonic' => 'MDE2020UIOP1']);
        Program::create(['name' => 'Incompany FarmaEnlace 2021 - Programa de Desarrollo de Habilidades Gerenciales','short_name' => 'FarmaEnlace 2021', 'mnemonic' => 'FarmaEnlace 2021']);
        Program::create(['name' => 'Incompany NIRSA 2021 - Programa Estratégico de Finanzas','short_name' => 'In CO - NIRSA', 'mnemonic' => 'In CO - NIRSA']);
        Program::create(['name' => 'Incompany Acuacultura 2020 - Programa de Gerencia para Empresas del Sector Camaronero','short_name' => 'Acuacultura 2020', 'mnemonic' => 'Acuacultura 2020']);
        Program::create(['name' => 'Incompany B. Braun Medicals 2020 - Programa de Liderazgo y Desarrollo','short_name' => 'In Co B.Braun 2020', 'mnemonic' => 'B.Braun 2020']);
        Program::create(['name' => 'Incompany Bananeros 2020 - Programa de Eficiencia en Dirección de Empresas para el sector Bananero','short_name' => 'Bananeros 2020', 'mnemonic' => 'Bananeros 2020']);
        Program::create(['name' => 'Programa Ejecutivo de Finanzas 2020','short_name' => 'PEF 2020', 'mnemonic' => 'PEF 2020']);
        Program::create(['name' => 'Progama de Liderezgo y Dirección de Personas 2020','short_name' => 'PLDP 2020', 'mnemonic' => 'PLDP 2020']);
        Program::create(['name' => 'Programa de Eficiencia en Operaciones 2020','short_name' => 'PEOP2020 Online', 'mnemonic' => 'PEOP2020 Online']);
        Program::create(['name' => 'Maestría en Dirección de Empresas 2018 Guayaquil Paralelo 1','short_name' => 'MDE2018GYEP1', 'mnemonic' => 'MDE2018GYEP1']);
        Program::create(['name' => 'Maestría en Dirección de Empresas 2018 Guayaquil Paralelo 2','short_name' => 'MDE2018GYEP2', 'mnemonic' => 'MDE2018GYEP2']);
        Program::create(['name' => 'Maestría en Dirección de Empresas 2018 Quito  Paralelo 1','short_name' => 'MDE2018UIOP1', 'mnemonic' => 'MDE2018UIOP1']);
        Program::create(['name' => 'Maestría en Dirección de Empresas 2018 Quito  Paralelo 2','short_name' => 'MDE2018UIOP2', 'mnemonic' => 'MDE2018UIOP2']);
        Program::create(['name' => 'Incompany KUBIEC 2020 - online','short_name' => 'KUBIEC 2020', 'mnemonic' => 'KUBIEC 2020']);
        Program::create(['name' => 'Incompany InCo Conver Liderazgo Femenino BG 2020 ','short_name' => 'Liderazgo Femenino BG 2020', 'mnemonic' => 'Liderazgo Femenino BG 2020']);
        Program::create(['name' => 'Programa de Eficiencia en Operaciones 2020','short_name' => 'Gobierno Corporativo 2020', 'mnemonic' => 'Gobierno Corporativo 2020']);
        Program::create(['name' => 'Incompany RESILIENCIA 2020','short_name' => 'RESILIENCIA 2020', 'mnemonic' => 'RESILIENCIA 2020']);
        Program::create(['name' => 'BT - Negociación, mediación y conflictos 2020','short_name' => 'BT-Negociación 2020', 'mnemonic' => 'BT-Negociación 2020']);
        Program::create(['name' => 'Seminario de Negociación 2020','short_name' => 'SEM NEG 2020', 'mnemonic' => 'SEM NEG 2020']);
        Program::create(['name' => 'Incompany Andres Petroleum 2020','short_name' => 'InCo ANDES PETROLEUM 2020', 'mnemonic' => 'ANDES PETROLEUM 2020']);
        Program::create(['name' => 'Programa de Marketing Estratégico','short_name' => 'PME 2020', 'mnemonic' => 'PME 2020']);
        Program::create(['name' => 'Programa Escenarios Empresariales 2021 - Virtual','short_name' => 'Escenarios Empresariales 2021 - Virtual', 'mnemonic' => 'Escenarios Empresariales 2021 - Virtual']);
        Program::create(['name' => 'Seminario Empresas Familiares 2020','short_name' => 'SEM-EF 2020', 'mnemonic' => 'SEM-EF 2020']);
        Program::create(['name' => 'BT - Ecommerce  y Nuevos Modelos de Negocios 2020','short_name' => 'BT-ECOMMERCE 2020', 'mnemonic' => 'BT-ECOMMERCE 2020']);
        Program::create(['name' => 'Incompany Banco de Guayaquil 2020','short_name' => 'BANCO DE GUAYAQUIL 2020', 'mnemonic' => 'BANCO DE GUAYAQUIL 2020']);
        Program::create(['name' => 'BT - Gestión de Liquidez y Riesgos 2020','short_name' => 'BT - Gestión de Liquidez 2020', 'mnemonic' => 'BT - Gestión de Liquidez 2020']);
        Program::create(['name' => 'BT - Liderazgo y Futuro del Trabajo 2020','short_name' => 'BT - Liderazgo y Futuro del Trabajo 2020', 'mnemonic' => 'BT - Liderazgo y Futuro del Trabajo 2020']);
        Program::create(['name' => 'Programa Soft Skills 2020','short_name' => 'SOFT SKILLS 2020', 'mnemonic' => 'SOFT SKILLS 2020']);
        Program::create(['name' => 'Programa para Propietarios de Empresas 2020 Guayaquil','short_name' => 'PPE 2020 GYE', 'mnemonic' => 'PPE 2020 GYE']);
        Program::create(['name' => 'Programa para Propietarios de Empresas 2020 Quito','short_name' => 'PPE 2020 UIO', 'mnemonic' => 'PPE 2020 UIO']);
        Program::create(['name' => 'Incompany MUJERES 2020 - Programa de Gerencia para Empresas del Sector Camaronero','short_name' => 'MUJERES 2020', 'mnemonic' => 'MUJERES 2020']);
        Program::create(['name' => 'Incompany 2020  - Programa Estratégico de Finanzas','short_name' => 'BANCO GUAYAQUIL - PYME 2020', 'mnemonic' => 'BANCO GUAYAQUIL - PYME 2020']);





    }
}
