<?php

namespace App\Http\Controllers;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\PhysicalRoom;
use App\Models\Program;
use App\Models\SupportPerson;
use App\Models\Instructor;
use App\Models\Booking;
use App\Models\BookingAction;
use App\Models\BookingSupportPerson;
use App\Models\InstructorArea;
use App\Models\InstructorConstraint;
use App\Models\SupportPersonRole;
use App\Models\VirtualRoom;
use App\Models\VirtualMeetingLink;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Session\Session;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session as FacadesSession;
use SessionHandler;
use Symfony\Component\HttpFoundation\Session\Session as SessionSession;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\returnSelf;

class BookingController extends Controller
{
    public function index()
    {
        //stringfy all bookings supports for first time
        foreach ( Booking::all() as $b ){
            $this->stringfySupportPeople($b->id);
         }


        //$bookings = Booking::orderBy('booking_date', 'DESC')->paginate(20);
        //return view('bookings.index', ['bookings' => $bookings]);
        return view('bookings.index');

    }

    public function express()
    {
        return view('bookings.express');

    }

    public function overallProgramming()
    {
        return view('bookings.overall-programming');

    }

    public function getOverallProgramming ( Request $request)
    {
        $input = json_decode($request["params"],true);

        $filteredInstructors = [];
        $filteredAreas=[];
        $filteredPrograms = [];

        foreach($input['selectedInstructors'] as $filteredInstructor){
            array_push($filteredInstructors,$filteredInstructor['id']);
        }

        foreach($input['selectedAreas'] as $filteredArea){
            array_push($filteredAreas,$filteredArea['id']);
        }

        foreach($input['selectedPrograms'] as $filteredProgram){
            array_push($filteredPrograms,$filteredProgram['id']);
        }


        $sessions = Booking::select('instructor_id','program_id','booking_date')
                            //->whereYear('booking_date','=',$input['year'])
                            ->where('booking_date', '>=', $input['from'])
                            ->where('booking_date', '<=', $input['to'])
                            ->where('instructor_id','!=', null)
                            ;



        $instructors =  Booking::select('instructor_id')
                                //->whereYear('booking_date','=',$input['year'])
                                ->where('booking_date', '>=', $input['from'])
                                ->where('booking_date', '<=', $input['to'])
                                ->where('instructor_id','!=', null)
                                ;


        if (count ($filteredAreas)>0){
            $sessions->whereIn('area_id',$filteredAreas);
            $instructors->whereIn('area_id',$filteredAreas);
        }


        if (count ($filteredInstructors)>0){
            $sessions->whereIn('instructor_id',$filteredInstructors);
            $instructors->whereIn('instructor_id',$filteredInstructors);
        }

        if (count ($filteredPrograms)>0){
            $sessions->whereIn('program_id',$filteredPrograms);
            $instructors->whereIn('program_id',$filteredPrograms);
        }


        $sessions= $sessions->groupBy('instructor_id','program_id','booking_date')
                    ->orderBy('booking_date','asc')
                    ->orderBy('instructor_id')
                    ->with('instructor','program')
                    ->get()
                    ;

        $instructors = $instructors->groupBy('instructor_id')
                    ->get()
                    ;


        $bookedInstructors = [];


        foreach ($instructors as $instructor){
            array_push($bookedInstructors, [ 'instructor_id' => $instructor['instructor']['id'] ,
                                            'name' => $instructor['instructor']['name'] ,
                                            'mnemonic' => $instructor['instructor']['mnemonic'] ,
                                            ]);

        }



        usort($bookedInstructors,function($a,$b){
            return strtolower($a['mnemonic']) > strtolower($b['mnemonic']);
        });


        $bookedInstructorsIds = array_column($bookedInstructors, 'instructor_id');

        $constraints = InstructorConstraint::whereIn('instructor_id',$bookedInstructorsIds)
                                            ->where(function ($q) use($input) {
                                                $q->where('from', '<=', $input['from'])
                                                ->where('to', '>=', $input['from']);
                                                })->orWhere(function($q) use($input){
                                                    $q->where('from', '<=', $input['to'])
                                                    ->where('to', '>=', $input['to']);
                                                })->orWhere(function($q) use($input){
                                                    $q->where('from', '>=', $input['from'])
                                                    ->where('from', '<=', $input['to']);
                                                })->orWhere(function($q) use($input){
                                                    $q->where('to', '>=', $input['from'])
                                                    ->where('to', '<=', $input['to']);
                                                })
                                                ->get();




        return response()->json(['sessions' =>$sessions,
                                'instructors'=> $bookedInstructors,
                                'constraints' => $constraints,
                                ]);

    }

