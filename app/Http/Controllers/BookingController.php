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
            if ( $field == "virtual_room"){
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


        foreach ( $newBooking["supportPeople"] as $supportPerson ){
                    BookingSupportPerson::create(['support_role' => $supportPerson["role"],
                                            'booking_id' => $newObj->id,
                                            'support_person_id'=> $supportPerson["support_person_id"] ,
                                            'support_type' => (int) $supportPerson ["type"],
                                            ]);


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

    public function updateBooking($id, Request $request)
    {
        $b = Booking::find($id);


        if (!$b) {
            abort(404);
        }

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

        $startsAt = (new Carbon($newBooking["startTime"]))->timezone('America/Guayaquil');
        $endsAt = (new Carbon($newBooking["endTime"]))->timezone('America/Guayaquil');
        $b->program_id = $newBooking["program"];
        $b->booking_date =(new Carbon($newBooking["booking_date"]))->timezone('America/Guayaquil');
        $b->area_id = $newBooking["area"];
        $b->instructor_id = $newBooking["instructor"];
        $b->physical_room_id = $newBooking["physicalRoom"];
        $b->virtual_meeting_link_id = $newBooking["link"];
        $b->start_time = $startsAt;
        $b->end_time = $endsAt;
        $b->topic = $newBooking["topic"];
        $b->virtual_room_capacity = $newBooking["virtualRoomCapacity"];


        $b->save();

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


