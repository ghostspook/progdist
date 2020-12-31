<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\PhysicalRoom;
use App\Models\Program;
use App\Models\SupportPerson;
use App\Models\Instructor;
use App\Models\Booking;
use App\Models\BookingSupportPerson;
use App\Models\SupportPersonRole;

class BookingController extends Controller
{

    public function create ()
    {
        $bookings= Booking::all();


        return view ('bookings.create', [
            'areas' => Area::get(),
            'instructors' => Instructor::get(),
            'programs' => Program::get(),
            'physicalRooms' => PhysicalRoom::get(),
            'supportPeople' => SupportPerson::select('mnemonic')->distinct()->get(),



            'bookings' => $bookings,

         //   'supportRoles' => $supportRoles,
            // 'bookings' => Booking::select('bookings.booking_date',
            //                                'areas.mnemonic as area',
            //                                'instructors.mnemonic as instructor',
            //                                 'programs.mnemonic as program',
            //                                 'bookings.start_time as start_time',
            //                                 'bookings.end_time as end_time',
            //                                 'physical_rooms.mnemonic as physical_room',
            //                                 'virtual_meeting_links.link as link',
            //                                 'virtual_meeting_links.password as password'
            //                                 )
            //     ->join('areas', 'bookings.area_id', '=', 'areas.id')
            //     ->join('instructors', 'bookings.instructor_id', '=', 'instructors.id')
            //     ->join('programs', 'bookings.program_id', '=', 'programs.id')
            //     ->join('physical_rooms', 'bookings.physical_room_id', '=', 'physical_rooms.id')
            //     ->join('virtual_meeting_links', 'bookings.virtual_meeting_link_id', '=', 'virtual_meeting_links.id')
            //     ->get(),






        ]);



    }

    public function store (Request $request)
    {

        $this->validate($request, [
            'endTime'  => 'gt:startTime',
            'bookingDate' => 'required'
        ], [
            'endTime.gt' => 'La hora de fin debe ser mayor que la hora de inicio',
            'bookingDate.required' => 'Debe seleccionar una fecha para esta sesión'
        ]);
        dd($request->get('bookingDate'));

    }
}