    public function getOverallProgrammingSessionDetails(Request $request)
    {
        $input = json_decode($request["params"],true);

        $details = Booking::where('instructor_id',$input['instructor_id'])
                            ->where('program_id',$input['program_id'])
                            ->where('booking_date',$input['booking_date'])
                            ->with('instructor','program','area')
                            ->get();

        return response()->json(['details' => $details,
                                ]);

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

    public function destroy($id)
    {
        $b = Booking::find($id);

        BookingAction::create([
            'user_id' => Auth::user()->id,
            'booking_id' => $id,
            'action' => 1, // Create
            'json' => json_encode($b),
        ]);

        $b->delete();

        return response("Success on Delete", 200);
        //return redirect()->route('bookings.index');


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

    public function getInstructors()
    {
        $instructors = Instructor::all();
        return response()->json($instructors);
    }

    public function getPrograms(Request $request)
    {


        if (is_null($request["onlyvisible"]) || $request["onlyvisible"]=="false" ) {
            $programs = Program::all();

        }
        else
        {
            $programs = Program::where('is_visible',true)->get();

        }




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
        $supportPeople = SupportPerson::select('id','mnemonic','name')->get();

        return response()->json($supportPeople);
    }



    public function getAllBookingsJson (){

        $query = Booking::select('bookings.id as booking_id',
                                'bookings.booking_date',
                                'areas.mnemonic as area',
                                'instructors.mnemonic as instructor',
                                'programs.mnemonic as program',
                                'bookings.start_time as start_time',
                                'bookings.end_time as end_time',
                                'physical_rooms.mnemonic as physical_room',
                                'virtual_meeting_links.link as link',
                                'virtual_meeting_links.password as password',
                                'support_people_string as support',

                                )
                ->leftjoin('areas', 'bookings.area_id', '=', 'areas.id')
                ->leftjoin('instructors', 'bookings.instructor_id', '=', 'instructors.id')
                ->leftjoin('programs', 'bookings.program_id', '=', 'programs.id')
                ->leftjoin('physical_rooms', 'bookings.physical_room_id', '=', 'physical_rooms.id')
                ->leftjoin('virtual_meeting_links', 'bookings.virtual_meeting_link_id', '=', 'virtual_meeting_links.id')
                ;

                //Retrieve Virtual Rooms for each Booking
                $query->addSelect(['virtual_room_id' =>VirtualMeetingLink::select('virtual_room_id')
                            ->whereColumn('virtual_meeting_link_id','virtual_meeting_links.id'),
                            'virtual_room' => VirtualRoom::select('mnemonic')
                            ->wherecolumn('virtual_room_id','id')
                        ]);

        return response()->json($query->get());
    }

    public function getBookings(Request $request)
    {

       //  dd($request->all());
        // $input = $request->all();
        $input = json_decode($request["params"],true);
       // dd($input);

        //dd($input["sort"][0]["field"]);
       // $query = Booking::with(['area', 'instructor', 'program', 'physicalRoom', 'virtualMeetingLink.virtualRoom','bookingSupportPersons.supportPerson']);

        $query = Booking::select('bookings.id as booking_id',
                                'bookings.booking_date',
                                'areas.mnemonic as area',
                                'instructors.mnemonic as instructor',
                                'programs.mnemonic as program',
                                'bookings.start_time as start_time',
                                'bookings.end_time as end_time',
                                'physical_rooms.mnemonic as physical_room',
                                'virtual_meeting_links.link as link',
                                'virtual_meeting_links.password as password',
                                'support_people_string as support',
                                'virtual_room_capacity'

                                )
            ->leftjoin('areas', 'bookings.area_id', '=', 'areas.id')
            ->leftjoin('instructors', 'bookings.instructor_id', '=', 'instructors.id')
            ->leftjoin('programs', 'bookings.program_id', '=', 'programs.id')
            ->leftjoin('physical_rooms', 'bookings.physical_room_id', '=', 'physical_rooms.id')
            ->leftjoin('virtual_meeting_links', 'bookings.virtual_meeting_link_id', '=', 'virtual_meeting_links.id')
            ;

            //Retrieve Virtual Rooms for each Booking
            $query->addSelect(['virtual_room_id' =>VirtualMeetingLink::select('virtual_room_id')
                                    ->whereColumn('virtual_meeting_link_id','virtual_meeting_links.id'),
                                    'virtual_room' => VirtualRoom::select('mnemonic')
                                    ->wherecolumn('virtual_room_id','id')
                                ]);

        //look for Bookings on Date range
        if ( array_key_exists("from",$input) ){
            $query->where('booking_date', '>=', $input['from']);

        }

        if ( array_key_exists("to",$input) ){
            $query->where('booking_date', '<=', $input['to']);
        }

        //look for Bookings on Date range filtered by VueGood Table Header controls
              if ( array_key_exists("fromBookingDate",$input) ){
                $query->where('booking_date', '>=', $input['fromBookingDate']);

            }

            if ( array_key_exists("toBookingDate",$input) ){
                $query->where('booking_date', '<=', $input['toBookingDate']);
            }



        foreach ($input["columnFilters"] as $field=>$value){
            if ($value <> "" &&  $field <> "virtual_room"){
                $query->where($this->translateField($field), 'like', '%' . $value . '%');
            }
            if ($value <> "" &&  $field == "virtual_room"){
                $query->having('virtual_room','like', '%' . $value . '%');

            }
        }


        if ( $input["sort"][0]["field"]<> "" ){
          $query->orderby($input["sort"][0]["field"],$input["sort"][0]["type"]);
        }



        $currentPage = $input['page'];
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });

        return response()->json($query->paginate($input['rowsPerPage']));
    }

