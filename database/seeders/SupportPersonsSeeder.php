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

class SupportPersonsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        SupportPerson::create(['name' => 'Helen Cadena' , 'mnemonic'=>'HC' ] );
        SupportPerson::create(['name' => 'Karyna Espinoza' , 'mnemonic'=>'KE' ] );
        SupportPerson::create(['name' => 'Karina San Martín' , 'mnemonic'=>'KSM' ] );
        SupportPerson::create(['name' => 'Martiza Allauca' , 'mnemonic'=>'MA' ] );
        SupportPerson::create(['name' => 'María Fernanda Bustamante' , 'mnemonic'=>'MFB' ] );
        SupportPerson::create(['name' => 'María José Naranjo' , 'mnemonic'=>'MJN' ] );
        SupportPerson::create(['name' => 'Marco Salazar' , 'mnemonic'=>'MS' ] );
        SupportPerson::create(['name' => 'Martha Triana' , 'mnemonic'=>'MT' ] );
        SupportPerson::create(['name' => 'Rafael Castillo' , 'mnemonic'=>'RC' ] );
        SupportPerson::create(['name' => 'Stefany Acuña' , 'mnemonic'=>'SA' ] );
        SupportPerson::create(['name' => 'Soledad Crespo' , 'mnemonic'=>'SC' ] );
        SupportPerson::create(['name' => 'Sandra Guevara' , 'mnemonic'=>'SG' ] );
        SupportPerson::create(['name' => 'Santiago Ullauri' , 'mnemonic'=>'SU' ] );
        SupportPerson::create(['name' => 'Vanessa Valle' , 'mnemonic'=>'VV' ] );
        SupportPerson::create(['name' => 'Xavier Dyer' , 'mnemonic'=>'XD' ] );



    }
}
