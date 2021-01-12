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

class VirtualRoomsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
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