    public function getBookingsBunch(Request $request)
    {
        $bookingsIds = $request["bookings_ids"];
        //  dd($request);

         //dd($input["sort"][0]["field"]);
        // $query = Booking::with(['area', 'instructor', 'program', 'physicalRoom', 'virtualMeetingLink.virtualRoom','bookingSupportPersons.supportPerson']);

         $query = Booking::select('bookings.id as booking_id',
                                 'bookings.booking_date',
                                 'areas.mnemonic as area',
                                 'areas.id as area_id',
                                 'instructors.mnemonic as instructor',
                                 'instructors.id as instructor_id',
                                 'programs.mnemonic as program',
                                 'programs.id as program_id',
                                 'bookings.start_time as start_time',
                                 'bookings.end_time as end_time',
                                 'physical_rooms.mnemonic as physical_room',
                                 'physical_rooms.id as physical_room_id',
                                 'virtual_meeting_links.link as link',
                                 'virtual_meeting_links.id as link_id',
                                 'virtual_meeting_links.password as password',
                                 'support_people_string as support',
                                 'virtual_room_capacity',
                                 'bookings.topic as meeting_topic',

                                 )
             ->leftjoin('areas', 'bookings.area_id', '=', 'areas.id')
             ->leftjoin('instructors', 'bookings.instructor_id', '=', 'instructors.id')
             ->leftjoin('programs', 'bookings.program_id', '=', 'programs.id')
             ->leftjoin('physical_rooms', 'bookings.physical_room_id', '=', 'physical_rooms.id')
             ->leftjoin('virtual_meeting_links', 'bookings.virtual_meeting_link_id', '=', 'virtual_meeting_links.id')
             ;

             //Retrieve Virtual Rooms for each Booking
            $query->addSelect(['virtual_room_id' =>VirtualMeetingLink::select('virtual_room_id')
                                     ->whereColumn('virtual_meeting_link_id','virtual_meeting_links.id'),
                                     'virtual_waiting_room' =>VirtualMeetingLink::select('waiting_room')
                                     ->whereColumn('virtual_meeting_link_id','virtual_meeting_links.id'),
                                     'virtual_room' => VirtualRoom::select('mnemonic')
                                     ->whereColumn('virtual_room_id','id'),
                                     'virtual_room_name' => VirtualRoom::select('name')
                                     ->whereColumn('virtual_room_id','id')
                                ]);


            $supportPeople = BookingSupportPerson::select('support_person_id as person_id',
                                                            'support_role as role',
                                                            'support_type as type',
                                                            'support_persons.name as name',
                                                            'support_persons.name as mnemonic'

                            )
                            ->leftjoin('support_persons','booking_support_persons.support_person_id','=','support_persons.id')
                            ->whereIn('booking_support_persons.booking_id',$bookingsIds)
                            ;





            return response()->json( [ 'bookings' => $query->whereIn('bookings.id',$bookingsIds)
                                                        ->get(),
                                        'supportPeople' => $supportPeople->get()
                                    ]);
    }

