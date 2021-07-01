<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\SupportPerson;
use App\Models\VirtualMeetingLink;
use App\Models\VirtualRoom;
use Illuminate\Http\Request;

class BasicBookingsCalendarController extends Controller
{
    //
    public function index()
    {

        return view('basicbookingscalendar.index');

    }

    public function getBookingsByWeek(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);
        $input = $request->all();

       $orderBy = json_decode($input["orderBy"],true);

    //    dd($input);
        $query = Booking::select('bookings.id as booking_id',
                                'bookings.booking_date',
                                'bookings.topic as topic',
                                'areas.mnemonic as area',
                                'instructors.mnemonic as instructor',
                                'instructors.name as instructor_name',
                                'programs.mnemonic as program',
                                'bookings.start_time as start_time',
                                'bookings.end_time as end_time',
                                'physical_rooms.mnemonic as physical_room',
                                'virtual_meeting_links.link as link',
                                'virtual_meeting_links.password as password',
                                'support_people_string as support',
                                'programs.class as program_class',
                                'virtual_room_capacity'
                                // 'booking_support_persons.support_person_id as sp_id',
                                // 'booking_support_persons.support_role as sp_role',
                                // 'booking_support_persons.support_type as sp_type',
                                )
                            ->leftjoin('areas', 'bookings.area_id', '=', 'areas.id')
                            ->leftjoin('instructors', 'bookings.instructor_id', '=', 'instructors.id')
                            ->leftjoin('programs', 'bookings.program_id', '=', 'programs.id')
                            ->leftjoin('physical_rooms', 'bookings.physical_room_id', '=', 'physical_rooms.id')
                            ->leftjoin('virtual_meeting_links', 'bookings.virtual_meeting_link_id', '=', 'virtual_meeting_links.id')
                            // ->leftjoin('booking_support_persons', 'bookings.id', '=', 'booking_support_persons.booking_id')
                            ->where('booking_date', '>=', $input['from'])
                            ->where('booking_date', '<=', $input['to'])
                            ;

               // Retrieve Virtual Rooms for each Booking
                $query->addSelect(['virtual_room_id' =>VirtualMeetingLink::select('virtual_room_id')
                    ->whereColumn('virtual_meeting_link_id','virtual_meeting_links.id'),
                    'virtual_room' => VirtualRoom::select('mnemonic')
                    ->wherecolumn('virtual_room_id','id')
                ]);

                //Retrieve Support Person name and mnemonic for each Booking
                // $query->addSelect(['sp_name' =>SupportPerson::select('name')
                // ->whereColumn('sp_id','support_persons.id'),
                // 'sp_mnemonic' => SupportPerson::select('mnemonic')
                // ->wherecolumn('sp_id','support_persons.id')
                // ]);

         foreach ( $orderBy as $field){
            $query->orderBy($this->translateField($field["orderBy"]));

         }

       //  dd($query->get());
        return response()->json($query->get());


    }

    private function translateField($field){
        switch ($field) {


            case 'Aula Física':
                return 'physical_room';
            case 'Aula Virtual':
                return 'virtual_room';
            case 'Hora de Inicio':
                return 'start_time';

            case 'Profesor':
                return 'instructor_name';

            case 'Programa':
                return 'program';

        }
    }

}
