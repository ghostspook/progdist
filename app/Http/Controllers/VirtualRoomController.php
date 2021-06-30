<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Program;
use App\Models\VirtualMeetingLink;
use App\Models\VirtualRoom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VirtualRoomController extends Controller
{
    //

    public function index()
    {
        return view('virtualrooms.index');
    }

    public function getAvailableVirtualRooms(Request $request)
    {

        $datesToSearch = $request->all();




        $query=Booking::select(DB::raw("booking_date, program_id, ".
                                    " start_time, ".
                                    "  end_time "

                                    ))
                ->addSelect(['program'=> Program::select('mnemonic')->whereColumn('id', 'program_id')])
                ->addSelect(['virtual_room_id'=> VirtualMeetingLink::select('virtual_room_id')
                                            ->whereColumn('id', 'virtual_meeting_link_id')])
                ->addSelect(['vr_mnemonic'=> VirtualRoom::select('mnemonic')
                ->whereColumn('id', 'virtual_room_id')])

                ;

             //   dd($datesToSearch);
        foreach ($datesToSearch as $date)
        {
            $startTime = (new Carbon($date["freeStartTime"]))->timezone('America/Guayaquil')->isoFormat('HH:mm');
            $endTime = (new Carbon($date["freeEndTime"]))->timezone('America/Guayaquil')->isoFormat('HH:mm');;


            $query->orWhereRaw("((start_time>=addtime('". $startTime ."','-01:00:00') " .
                                    " AND end_time<=addtime('". $endTime . "','01:00:00') )" .
                                "  OR (start_time>=addtime('". $startTime ."','-01:00:00') " .
                                    " AND start_time<=addtime('". $endTime . "','01:00:00') )" .
                                "  OR (end_time>=addtime('". $startTime ."','-01:00:00') " .
                                    " AND end_time<=addtime('". $endTime . "','01:00:00') )" .
                                "  OR (start_time<=addtime('". $startTime ."','-01:00:00') " .
                                    " AND end_time>=addtime('". $endTime . "','01:00:00') ))" .
                                " AND DATE_FORMAT(booking_date, '%Y-%m-%d')='" . $date["freeDate"]. "'"


                                )
                ;

        }

        return response()->json($query->get());

    }


}
