<?php

namespace App\Http\Controllers;

use App\Models\SupportPerson;
use App\Models\SupportPersonConstraint;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class SupportPeopleController extends Controller
{
    //
    public function index()
    {
        return view('supportpeople.index');
    }

    public function edit($id)
    {
        $i = SupportPerson::find($id);



        $constraints = SupportPersonConstraint::where('support_person_id', $id)->orderBy('from','DESC')->get();

        if (!$i)
        {
            abort(404);
        }

        return view('supportpeople.edit', [ 'i' => $i,
                                        // 'areas' => $areas,
                                        // 'instructor_areas' => $instructorAreas,
                                        'constraints' => $constraints,
                                        ]);
    }

    public function update($id, Request $request)
    {
        $i = SupportPerson::find($id);
        $input = $request->all();

        $request->validate([
            'name' => 'required',
            'mnemonic' => 'required',

        ]);


          //Search if supportPerson is already in DB
            $query = SupportPerson::where(function ($q) use($request) {
                                                $q->where('name', '=', $request["name"])
                                                ->orWhere('mnemonic', '=', $request["mnemonic"]);
                                            }
                                        )->where('id','<>', $id)
                        ;



            if ($query->count()>0){
            return response()->json([
            "status" => "error",
            "errorCode" => 3,
            "errorMessage" => "The support person name or mnemonic already exists"
            ])->setStatusCode(400);
            }



        $i->name = $input['name'];
        $i->mnemonic = $input['mnemonic'];





        $i->save();

        return redirect()->route('supportpeople.index');
    }

    public function getSupportPeople(Request $request)
    {
        $input = json_decode($request["params"],true);

        $query= SupportPerson::select('id' ,'name', 'mnemonic');

        foreach ($input["columnFilters"] as $field=>$value){
            if ($value <> "" && $field){
              $query->where($field, 'like', '%' . $value . '%');
            }
        }


        if ( $input["sort"][0]["field"]<> "" ){
            dd($input["sort"][0]["field"]);
            $query->orderby($input["sort"][0]["field"],$input["sort"][0]["type"]);
        }


        $currentPage = $input['page'];
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });

        return response()->json($query->paginate($input['rowsPerPage']));;
    }

    public function storeSupportPerson(Request $request)
    {
        $newSupportPerson = $request->newSupportPerson;
        if (!$newSupportPerson["name"])
        {
            return response()->json([
                "status" => "error",
                "errorCode" => 1,
                "errorMessage" => "Missing Support Person name"
            ])->setStatusCode(400);
        }

        if (!$newSupportPerson["mnemonic"])
        {
            return response()->json([
                "status" => "error",
                "errorCode" => 2,
                "errorMessage" => "Missing Support Person mnemonic"
            ])->setStatusCode(400);
        }

        //Search if supportPerson is already in DB
        $query = SupportPerson::where('name','=',$newSupportPerson["name"])
                            ->orWhere('mnemonic', '=',$newSupportPerson["mnemonic"] );


        if ($query->first()){
            return response()->json([
                "status" => "error",
                "errorCode" => 3,
                "errorMessage" => "The support person name or mnemonic already exists"
            ])->setStatusCode(400);
        }

        $newObj = SupportPerson::create(['name' => $newSupportPerson["name"] ,
                                    'mnemonic'=>$newSupportPerson["mnemonic"],

        ]);



        return response()->json([
            "status" => "success",
            "support_person_id" => $newObj->id,
        ]);

    }


    public function destroy($id)
    {
        $i = SupportPerson::find($id);

        $i-> delete();
    }

    public function getSupportPeopleConstraints(Request $request)
    {
        $input = $request -> all();
                                                ;
        $query = SupportPersonConstraint::with('supportPerson')->where(function ($q) use($input) {
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

       //dd($query->get());
        return response()->json($query->get());
    }

    public function storeSupportPersonConstraint($id, Request $request)
    {
        // $request->validate([
        //   //  'from' => 'required|date|before_or_equal:to',
        //  //   'to' => 'required|date',
        // ]);

        $i = SupportPerson::find($id);
        $input = $request->all();

        SupportPersonConstraint::create(['support_person_id' => $id ,
                                'from'=>$input["from"],
                                'to'=>$input["to"],
                                ]);


        return redirect()->back();

        // ->withErrors(['from' => 'Revise las fechas de inicio y fin',
        //                                         'to' => 'Revise las fechas de inicio y fin',
        //                                       ]);



    }

    public function destroySupportPersonConstraint($id , Request $request)
    {

        $input = $request->all();
        $constraint = SupportPersonConstraint::find($id);

        $constraint-> delete();

        return redirect()->route('supportpeople.edit',['id'=> $input["support_person_id"] ]);


    }

}
