<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingsCalendarController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\VirtualMeetingLinkController;
use App\Http\Middleware\canCreateAndEditBookings;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/bookings', [BookingController::class,'index'])->middleware(['auth:web'])->name('bookings.index');
Route::get('/bookings/datatable', [BookingController::class,'getBookings'])->middleware(['auth'])->name('bookings.index.datatable');
Route::delete('/bookings/{id}', [BookingController::class,'destroy'])->middleware(['auth:web', canCreateAndEditBookings::class])->name('bookings.destroy');
Route::get('/bookings/{id}/edit', [BookingController::class,'edit'])->middleware(['auth:web'])->name('bookings.edit');

// Route::get('/bookings/create', [BookingController::class,'create']);
// Route::post('/bookings', [BookingController::class,'store'])->name('bookings.create');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/login', [LoginController::class,'redirectToProvider'])->name('login');

Route::get('/auth/callback', [LoginController::class,'handleProviderCallback']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/unauthorizedaccount', [LoginController::class, 'displayUnauthorizedAccountMsg'])->name('unauthorizedaccount');

Route::get('api/user', [LoginController::class, 'getUserInfo'])->middleware(['auth']);
Route::get('api/areas', [BookingController::class, 'getAreas'])->middleware(['auth']);
Route::get('api/instructorareas', [BookingController::class, 'getInstructorAreas'])->middleware(['auth']);
Route::get('api/instructors', [BookingController::class, 'getInstructors'])->middleware(['auth']);

Route::get('api/programs', [BookingController::class, 'getPrograms'])->middleware(['auth']);
Route::get('api/physicalrooms', [BookingController::class, 'getPhysicalRooms'])->middleware(['auth']);
Route::get('api/virtualrooms', [BookingController::class, 'getVirtualRooms'])->middleware(['auth']);
Route::get('api/virtualrooms/{id}', [VirtualMeetingLinkController::class, 'getVirtualRoomByLinkId'])->middleware(['auth']);
Route::post('api/virtualmeetinglinks', [VirtualMeetingLinkController::class,'addLinkForVirtualMeeting'])->middleware(['auth:web']);

Route::get('api/supportpeople', [BookingController::class, 'getSupportPeople'])->middleware(['auth']);
Route::get('api/bookings/datatable', [BookingController::class, 'getBookings'])->middleware(['auth', canCreateAndEditBookings::class]);
Route::get('api/bookings/instructorconflicts', [BookingController::class, 'getInstructorConflicts'])->middleware(['auth', canCreateAndEditBookings::class]);


Route::delete('/api/bookings/{id}', [BookingController::class,'destroy'])->middleware(['auth:web',canCreateAndEditBookings::class]);
Route::get('api/bookings/all', [BookingController::class, 'getAllBookingsJson'])->middleware(['auth', canCreateAndEditBookings::class]);
Route::post('api/bookings', [BookingController::class, 'storeBooking'])->middleware(['auth', canCreateAndEditBookings::class]);
Route::get('/api/bookings/{id}', [BookingController::class,'getBooking'])->middleware(['auth:web']);
Route::get('api/bookings', [BookingsCalendarController::class, 'getBookingsJson'])->middleware(['auth']);
Route::put('/api/bookings/{id}', [BookingController::class,'updateBooking'])->middleware(['auth', canCreateAndEditBookings::class]);

//Bookings Calendar
Route::get('/calendar', [BookingsCalendarController::class,'index'])->middleware(['auth:web'])->name('bookingscalendar.index');

Route::get('/programs', [ProgramController::class,'index'])->middleware(['auth:web'])->name('programs.index');
Route::get('/programs/datatable', [ProgramController::class,'dataTable'])->middleware(['auth'])->name('programs.index.datatable');
Route::get('/programs/create', [ProgramController::class,'create'])->middleware(['auth:web'])->name('programs.create');
Route::get('/programs/{id}/edit', [ProgramController::class,'edit'])->middleware(['auth:web'])->name('programs.edit');
Route::post('/programs/store', [ProgramController::class,'store'])->middleware(['auth:web'])->name('programs.store');
Route::put('/programs/{id}', [ProgramController::class,'update'])->middleware(['auth:web'])->name('programs.update');
Route::delete('/programs/{id}', [ProgramController::class,'destroy'])->middleware(['auth:web'])->name('programs.destroy');
Route::get('api/programvirtualmeetinglinks/{id}',  [ProgramController::class,'getProgramVirtualMeetingLinks'])->middleware(['auth']);

Route::get('/conflicts', 'App\Http\Controllers\ConflictController@index')->middleware(['auth:web'])->name('conflicts.index');



Route::post('/virtual_links/store', [VirtualMeetingLinkController::class,'store'])->middleware(['auth:web'])->name('virtual_links.store');
Route::delete('/virtual_links/{id}', [VirtualMeetingLinkController::class,'destroy'])->middleware(['auth:web'])->name('virtual_links.destroy');
Route::get('/virtual_links/setdefault/{id}', [VirtualMeetingLinkController::class,'setDefaultLink'])->middleware(['auth:web'])->name('virtual_links.setdefault');

