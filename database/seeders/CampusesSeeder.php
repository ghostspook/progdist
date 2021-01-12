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

class CampusesSeeder extends Seeder
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

    }
}
