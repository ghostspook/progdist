<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    //
    public function getAreas (Request $request)
    {
        $input = json_decode($request["params"],true);

        $query= Area::select('id', 'mnemonic', 'name');

        foreach ($input["columnFilters"] as $field=>$value){
            if ($value <> "" ){
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

    public function storeArea(Request $request)
    {
        $newArea = $request->newArea;
        if (!$newArea["name"])
        {
            return response()->json([
                "status" => "error",
                "errorCode" => 1,
                "errorMessage" => "Missing area name"
            ])->setStatusCode(400);
        }

        if (!$newArea["mnemonic"])
        {
            return response()->json([
                "status" => "error",
                "errorCode" => 2,
                "errorMessage" => "Missing area mnemonic"
            ])->setStatusCode(400);
        }

        //Search if instructor is already in DB
        $query = Area::where('name','=',$newArea["name"])
                            ->orWhere('mnemonic', '=',$newArea["mnemonic"] );


        if ($query->first()){
            return response()->json([
                "status" => "error",
                "errorCode" => 3,
                "errorMessage" => "The area name or mnemonic already exists"
            ])->setStatusCode(400);
        }

        $newObj = Area::create(['name' => $newArea["name"] ,
                                    'mnemonic'=>$newArea["mnemonic"],

        ]);




        return response()->json([
            "status" => "success",
            "instructor_id" => $newObj->id,
        ]);

    }

    private function translateField($field){
        switch ($field) {
            case 'name':
                return 'areas.name';
            case 'mnemonic':
                return 'areas.mnemonic';


        }
    }

}
