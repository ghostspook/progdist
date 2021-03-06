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

class PhysicalRoomsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
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

        PhysicalRoom::create([
            'campus_id' => 1,
            'name' => 'Domicilio - Guayaquil',
            'mnemonic' => 'DOM-GYE' ,

        ]);

        PhysicalRoom::create([
            'campus_id' => 2,
            'name' => 'Domicilio - Quito',
            'mnemonic' => 'DOM-UIO' ,

        ]);




    }
}
