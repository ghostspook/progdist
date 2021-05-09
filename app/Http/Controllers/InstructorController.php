<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\InstructorArea;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class InstructorController extends Controller
{
    //
    public function index()
    {
        return view('instructors.index');
    }

    public function getInstructors (Request $request)
    {
        $input = json_decode($request["params"],true);

       // dd ($request["params"]);
        $query = Instructor::with('instructorAreas.area');
        // dd($query);

        foreach ($input["columnFilters"] as $field=>$value){
            if ($value <> ""){
              // $query->having($field, 'like', '%' . $value . '%');
              $query->where($this->translateField($field), 'like', '%' . $value . '%');

            }
        }

        if ( $input["sort"][0]["field"]<> "" ){
          $query->orderby($input["sort"][0]["field"],$input["sort"][0]["type"]);
        }


        $currentPage = $input['page'];
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });

        return response()->json($query->paginate($input['rowsPerPage']));;
    }

    public function storeInstructor(Request $request)
    {
        $newInstructor = $request->newInstructor;
        if (!$newInstructor["name"])
        {
            return response()->json([
                "status" => "error",
                "errorCode" => 1,
                "errorMessage" => "Missing instructor name"
            ])->setStatusCode(400);
        }

        if (!$newInstructor["mnemonic"])
        {
            return response()->json([
                "status" => "error",
                "errorCode" => 2,
                "errorMessage" => "Missing instructor mnemonic"
            ])->setStatusCode(400);
        }

        $newObj = Instructor::create(['name' => $newInstructor["name"] ,
                                    'mnemonic'=>$newInstructor["mnemonic"],

        ]);


        foreach ( $newInstructor["areas"] as $area ){
            InstructorArea::create(['instructor_id' => $newObj->id,
                                    'area_id' => $area["id"],
                                    ]);
                }

        return response()->json([
            "status" => "success",
            "instructor_id" => $newObj->id,
        ]);

    }


    private function translateField($field){
        switch ($field) {
            case 'name':
                return 'instructors.name';
            case 'mnemonic':
                return 'instructors.mnemonic';
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
            case 'virtual_room':
                return 'virtual_rooms.mnemonic';

        }
    }

}
