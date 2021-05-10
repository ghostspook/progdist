<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\InstructorArea;
use App\Models\Area;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class InstructorController extends Controller
{
    //
    protected $searched_area_mnemonic;

    public function index()
    {
        return view('instructors.index');
    }

    public function getInstructors (Request $request)
    {
        $input = json_decode($request["params"],true);

        $query= Instructor::with('instructorAreas.area');

        foreach ($input["columnFilters"] as $field=>$value){
            if ($value <> "" && $field <> "instructor_areas"){
              $query->where($this->translateField($field), 'like', '%' . $value . '%');
            }
            if ( $value <> "" && $field == "instructor_areas" ){ // if user is looking for Instructor by Area Mnemonic
                $this->searched_area_mnemonic = $value;
                $query->whereHas('instructorAreas', function (Builder $q) {
                    $q->whereHas('area', function(Builder $q ){
                        return $q->where('areas.mnemonic','like', '%' . $this->searched_area_mnemonic . '%');
                    });
              });

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

        //Search if instructor is already in DB
        $query = Instructor::where('name','=',$newInstructor["name"])
                            ->orWhere('mnemonic', '=',$newInstructor["mnemonic"] );


        if ($query->first()){
            return response()->json([
                "status" => "error",
                "errorCode" => 3,
                "errorMessage" => "The instructor name or mnemonic already exists"
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


        }
    }

}