    private function translateField($field){
        switch ($field) {
            case 'booking_date':
                return 'bookings.booking_date';
            case 'area':
                return 'areas.mnemonic';
            case 'instructor':
                return 'instructors.mnemonic';
            case 'program':
                return 'programs.mnemonic';
            case 'start_time':
                return 'bookings.start_time';
            case 'end_time':
                return 'bookings.end_time';
            case 'physical_room':
                return 'physical_rooms.mnemonic';
            case 'link':
                return 'virtual_meeting_links.link';
            case 'password':
                return 'virtual_meeting_links.password';


        }
    }



    public function getBooking ($id)
    {
        $booking = Booking::with(['area', 'instructor', 'program', 'physicalRoom', 'virtualMeetingLink.virtualRoom','bookingSupportPersons.supportPerson'])->find($id);
        return response()->json($booking);
    }

    public function getBookingsBunchForCloning (Request $request)
    {
        $bookingsIds = $request["bookings_ids"];

        $bookings = Booking::with(['area', 'instructor', 'program', 'physicalRoom',
                                'virtualMeetingLink.virtualRoom',
                                'bookingSupportPersons.supportPerson'])->whereIn('bookings.id',$bookingsIds)->get();

        return response()->json([ 'bookings' => $bookings]);

    }



    public function storeBooking(Request $request)
    {
        //dd($request->newBooking);

        $newBooking = $request->newBooking;

        if (is_null($newBooking["program"]))
        {
            return response()->json([
                "status" => "error",
                "errorCode" => 1,
                "errorMessage" => "Missing Program"
            ])->setStatusCode(400);
        }


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


        $startsAt = (new Carbon($newBooking["startTime"]))->timezone('America/Guayaquil');
        $endsAt = (new Carbon($newBooking["endTime"]))->timezone('America/Guayaquil');
        $newObj = Booking::create(['program_id' => $newBooking["program"] ,
                                    'booking_date' => ( new Carbon($newBooking["booking_date"]))->timezone('America/Guayaquil') ,
                                    'area_id'=>$newBooking["area"],
                                    'instructor_id'=> $newBooking["instructor"],
                                    'virtual_meeting_link_id'=> $newBooking["link"],
                                    'physical_room_id'=>  $newBooking["physicalRoom"],
                                    'start_time'=> $startsAt,
                                    'end_time'=> $endsAt,
                                    'topic'=> $newBooking["topic"],
                                    'virtual_room_capacity'=>(int) $newBooking["virtualRoomCapacity"],
                                    ]);

        if (array_key_exists('supportPeople',$newBooking))
        {
            foreach ( $newBooking["supportPeople"] as $supportPerson ){
                        BookingSupportPerson::create(['support_role' => $supportPerson["role"],
                                                'booking_id' => $newObj->id,
                                                'support_person_id'=> $supportPerson["support_person_id"] ,
                                                'support_type' => (int) $supportPerson ["type"],
                                                ]);


            }
        }

        //save stringfy Support people for this booking
        $this->stringfySupportPeople($newObj->id);

        BookingAction::create([
            'user_id' => Auth::user()->id,
            'booking_id' => $newObj->id,
            'action' => 1, // Create
            'json' => json_encode($newBooking),
        ]);

        return response()->json([
            "status" => "success",
            "bookingId" => $newObj->id,
        ]);
    }

