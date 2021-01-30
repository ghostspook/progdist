<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingsCalendarController extends Controller
{
    public function index()
    {
        return view('bookingscalendar.index');
    }
}
