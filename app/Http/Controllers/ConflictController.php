<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\VirtualMeetingLink;
use App\Models\VirtualRoom;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class ConflictController extends Controller
{
    //
    public function index()
    {
        return view('conflicts.instructors.index');
    }

    public function virtualRoomsConflictsIndex ()
    {
        return view('conflicts.virtualrooms.index');
    }

    private function translateFieldForInstructorConflicts($field){
        switch ($field) {
            case 'bookingDate':
                return 'B.booking_date';
            case 'area':
                return 'areas.mnemonic';
            case 'instructor':
                return 'instructors.name';
            case 'program':
                return 'programs.mnemonic';
            case 'startTime':
                return 'B.start_time';
            case 'endTime':
                return 'B.end_time';

        }
    }

    public function getVirtualRoomConflicts (Request $request)
    {

        $bookingDate = Carbon::parse($request["booking_date"])->format('Y-m-d');

        $query = " SELECT id, booking_date, start_time, end_time,  " .
                " (select mnemonic from programs where id=a.program_id) as program ," .
                "(select id from virtual_rooms where id= (select virtual_room_id from virtual_meeting_links where id=a.virtual_meeting_link_id) ) as virtualRoom " .
                ", (SELECT count(*) from bookings c  WHERE start_time<= addtime(a.end_time,'00:15:00') AND end_time>= addtime(a.start_time, '-00:15:00') AND " .
                " (select id from virtual_rooms where id= (select virtual_room_id from virtual_meeting_links where id=c.virtual_meeting_link_id) )=virtualRoom AND " .
                " a.program_id <> c.program_id AND " .
                "DATE_FORMAT(booking_date, '%Y-%m-%d')='" . $bookingDate . "' AND a.id<>c.id ) as overlap " .
                "FROM bookings a  where DATE_FORMAT(booking_date, '%Y-%m-%d')='" . $bookingDate . "' " .
                " HAVING overlap >=1 ;"
                ;

        $records = DB::select($query);


        return view('conflicts.virtualrooms.index', [ 'virtualRoomConflicts' => $records, 'booking_date' => $bookingDate]);


    }
    public function getInstructorConflicts (Request $request)
    {
        //  dd($request->all());
        // $input = $request->all();
        $input = json_decode($request["params"],true);
        $from = Carbon::parse($request["from"]);
        $to = Carbon::parse($request["to"]);
        $instructor = (int) $request["instructor"];


        //RAW SQL QUERY TO BE CONVERTED TO ELOQUENT
    //   "SELECT B.instructor_id, B.booking_date, programs.mnemonic, B.program_id
    //             FROM bookings as B
    //             inner join instructors on B.instructor_id = instructors.id
    //             left join programs on B.program_id = programs.id ,
    //                 (SELECT instructor_id, booking_date from bookings
    //                 GROUP BY instructor_id, booking_date
    //                 HAVING Count(instructor_id)>1 AND COUNT(booking_date)>1
    //                 ) as T
    //             WHERE T.instructor_id= B.instructor_id AND B.booking_date=T.booking_date
    //             ORDER BY B.booking_date, B.instructor_id;";

        $instructorConflicts= DB::table('bookings')
                                ->select ('instructor_id', 'booking_date')
                                ->groupBy('instructor_id','booking_date')
                                ->havingRaw('COUNT(instructor_id)>1 AND COUNT(booking_date)>1')
                                ;

        $query= DB::table('bookings as B')
                        // ->select('B.id as booking_id', 'B.booking_date as bookingDate',
                        //          'programs.mnemonic as program', 'areas.mnemonic as area',
                        //          'instructors.name as instructor', 'B.start_time as startTime',
                        //          'B.end_time as endTime',

                        //         )
                        ->select(DB::raw("B.id as booking_id, B.booking_date as bookingDate, " .
                                        "programs.mnemonic as program , areas.mnemonic as area," .
                                        "instructors.name as instructor , DATE_FORMAT(B.start_time,'%Y-%m-%d %H:%i') as startTime , " .
                                        "DATE_FORMAT(B.end_time,'%Y-%m-%d %H:%i') as endTime"
                                        ))
                        ->join('instructors','B.instructor_id','=','instructors.id')
                        ->join('areas','B.area_id','=','areas.id')
                        ->leftjoin('programs','B.program_id','=','programs.id')
                        ->joinSub($instructorConflicts,'conflicts',function ($join){
                                $join->on('B.instructor_id','=','conflicts.instructor_id');
                                $join->on('B.booking_date','=','conflicts.booking_date');
                        })

                        ;


        foreach ($input["columnFilters"] as $field=>$value){
            if ($value <> ""){
              $query->where($this->translateFieldForInstructorConflicts($field), 'like', '%' . $value . '%');
            }
        }

        if ($from != "null"){
            $query->where('B.booking_date','>=',$from);
        }

        if ($to != "null"){
            $query->where('B.booking_date','<=',$to);
        }

        if ($instructor != 0 ){
            $query->where('B.instructor_id',$instructor);
        }

        if ( $input["sort"][0]["field"]<> "" ){
            $query->orderby($input["sort"][0]["field"],$input["sort"][0]["type"]);
        }
        else{
            $query->orderby('bookingDate');
        }



        $currentPage = $input['page'];
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });



        return response()->json($query->paginate($input['rowsPerPage']));

    }
}