    public function storeBookingsBunch(Request $request)
    {
        //dd($request->cloningDates);
        //dd($request->newBooking);

        $newBooking = $request->newBooking;
        $cloningDates = $request->cloningDates;

        // if (is_null($newBooking["program"]))
        // {
        //     return response()->json([
        //         "status" => "error",
        //         "errorCode" => 1,
        //         "errorMessage" => "Missing Program"
        //     ])->setStatusCode(400);
        // }


        // if (!$newBooking["booking_date"])
        // {
        //     return response()->json([
        //         "status" => "error",
        //         "errorCode" => 1,
        //         "errorMessage" => "Missing date"
        //     ])->setStatusCode(400);
        // }

        // if (!$newBooking["startTime"] || !$newBooking["endTime"])
        // {
        //     return response()->json([
        //         "status" => "error",
        //         "errorCode" => 2,
        //         "errorMessage" => "Missing start or end time"
        //     ])->setStatusCode(400);
        // }

        // if ($newBooking["startTime"] >= $newBooking["endTime"])
        // {
        //     return response()->json([
        //         "status" => "error",
        //         "errorCode" => 3,
        //         "errorMessage" => "Event starts before end time"
        //     ])->setStatusCode(400);
        // }

        // if (!$newBooking["topic"] && !$newBooking["program"])
        // {
        //     return response()->json([
        //         "status" => "error",
        //         "errorCode" => 3,
        //         "errorMessage" => "Missing program or topic"
        //     ])->setStatusCode(400);
        // }
        $response = [];
        foreach ( $newBooking as $booking ){


            foreach ($cloningDates as $cloningDate) {
                $startsAt = (new Carbon($booking["start_time"]))->timezone('America/Guayaquil');
                $endsAt = (new Carbon($booking["end_time"]))->timezone('America/Guayaquil');
                $newObj = Booking::create(['program_id' => $booking["program_id"] ,
                                            'booking_date' => ( new Carbon($cloningDate["start"]))->timezone('America/Guayaquil') ,
                                            'area_id'=>$booking["area_id"],
                                            'instructor_id'=> $booking["instructor_id"],
                                            'virtual_meeting_link_id'=> $booking["virtual_meeting_link_id"],
                                            'physical_room_id'=>  $booking["physical_room_id"],
                                            'start_time'=> $startsAt,
                                            'end_time'=> $endsAt,
                                            'topic'=> $booking["topic"],
                                            'virtual_room_capacity'=>(int) $booking["virtual_room_capacity"],
                                            ]);

                if (array_key_exists('support_people',$booking))
                {

                    foreach ( $booking["support_people"] as $supportPerson ){
                                BookingSupportPerson::create(['support_role' => $supportPerson["role"],
                                                        'booking_id' => $newObj->id,
                                                        'support_person_id'=> $supportPerson["support_person_id"] ,
                                                        'support_type' => (int) $supportPerson ["type"],
                                                        ]);


                    }
                }

                //save stringfy Support people for this booking
                $this->stringfySupportPeople($newObj->id);

                BookingAction::create([
                    'user_id' => Auth::user()->id,
                    'booking_id' => $newObj->id,
                    'action' => 1, // Create
                    'json' => json_encode($booking),
                ]);

                array_push($response,$newObj->id);
            }
        }
            return response()->json([
                "status" => "success",
                "bookingsIds" => $response,
            ]);

    }

