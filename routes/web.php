<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingsCalendarController;
use App\Http\Controllers\BasicBookingsCalendarController;

use App\Http\Controllers\ConflictController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\AreaController;


use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SupportPeopleController;
use App\Http\Controllers\VirtualMeetingLinkController;
use App\Http\Controllers\VirtualRoomController;
use App\Http\Middleware\canCreateAndEditBookings;
use App\Models\VirtualRoom;
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

Route::get('/', [BasicBookingsCalendarController::class,'index'])->middleware(['auth:web']);

Route::get('/bookings', [BookingController::class,'index'])->middleware(['auth:web'])->name('bookings.index');
Route::get('/bookings/express', [BookingController::class,'express'])->middleware(['auth:web'])->name('bookings.express');
Route::get('/bookings/overallprogramming', [BookingController::class, 'overallProgramming'])->middleware(['auth'/*, canCreateAndEditBookings::class*/])->name('bookings.overallprogramming');
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
Route::get('api/bookings/datatable', [BookingController::class, 'getBookings'])->middleware(['auth'/*, canCreateAndEditBookings::class*/]);
Route::get('api/bookings/overallprogramming', [BookingController::class, 'getOverallProgramming'])->middleware(['auth'/*, canCreateAndEditBookings::class*/]);
Route::get('api/bookings/overallprogramming/sessiondetails', [BookingController::class, 'getOverallProgrammingSessionDetails'])->middleware(['auth'/*, canCreateAndEditBookings::class*/]);
Route::get('api/bookings/instructorconflicts', [ConflictController::class, 'getInstructorConflicts'])->middleware(['auth'/*, canCreateAndEditBookings::class*/]);




Route::get('/api/bookings/week', [BasicBookingsCalendarController::class,'getBookingsByWeek'])->middleware(['auth:web']);
Route::get('/api/bookings/virtualroomconflicts/week', [ConflictController::class,'getVirtualRoomConflictsByWeek'])->middleware(['auth:web']);


Route::delete('/api/bookings/{id}', [BookingController::class,'destroy'])->middleware(['auth:web',canCreateAndEditBookings::class]);
Route::get('api/bookings/all', [BookingController::class, 'getAllBookingsJson'])->middleware(['auth', canCreateAndEditBookings::class]);
Route::post('api/bookings', [BookingController::class, 'storeBooking'])->middleware(['auth', canCreateAndEditBookings::class]);
Route::post('api/bookings/bunch/create',[BookingController::class,'storeBookingsBunch'])->middleware(['auth', canCreateAndEditBookings::class]);
Route::post('api/bookings/bunch/split',[BookingController::class,'splitBookingsBunch'])->middleware(['auth', canCreateAndEditBookings::class]);
Route::get('/api/bookings/{id}', [BookingController::class,'getBooking'])->middleware(['auth:web']);
Route::get('api/bookings', [BookingsCalendarController::class, 'getBookingsJson'])->middleware(['auth']);
Route::post('api/bookings/bunch',[BookingController::class,'getBookingsBunch'])->middleware(['auth']);

Route::put('/api/bookings/multiupdate', [BookingController::class,'updateMultiBooking'])->middleware(['auth', canCreateAndEditBookings::class]);
Route::put('/api/bookings/{id}', [BookingController::class,'updateBooking'])->middleware(['auth', canCreateAndEditBookings::class]);



Route::get('/api/bookings/week', [BasicBookingsCalendarController::class,'getBookingsByWeek'])->middleware(['auth:web']);
Route::post('/api/bookings/clone/bunch', [BookingController::class,'getBookingsBunchForCloning'])->middleware(['auth:web', canCreateAndEditBookings::class]);




// Route::get('/api/bookings/supportpeople/{id}', [BookingController::class,'getSupportPeopleForBooking'])->middleware(['auth:web']);



//Bookings Calendar
Route::get('/calendar', [BookingsCalendarController::class,'index'])->middleware(['auth:web'])->name('bookingscalendar.index');

//Basic Bookings Calendar
Route::get('/basicbookingscalendar', [BasicBookingsCalendarController::class,'index'])->middleware(['auth:web'])->name('basicbookingscalendar.index');



Route::get('/programs', [ProgramController::class,'index'])->middleware(['auth:web'])->name('programs.index');
Route::get('/datatable', [ProgramController::class,'dataTable'])->middleware(['auth'])->name('programs.index.datatable');
Route::get('/programs/create', [ProgramController::class,'create'])->middleware(['auth:web'])->name('programs.create');
Route::get('/programs/{id}/edit', [ProgramController::class,'edit'])->middleware(['auth:web'])->name('programs.edit');
Route::post('/programs/store', [ProgramController::class,'store'])->middleware(['auth:web'])->name('programs.store');
Route::put('/programs/{id}', [ProgramController::class,'update'])->middleware(['auth:web'])->name('programs.update');
Route::delete('/programs/{id}', [ProgramController::class,'destroy'])->middleware(['auth:web'])->name('programs.destroy');
Route::get('api/programvirtualmeetinglinks/{id}',  [ProgramController::class,'getProgramVirtualMeetingLinks'])->middleware(['auth']);


Route::get('/conflicts', 'App\Http\Controllers\ConflictController@index')->middleware(['auth:web'])->name('conflicts.index');

