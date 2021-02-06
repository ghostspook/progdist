<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramVirtualMeetingLink;
use App\Models\VirtualRoom;
use Illuminate\Http\Request;
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
            'end_date' => $input['end_date'],
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
            ->addColumn('action', function ($p) {
                return
                    '<form method="POST" action="'.route('programs.destroy', ['id' => $p->id]).'">'.
                        '<input type="hidden" name="_method" value="delete" />'.
                        '<input type="hidden" name="_token" value="'.csrf_token().'" />'.
                        '<a class="btn btn-primary" href="'.route('programs.edit', ['id' => $p->id]).'"><i class="fa fa-edit"></i></a>'.
                        '<button type="submit" class="btn btn-danger ml-2"><i class="fa fa-trash"></i></button>'.
                    '</form>';
            })
            ->make(true);
    }

    public function getProgramVirtualMeetingLinks($id)
    {
        $links = ProgramVirtualMeetingLink::where('program_id',$id)->with('virtualMeetingLink','program')->get();

        // $linksList = [];

        // foreach ( $links as $vml) {
        //     $linksList = ["id" => $vml->virtualMeetingLink->id ,
        //              "link" => $vml->virtualMeetingLink->link,
        //             ];

        // }

        return response()->json($links);


    }
}