    public function splitBookingsBunch (Request $request)
    {
        $bookings = $request->bookings;
        $splitRanges = $request->splitRanges;

        $response = [];
        foreach ( $bookings as $booking )
        {

            foreach ($splitRanges as $key => $splitRange)
            {
                if ($key==0)
                {

                    //The booking which is going to be splitted, must be updated to updated to set
                    //start_time and end_time with the first split range provided

                    $startsAt = (new Carbon($splitRange["startTime"]))->timezone('America/Guayaquil');
                    $endsAt = (new Carbon($splitRange["endTime"]))->timezone('America/Guayaquil');

                    $b = Booking::find($booking["id"]);
                    $b->start_time = $startsAt;
                    $b->end_time = $endsAt;
                    $b->save();
                }


                if ($key > 0)
                {
                    $startsAt = (new Carbon($splitRange["startTime"]))->timezone('America/Guayaquil');
                    $endsAt = (new Carbon($splitRange["endTime"]))->timezone('America/Guayaquil');
                    $newObj = Booking::create(['program_id' => $booking["program_id"] ,
                                                'booking_date' => ( new Carbon($booking["booking_date"]))->timezone('America/Guayaquil') ,
                                                'area_id'=>$booking["area_id"],
                                                'instructor_id'=> $booking["instructor_id"],
                                                'virtual_meeting_link_id'=> $booking["virtual_meeting_link_id"],
                                                'physical_room_id'=>  $booking["physical_room_id"],
                                                'start_time'=> $startsAt,
                                                'end_time'=> $endsAt,
                                                'topic'=> $booking["topic"],
                                                'virtual_room_capacity'=>(int) $booking["virtual_room_capacity"],
                                                ]);

                    if (array_key_exists('support_people',$booking))
                    {

                        foreach ( $booking["support_people"] as $supportPerson ){
                                    BookingSupportPerson::create(['support_role' => $supportPerson["role"],
                                                            'booking_id' => $newObj->id,
                                                            'support_person_id'=> $supportPerson["support_person_id"] ,
                                                            'support_type' => (int) $supportPerson ["type"],
                                                            ]);


                        }
                    }

                    //save stringfy Support people for this booking
                    $this->stringfySupportPeople($newObj->id);

                    BookingAction::create([
                        'user_id' => Auth::user()->id,
                        'booking_id' => $newObj->id,
                        'action' => 1, // Create
                        'json' => json_encode($booking),
                    ]);

                    array_push($response,$newObj->id);
                }
            }
        }

        return response()->json([
            "status" => "success",
            "bookingsIds" => $response,
        ]);

    }




