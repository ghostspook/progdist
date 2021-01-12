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

class VirtualMeetingLinksSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        VirtualMeetingLink::create([ 'topic' => 'Clases MDE 2019 GYE P1 ',  'link' => ' https://us02web.zoom.us/j/407061210 ','password' =>'MDE2021GYE', 'waiting_room' => TRUE,  'virtual_room_id' =>2, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases MDE 2019 GYE P2 ',  'link' => 'https://zoom.us/j/992035816 ','password' =>'MDE2021GYE', 'waiting_room' => TRUE,  'virtual_room_id' =>3, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases MDE 2019 UIO P1 ',  'link' => ' https://us02web.zoom.us/j/515036238 ','password' =>'MDE2021UIO', 'waiting_room' => TRUE,  'virtual_room_id' =>2, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases MDE 2019 UIO P2 ',  'link' => 'https://zoom.us/j/708985179 ','password' =>'MDE2021UIO', 'waiting_room' => TRUE,  'virtual_room_id' =>3, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases In Co - NIRSA - PEF ',  'link' => ' https://us02web.zoom.us/j/87291770504 ','password' =>'2021', 'waiting_room' => TRUE,  'virtual_room_id' =>1, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases MDE 2020 UIO P1 ',  'link' => ' https://us02web.zoom.us/j/84790993992 ','password' =>'MDEUIOP1', 'waiting_room' => TRUE,  'virtual_room_id' =>4, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases MDE 2020 GYE P1 ',  'link' => 'https://us02web.zoom.us/j/82643185101 ','password' =>'MDEGYEP1', 'waiting_room' => TRUE,  'virtual_room_id' =>4, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases Programa Bananeros 2020 ',  'link' => ' https://us02web.zoom.us/j/85464448523 ','password' =>'BAN2020', 'waiting_room' => TRUE,  'virtual_room_id' =>4, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases PPE 2020 ',  'link' => ' https://us02web.zoom.us/j/83838368564 ','password' =>'PPE2020', 'waiting_room' => TRUE,  'virtual_room_id' =>4, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases Programa Acuacultura 2020 ',  'link' => ' https://us02web.zoom.us/j/86914374579 ','password' =>'ACUA20', 'waiting_room' => TRUE,  'virtual_room_id' =>5, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases Incompany B. Braun Medicals 2020 ',  'link' => 'https://us02web.zoom.us/j/82449332421 ','password' =>'BRAUN', 'waiting_room' => TRUE,  'virtual_room_id' =>5, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases Incompany KUBIEC 2020 ',  'link' => ' https://us02web.zoom.us/j/81322083921 ','password' =>'KUB', 'waiting_room' => TRUE,  'virtual_room_id' =>5, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases  Incompany KUBIEC 2020 ',  'link' => ' https://us02web.zoom.us/j/85672959643 ','password' =>'KUB', 'waiting_room' => TRUE,  'virtual_room_id' =>6, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases PEF 2020 ',  'link' => ' https://us02web.zoom.us/j/85284723919 ','password' =>'PEF2020', 'waiting_room' => TRUE,  'virtual_room_id' =>6, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases Incompany FarmaEnlace 2020 ',  'link' => ' https://us02web.zoom.us/j/83683104365 ','password' =>'FARMA', 'waiting_room' => TRUE,  'virtual_room_id' =>6, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases Programa de Liderazgo y Dirección de Personas 2020 ',  'link' => ' https://us02web.zoom.us/j/83039539294 ','password' =>'PLDP', 'waiting_room' => TRUE,  'virtual_room_id' =>6, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases Incompany KUBIEC 2020 online ',  'link' => ' https://us02web.zoom.us/j/87922341402 ','password' =>'KUB', 'waiting_room' => TRUE,  'virtual_room_id' =>7, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases PEOP 2020 ',  'link' => ' https://us02web.zoom.us/j/85276085810 ','password' =>'PEOP', 'waiting_room' => TRUE,  'virtual_room_id' =>7, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases PME 2020 ',  'link' => 'https://us02web.zoom.us/j/84534644029 ','password' =>'PME', 'waiting_room' => TRUE,  'virtual_room_id' =>7, ]);
        VirtualMeetingLink::create([ 'topic' => 'Clases Programa de Liderazgo y Dirección de Personas 2020 ',  'link' => ' https://us02web.zoom.us/j/88478345638 ','password' =>'PLDP', 'waiting_room' => TRUE,  'virtual_room_id' =>7, ]);




    }
}
