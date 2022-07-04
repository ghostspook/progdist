<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramVirtualMeetingLink;
use App\Models\VirtualRoom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProgramController extends Controller
{
    public function index()
    {
        return view('programs.index');
    }

    public function create()
    {
        return view('programs.create');
    }

    public function edit($id)
    {
        $p = Program::find($id);
        $rooms = VirtualRoom::all();

        if (!$p)
        {
            abort(404);
        }

        return view('programs.edit', [ 'p' => $p, 'rooms' => $rooms]);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $request->validate([
            'name' => 'required',
            'mnemonic' => 'required',
            'short_name' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);


        Program::create([
            'name' => $input['name'],
            'mnemonic' => $input['mnemonic'],
            'short_name' => $input['short_name'],
            'start_date' => $input['start_date'],
            'class' => $input['class'],
            'is_visible' => $input['is_visible'],
        ]);

        return redirect()->route('programs.index');
    }

    public function update($id, Request $request)
    {
        $p = Program::find($id);
        $input = $request->all();

        $request->validate([
            'name' => 'required',
            'mnemonic' => 'required',
            'short_name' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $p->name = $input['name'];
        $p->mnemonic = $input['mnemonic'];
        $p->short_name = $input['short_name'];
        $p->start_date = $input['start_date'];
        $p->end_date = $input['end_date'];
        $p->class = $input['class'];
        $p->is_visible = $input['is_visible'];
        $p->save();

        return redirect()->route('programs.index');
    }

    public function destroy($id)
    {
        $p = Program::find($id);
        $p->delete();

        return redirect()->route('programs.index');
    }

    public function dataTable(Request $request)
    {
        $programs = Program::all();

        return Datatables::of($programs)
            ->editColumn('start_date', function($data){ $formatedDate = Carbon::parse($data->start_date)->locale('es_ES')->isoFormat('YYYY-MM-DD'); return $formatedDate; })
            ->editColumn('end_date', function($data){ $formatedDate = Carbon::parse($data->end_date)->locale('es_ES')->isoFormat('YYYY-MM-DD'); return $formatedDate; })
            ->addColumn('action', function ($p) {
                if( (Auth::user()->authorizedAccount->can_create_and_edit_bookings)){
                    return
                        '<form method="POST" action="'.route('programs.destroy', ['id' => $p->id]).'">'.
                            '<input type="hidden" name="_method" value="delete" />'.
                            '<input type="hidden" name="_token" value="'.csrf_token().'" />'.
                            '<a class="btn btn-primary btn-sm" href="'.route('programs.edit', ['id' => $p->id]).'"><i class="fa fa-edit"></i></a>'.
                            '<button type="submit" class="btn btn-danger ml-2 btn-sm"><i class="fa fa-trash"></i></button>'.
                        '</form>';
                }
                else{
                    return '';
                }
            })
            ->make(true);
    }

    public function getProgramVirtualMeetingLinks($id)
    {
      //  $pvmls = ProgramVirtualMeetingLink::where('program_id',$id)->with('virtualMeetingLink','program')->get();
     // dd($id);
      $pvmls = ProgramVirtualMeetingLink::where('program_id',$id)->with('virtualMeetingLink','program')->get();
       $links= [];
      foreach($pvmls as $vml){
            $isDefaultLink = false;
           // dd($vml->program->default_virtual_meeting_link_id);
            $vml->program->default_virtual_meeting_link_id == $vml->virtualMeetingLink["id"] ?
                                                            $isDefaultLink = true :
                                                            $isDefaultLink = false;
            $links [] = ["virtual_meeting_link_id" => $vml->virtualMeetingLink["id"],
                         "virtual_meeting_link" => $vml->virtualMeetingLink["link"],
                         "password" => $vml->virtualMeetingLink["password"],
                         "is_default_link" => $isDefaultLink,
                         "virtual_room_id" => $vml->virtualMeetingLink->virtualRoom["id"],
                         "virtual_room_name" => $vml->virtualMeetingLink->virtualRoom["name"],
                         "waiting_room" => $vml->virtualMeetingLink["waiting_room"],

            ] ;


      }


     return response()->json($links);


    }
}
