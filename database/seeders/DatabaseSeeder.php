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
        $this->call([
            AreasSeeder::class,
            CampusesSeeder::class,
            PhysicalRoomsSeeder::class,
            VirtualRoomsSeeder::class,
            VirtualMeetingLinksSeeder::class,
            ProgramsSeeder::class,
            SupportPersonsSeeder::class,
            InstructorsSeeder::class,
            BookingsSeeder::class,
            BookingSupportPersonSeeder::class
        ]);
    }
}