    public function updateBooking($id, Request $request)
    {
        $b = Booking::find($id);


        if (!$b) {
            abort(404);
        }

        $newBooking = $request->newBooking;



        // if (!$newBooking["booking_date"])
        // {
        //     return response()->json([
        //         "status" => "error",
        //         "errorCode" => 1,
        //         "errorMessage" => "Missing date"
        //     ])->setStatusCode(400);
        // }

        // if (!$newBooking["startTime"] || !$newBooking["endTime"])
        // {
        //     return response()->json([
        //         "status" => "error",
        //         "errorCode" => 2,
        //         "errorMessage" => "Missing start or end time"
        //     ])->setStatusCode(400);
        // }

        // if ($newBooking["startTime"] >= $newBooking["endTime"])
        // {
        //     return response()->json([
        //         "status" => "error",
        //         "errorCode" => 3,
        //         "errorMessage" => "Event starts before end time"
        //     ])->setStatusCode(400);
        // }

        // if (!$newBooking["topic"] && !$newBooking["program"])
        // {
        //     return response()->json([
        //         "status" => "error",
        //         "errorCode" => 3,
        //         "errorMessage" => "Missing program or topic"
        //     ])->setStatusCode(400);
        // }




        if (array_key_exists('startTime',$newBooking))
        {
            $startsAt = (new Carbon($newBooking["startTime"]))->timezone('America/Guayaquil');
            $b->start_time = $startsAt;
        }



        if (array_key_exists('endTime',$newBooking))
        {
            $endsAt = (new Carbon($newBooking["endTime"]))->timezone('America/Guayaquil');
            $b->end_time = $endsAt;
        }

        if (array_key_exists('program',$newBooking))
        {
            $b->program_id = $newBooking["program"];
        }

        if (array_key_exists('booking_date',$newBooking))
        {
            $b->booking_date =(new Carbon($newBooking["booking_date"]))->timezone('America/Guayaquil');
        }

        if (array_key_exists('area',$newBooking))
        {
            $b->area_id = $newBooking["area"];
        }

        if (array_key_exists("instructor",$newBooking))
        {
            $b->instructor_id = $newBooking["instructor"];
        }

        if (array_key_exists("physicalRoom",$newBooking))
        {
            $b->physical_room_id = $newBooking["physicalRoom"];
        }

        if (array_key_exists("link",$newBooking))
        {
            $b->virtual_meeting_link_id = $newBooking["link"];
        }

        if (array_key_exists("topic",$newBooking))
        {
            $b->topic = $newBooking["topic"];
        }

        if (array_key_exists("virtualRoomCapacity",$newBooking))
        {
            $b->virtual_room_capacity = $newBooking["virtualRoomCapacity"];
        }



        $b->save();


        if(array_key_exists("supportPeople",$newBooking))
        {

            BookingSupportPerson::where('booking_id', $id)->delete();

            foreach ( $newBooking["supportPeople"] as $supportPerson ){
                        BookingSupportPerson::create(['support_role' => $supportPerson["role"],
                                                    'support_type' => $supportPerson ["type"],
                                                'booking_id' => $id,
                                                'support_person_id'=> $supportPerson["support_person_id"] ,

                                                ]);
            }

            //Update stringfy Support people for this booking
            $this->stringfySupportPeople($b->id);
        }

        BookingAction::create([
            'user_id' => Auth::user()->id,
            'booking_id' => $id,
            'action' => 2, // Edit
            'json' => json_encode($newBooking),
        ]);

        return response()->json([
            "status" => "success"
        ]);
    }

    public function updateMultiBooking( Request $request)
    {

        $newBookings = $request->newBookings;


        foreach ($newBookings as $nb){
           // dd($nb);

            $b = Booking::find($nb["booking_id"]);

            if (!$b) {
                abort(404);
            }


            if (!$nb["booking_date"])
            {
                return response()->json([
                    "status" => "error",
                    "errorCode" => 1,
                    "errorMessage" => "Missing date"
                ])->setStatusCode(400);
            }

            if (!$nb["topic"] && !$nb["program"])
            {
                return response()->json([
                    "status" => "error",
                    "errorCode" => 3,
                    "errorMessage" => "Missing program or topic"
                ])->setStatusCode(400);
            }


            $b->program_id = $nb["program"];
            $b->booking_date =(new Carbon($nb["booking_date"]))->timezone('America/Guayaquil');

            $b->physical_room_id = $nb["physicalRoom"];
            $b->virtual_meeting_link_id = $nb["link"];

            $b->topic = $nb["topic"];
            $b->virtual_room_capacity = $nb["virtualRoomCapacity"];


            $b->save();

            BookingSupportPerson::where('booking_id', $nb["booking_id"])->delete();

            foreach ( $nb["supportPeople"] as $supportPerson ){
                BookingSupportPerson::create(['support_role' => $supportPerson["role"],
                                            'support_type' => $supportPerson ["type"],
                                        'booking_id' => $nb["booking_id"],
                                        'support_person_id'=> $supportPerson["support_person_id"] ,

                                        ]);
            }

            //Update stringfy Support people for this booking
            $this->stringfySupportPeople($nb["booking_id"]);

            BookingAction::create([
                'user_id' => Auth::user()->id,
                'booking_id' => $nb["booking_id"],
                'action' => 2, // Edit
                'json' => json_encode($nb),
            ]);

        }

        return response()->json([
            "status" => "success"
        ]);

    }

