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
use App\Models\InstructorArea;
use App\Models\SupportPersonRole;
use App\Models\VirtualRoom;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::orderBy('booking_date', 'DESC')->paginate(20);

        return view('bookings.index', ['bookings' => $bookings]);
    }

    public function create ()
    {
        $bookings= Booking::latest()->paginate(25);


        return view ('bookings.create', [
            'areas' => Area::orderby('mnemonic')->get(),
            'instructors' => Instructor::orderby('mnemonic')->get(),
            'programs' => Program::orderby('mnemonic')->get(),
            'physicalRooms' => PhysicalRoom::orderby('mnemonic')->get(),
            'virtualRooms' => VirtualRoom::orderby('mnemonic')->get(),
            'supportPeople' => SupportPerson::orderby('mnemonic')->get(),



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

    public function getAreas()
    {
        $areas = Area::all();

        return response()->json($areas);
    }

    public function getInstructorAreas()
    {
        $instAreas = InstructorArea::with('instructor:id,name,mnemonic')->get();

        return response()->json($instAreas);
    }

    public function getPrograms()
    {
        $programs = Program::all();

        return response()->json($programs);
    }

    public function getPhysicalRooms()
    {
        $physicalRooms = PhysicalRoom::all();

        return response()->json($physicalRooms);
    }

    public function getVirtualRooms()
    {
        $virtualRooms = VirtualRoom::all();

        return response()->json($virtualRooms);
    }

    public function getSupportPeople()
    {
        $supportPeople = SupportPerson::select('id','mnemonic')->get();

        return response()->json($supportPeople);
    }

    public function storeBooking(Request $request)
    {
        //dd($request->newBooking);
        $newBooking = $request->newBooking;
        if (!$newBooking["booking_date"])
        {
            return response()->json([
                "status" => "error",
                "errorCode" => 1,
                "errorMessage" => "Missing date"
            ])->setStatusCode(400);
        }

        if (!$newBooking["startTime"] || !$newBooking["endTime"])
        {
            return response()->json([
                "status" => "error",
                "errorCode" => 2,
                "errorMessage" => "Missing start or end time"
            ])->setStatusCode(400);
        }

        if ($newBooking["startTime"] >= $newBooking["endTime"])
        {
            return response()->json([
                "status" => "error",
                "errorCode" => 3,
                "errorMessage" => "Event starts before end time"
            ])->setStatusCode(400);
        }

        if (!$newBooking["topic"] && !$newBooking["program"])
        {
            return response()->json([
                "status" => "error",
                "errorCode" => 3,
                "errorMessage" => "Missing program or topic"
            ])->setStatusCode(400);
        }

        $newObj = Booking::create(['program_id' => $newBooking["program"] ,
                                    'booking_date' => $newBooking["booking_date"],
                                    'area_id'=>$newBooking["area"],
                                    'instructor_id'=> $newBooking["instructor"],
                                    //'virtual_meeting_link_id'=>3,
                                    'physical_room_id'=>  $newBooking["physicalRoom"],
                                    'start_time'=> $newBooking["startTime"],
                                    'end_time'=> $newBooking["endTime"],
                                    ]);


        foreach ( $newBooking["supportPeople"] as $supportPerson ){
                    BookingSupportPerson::create(['support_role' => $supportPerson["role"],
                                            'booking_id' => $newObj->id,
                                            'support_person_id'=> $supportPerson["support_person_id"] ,
                                            'support_type' => $supportPerson ["type"],
                                            ]);

        }

        return response()->json([
            "status" => "success"
        ]);
    }
}