Route::get('/conflicts/virtualroom', [ConflictController::class, 'virtualRoomsConflictsIndex'])->middleware(['auth:web'])->name('conflicts.virtualrooms.index');
Route::post('/conflicts/virtualroom', [ConflictController::class, 'virtualRoomsConflictsIndex'])->middleware(['auth'/*, canCreateAndEditBookings::class*/])->name('conflicts.virtualrooms.show');


Route::post('/virtual_links/store', [VirtualMeetingLinkController::class,'store'])->middleware(['auth:web'])->name('virtual_links.store');
Route::delete('/virtual_links/{id}', [VirtualMeetingLinkController::class,'destroy'])->middleware(['auth:web'])->name('virtual_links.destroy');
Route::get('/virtual_links/setdefault/{id}', [VirtualMeetingLinkController::class,'setDefaultLink'])->middleware(['auth:web'])->name('virtual_links.setdefault');
Route::put('/api/programvirtualmeetinglinks/setdefault/{id}', [VirtualMeetingLinkController::class,'api_setDefaultLink'])->middleware(['auth:web']);
Route::get('api/programvirtualmeetinglinks/getdefault/{id}',  [VirtualMeetingLinkController::class,'getDefaultProgramVirtualMeetingLink'])->middleware(['auth']);


Route::get('/instructors', [InstructorController::class,'index'])->middleware(['auth:web'])->name('instructors.index');
Route::get('/instructors/{id}/edit', [InstructorController::class,'edit'])->middleware(['auth:web'])->name('instructors.edit');
Route::put('/instructors/{id}', [InstructorController::class,'update'])->middleware(['auth:web'])->name('instructors.update');

Route::get('/api/instructors/constraints', [InstructorController::class, 'getInstructorConstraints'])->middleware(['auth', /*canCreateAndEditBookings::class*/]);
Route::delete('/api/instructors/{id}', [InstructorController::class,'destroy'])->middleware(['auth:web', canCreateAndEditBookings::class]);
Route::get('/api/instructors/paged', [InstructorController::class,'getInstructors'])->middleware(['auth', /*canCreateAndEditBookings::class*/]);
Route::post('/api/instructors', [InstructorController::class, 'storeInstructor'])->middleware(['auth', canCreateAndEditBookings::class]);
Route::get('/api/instructors/conflicts', [ConflictController::class, 'getInstructorConflictsByDateAndTime'])->middleware(['auth', /*canCreateAndEditBookings::class*/]);

Route::put('/instructors/{id}/constraints', [InstructorController::class, 'storeInstructorConstraint'])->middleware(['auth', canCreateAndEditBookings::class])->name('instructorconstraints.store');

Route::delete('/instructors/constraints/{id}', [InstructorController::class,'destroyInstructorConstraint'])->middleware(['auth:web', canCreateAndEditBookings::class])->name('instructorconstraints.destroy');


Route::post('/instructorareas/store', [InstructorController::class, 'storeInstructorArea'])->middleware(['auth', canCreateAndEditBookings::class])->name('instructorareas.store');




Route::get('/api/areas/paged', [AreaController::class,'getAreas'])->middleware(['auth' /*, canCreateAndEditBookings::class*/]);
Route::post('/api/areas', [AreaController::class, 'storeArea'])->middleware(['auth', canCreateAndEditBookings::class]);



Route::get('/virtualroom', [VirtualRoomController::class,'index'])->middleware(['auth' /*, canCreateAndEditBookings::class*/])->name('virtualrooms.index');;
Route::post('/api/virtualrooms/available', [VirtualRoomController::class,'getAvailableVirtualRooms'])->middleware(['auth' /*, canCreateAndEditBookings::class*/])->name('virtualrooms.available');;

Route::get('/supportpeople', [SupportPeopleController::class,'index'])->middleware(['auth' /*, canCreateAndEditBookings::class*/])->name('supportpeople.index');;
Route::get('/supportpeople/{id}/edit', [SupportPeopleController::class,'edit'])->middleware(['auth:web'])->name('supportpeople.edit');
Route::put('/supportpeople/{id}', [SupportPeopleController::class,'update'])->middleware(['auth:web'])->name('supportpeople.update');


Route::get('/api/supportpeople/paged', [SupportPeopleController::class,'getSupportPeople'])->middleware(['auth', /*canCreateAndEditBookings::class*/]);
Route::delete('/api/supportpeople/{id}', [SupportPeopleController::class,'destroy'])->middleware(['auth:web', canCreateAndEditBookings::class]);
Route::post('/api/supportpeople', [SupportPeopleController::class, 'storeSupportPerson'])->middleware(['auth', canCreateAndEditBookings::class]);

Route::get('/api/supportpeople/constraints', [SupportPeopleController::class, 'getSupportPeopleConstraints'])->middleware(['auth', /*canCreateAndEditBookings::class*/]);

Route::delete('/supportpeople/constraints/{id}', [SupportPeopleController::class,'destroySupportPersonConstraint'])->middleware(['auth:web', canCreateAndEditBookings::class])->name('supportpeopleconstraints.destroy');
Route::put('/supportpeople/{id}/constraints', [SupportPeopleController::class, 'storeSupportPersonConstraint'])->middleware(['auth', canCreateAndEditBookings::class])->name('supportpeopleconstraints.store');
