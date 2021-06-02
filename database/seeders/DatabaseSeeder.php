<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\AuthorizedAccount;
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
use App\Models\User;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

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
            //AreasSeeder::class,
            CampusesSeeder::class,
            PhysicalRoomsSeeder::class,
            VirtualRoomsSeeder::class,
            // VirtualMeetingLinksSeeder::class,
            // ProgramsSeeder::class,
             SupportPersonsSeeder::class,
            // InstructorsSeeder::class,
            // InstructorAreasSeeder::class,
            // BookingsSeeder::class,
             //BookingSupportPersonSeeder::class

        ]);

        Program::create(['name' => 'Reuniones','short_name' => 'Reuniones', 'mnemonic' => '(REUNIÓN)']);


        AuthorizedAccount::create(['email' => "xdyer@ide.edu.ec", 'can_create_and_edit_bookings' => 1]);
        AuthorizedAccount::create(['email' => "rcastillo@ide.edu.ec", 'can_create_and_edit_bookings' => 1]);
        AuthorizedAccount::create(['email' => "mtriana@ide.edu.ec", 'can_create_and_edit_bookings' => 1]);
        AuthorizedAccount::create(['email' => "ksanmartin@ide.edu.ec", 'can_create_and_edit_bookings' => 1]);
        AuthorizedAccount::create(['email' => "hcadena@ide.edu.ec", 'can_create_and_edit_bookings' => 1]);
        AuthorizedAccount::create(['email' => "mallauca@ide.edu.ec", 'can_create_and_edit_bookings' => 1]);
        AuthorizedAccount::create(['email' => "sfierro@ide.edu.ec", 'can_create_and_edit_bookings' => 1]);
        AuthorizedAccount::create(['email' => "sacuna@ide.edu.ec", 'can_create_and_edit_bookings' => 1]);
        AuthorizedAccount::create(['email' => "sguevara@ide.edu.ec", 'can_create_and_edit_bookings' => 1]);
        AuthorizedAccount::create(['email' => "mnaranjo@ide.edu.ec", 'can_create_and_edit_bookings' => 1]);
        AuthorizedAccount::create(['email' => "ybastidas@ide.edu.ec", 'can_create_and_edit_bookings' => 0]);



    }
}
