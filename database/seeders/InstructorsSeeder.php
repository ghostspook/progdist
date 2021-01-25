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

class InstructorsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
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
Instructor::create(['name' => 'Jorge Monckeberg' , 'mnemonic'=>'JMB' ] );
Instructor::create(['name' => 'Hugo Pérez' , 'mnemonic'=>'HP' ] );
Instructor::create(['name' => 'Francisco Alarcón' , 'mnemonic'=>'FA' ] );
Instructor::create(['name' => 'Bárbara Silva' , 'mnemonic'=>'BS' ] );
Instructor::create(['name' => 'María del Carmen Bernal' , 'mnemonic'=>'MCB' ] );




    }
}
