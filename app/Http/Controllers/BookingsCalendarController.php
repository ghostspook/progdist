<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingsCalendarController extends Controller
{
    public function index()
    {
        return view('bookingscalendar.index');
    }

    public function getBookingsJson(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $input = $request->all();


        return response()->json(
            Booking::with([
                    'area',
                    'instructor',
                    'program',
                    'physicalRoom',
                    'virtualMeetingLink.virtualRoom',
                    'bookingSupportPersons.supportPerson',
                ])
                ->where('booking_date', '>=', $input['from'])
                ->where('booking_date', '<=', $input['to'])
                ->get());
    }
}
