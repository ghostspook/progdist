<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BasicBookingsCalendarController extends Controller
{
    //
    public function index()
    {

        return view('basicbookingscalendar.index');

    }
}
