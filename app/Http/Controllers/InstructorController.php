<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\InstructorArea;
use App\Models\Area;
use App\Models\InstructorConstraint;
use Carbon\Carbon;
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

    public function edit($id)
    {
        $i = Instructor::find($id);

        $instructorAreas = InstructorArea::select('areas.id as area_id',
                                        'areas.name as area_name',
                                        'areas.mnemonic as area_mnemonic',
                                        )
                                ->where('instructor_id', $id)
                                ->leftjoin('areas', 'area_id', '=', 'areas.id')
                                ->orderBy('areas.mnemonic')
                                ->get()
                                ;

        $areas = Area::orderBy('mnemonic')->get();

        $constraints = InstructorConstraint::where('instructor_id', $id)->orderBy('from','DESC')->get();

        if (!$i)
        {
            abort(404);
        }

        return view('instructors.edit', [ 'i' => $i,
                                        'areas' => $areas,
                                        'instructor_areas' => $instructorAreas,
                                        'constraints' => $constraints,
                                        ]);
    }



    public function update($id, Request $request)
    {
        $i = Instructor::find($id);
        $input = $request->all();

        $request->validate([
            'name' => 'required',
            'mnemonic' => 'required',

        ]);


        //Search if instructor with new name or mnemonic is already in DB
        $query = Instructor::where(function ($q) use($request) {
                                            $q->where('name', '=', $request["name"])
                                                ->orWhere('mnemonic', '=', $request["mnemonic"]);
                                        }
                                    )
                                    ->where('id','<>', $id)
                                    ;


        if ($query->count()>0){
            return response()->json([
            "status" => "error",
            "errorCode" => 3,
            "errorMessage" => "The instructor name or mnemonic already exists"
            ])->setStatusCode(400);
        }



        if (array_key_exists( "area" , $input )){
            InstructorArea::create(['instructor_id' => $id ,
                                    'area_id'=>$input["area"],
                                ]);

        }

        $i->name = $input['name'];
        $i->mnemonic = $input['mnemonic'];

        $i->save();

        return redirect()->route('instructors.index');
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

    public function storeInstructorArea(Request $request)
    {
        $input = $request->all();
        $instructorId =$input["id"];

        // dd($input);
        //Search if instructor has already this area
        $query = InstructorArea::where('instructor_id','=',$input["id"])
                            ->Where('area_id', '=',$input["area_id"] );


        if ($query->first()){
            return redirect()->back()->withErrors(['area_id' => 'El instructor ya pertenece a esta área']);
        }

        $newObj = InstructorArea::create(['instructor_id' => $input["id"] ,
                                    'area_id'=>$input["area_id"],

        ]);




        return redirect()->route('instructors.edit',['id'=> $instructorId]);

    }

    public function destroy($id)
    {
        $i = Instructor::find($id);

        $i-> delete();
    }


    public function destroyInstructorConstraint($id , Request $request)
    {

        $input = $request->all();
        $constraint = InstructorConstraint::find($id);

        $constraint-> delete();

        return redirect()->route('instructors.edit',['id'=> $input["instructor_id"] ]);


    }

    public function storeInstructorConstraint($id, Request $request)
    {
        // $request->validate([
        //   //  'from' => 'required|date|before_or_equal:to',
        //  //   'to' => 'required|date',
        // ]);

        $i = Instructor::find($id);
        $input = $request->all();

        InstructorConstraint::create(['instructor_id' => $id ,
                                'from'=>$input["from"],
                                'to'=>$input["to"],
                                ]);

        //return redirect()->route('instructors.edit',['id'=> $id]);
        return redirect()->back();

        // ->withErrors(['from' => 'Revise las fechas de inicio y fin',
        //                                         'to' => 'Revise las fechas de inicio y fin',
        //                                       ]);



    }

    public function getInstructorConstraints(Request $request)
    {
        $input = $request -> all();
                                                ;
        $query = InstructorConstraint::with('instructor')->where(function ($q) use($input) {
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




        ;

       // dd($query->get());
        return response()->json($query->get());
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
