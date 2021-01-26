<?php

namespace App\Http\Controllers;

use App\Models\Program;
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
                        '<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>'.
                    '</form>';
            })
            ->make(true);
    }
}
