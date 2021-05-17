<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VirtualRoomController extends Controller
{
    //

    public function index()
    {
        return view('virtualrooms.index');
    }
}
