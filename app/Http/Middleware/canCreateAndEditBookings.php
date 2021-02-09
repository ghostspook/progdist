<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class canCreateAndEditBookings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::user()->authorizedAccount->can_create_and_edit_bookings) {
            if (! $request->expectsJson()) {
                return redirect()->route('unauthorizedaccount');
            }

            abort(403);
        }

        return $next($request);
    }
}
