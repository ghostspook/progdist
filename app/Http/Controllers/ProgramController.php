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

    public function dataTable(Request $request)
    {
        $programs = Program::all();

        return Datatables::of($programs)->make(true);
    }
}