    public function stringfySupportPeople ($id)
    {
        $b = Booking::find($id);

        $spString = $b->getSupportPersonsSummary();

        $b->support_people_string = Markdown::convertToHtml($spString);

        $b->save();
    }


    public function dataTable(Request $request)
     {
       $bookings = Booking::with(['area', 'instructor', 'program', 'physicalRoom', 'virtualMeetingLink.virtualRoom'])->select('bookings.*');

        return Datatables::eloquent($bookings)
            ->addColumn('day_name', function($b){
                return
                    $b->booking_date->dayName;
            })
            ->addColumn('program_title', function ($b) {
                return ($b->program && $b->program->mnemonic == '(Reunión)')
                    ? $b->topic : $b->program->mnemonic;
            })
            ->editColumn('booking_date', function($b){
                return
                    $b->booking_date->format('d-M-Y');
            })
            ->editColumn('start_time', function($b) {
                return
                    $b->start_time->format('H:i');
            })
            ->editColumn('end_time', function($b) {
                return
                    $b->end_time->format('H:i');
            })
            ->addColumn('link', function($b) {
                return !$b->virtualMeetingLink ? "" :
                   '<a href="'.$b->virtualMeetingLink->link.'">'.
                   $b->virtualMeetingLink->link.'</a>';
            })
            ->addColumn('support_people',function($b) {
                return Markdown::convertToHtml($b->getSupportPersonsSummary());
            })
            ->addColumn('action', function ($b) {
                return Auth::user()->authorizedAccount->can_create_and_edit_bookings ?
                    '<form method="POST" action="'.route('bookings.destroy', ['id' => $b->id]).'">'.
                        '<input type="hidden" name="_method" value="delete" />'.
                        '<input type="hidden" name="_token" value="'.csrf_token().'" />'.
                        '<button type="submit" class="btn btn-sm btn-danger ml-2"><i class="fa fa-trash"></i></button>'.
                        '<a class="edit btn btn-sm btn-primary ml-2" href="#" onclick="onBookingClick('.$b->id.')"><i class="fa fa-edit"></i></a>'.
                    '</form>'
                    : "";


            })
            ->rawColumns(['link','support_people','action'])
            ->make(true);

//         $bookings = Booking::addselect(['area' => Area::select('mnemonic')
//                      ->whereColumn('area_id','areas.id'),
//                      'instructor' => Instructor::select('mnemonic')
//                      ->whereColumn('instructor_id','instructors.id'),
//                      'program' => Program::select('mnemonic')
//                      ->whereColumn('program_id','programs.id'),
//                      'physical_room' => PhysicalRoom::select('mnemonic')
//                      ->whereColumn('physical_room_id','physical_rooms.id'),
//                      'link' => VirtualMeetingLink::select('link')
//                      ->whereColumn('virtual_meeting_link_id','virtual_meeting_links.id'),
//                      'link_id' =>VirtualMeetingLink::select('id')
//                       ->whereColumn('virtual_meeting_link_id','virtual_meeting_links.id'),
//                       'virtual_room_id' =>VirtualMeetingLink::select('virtual_room_id')
//                       ->whereColumn('virtual_meeting_link_id','virtual_meeting_links.id'),
//                       'virtual_room' => VirtualRoom::select('mnemonic')
//                        ->wherecolumn('virtual_room_id','id')
//                      ]);
//                      //->get();
//
//         return Datatables::of($bookings)
//                             ->make(true);

    }
}


