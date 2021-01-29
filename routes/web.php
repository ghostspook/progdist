<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\VirtualMeetingLinkController;
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
Route::get('/bookings/datatable', [BookingController::class,'dataTable'])->middleware(['auth'])->name('bookings.index.datatable');
// Route::get('/bookings/create', [BookingController::class,'create']);
// Route::post('/bookings', [BookingController::class,'store'])->name('bookings.create');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/login', [LoginController::class,'redirectToProvider'])->name('login');

Route::get('/auth/callback', [LoginController::class,'handleProviderCallback']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('api/areas', [BookingController::class, 'getAreas'])->middleware(['auth']);
Route::get('api/instructorareas', [BookingController::class, 'getInstructorAreas'])->middleware(['auth']);
Route::get('api/programs', [BookingController::class, 'getPrograms'])->middleware(['auth']);
Route::get('api/physicalrooms', [BookingController::class, 'getPhysicalRooms'])->middleware(['auth']);
Route::get('api/virtualrooms', [BookingController::class, 'getVirtualRooms'])->middleware(['auth']);
Route::get('api/supportpeople', [BookingController::class, 'getSupportPeople'])->middleware(['auth']);
Route::post('api/bookings', [BookingController::class, 'storeBooking'])->middleware(['auth']);

Route::get('/programs', [ProgramController::class,'index'])->middleware(['auth:web'])->name('programs.index');
Route::get('/programs/datatable', [ProgramController::class,'dataTable'])->middleware(['auth'])->name('programs.index.datatable');
Route::get('/programs/create', [ProgramController::class,'create'])->middleware(['auth:web'])->name('programs.create');
Route::get('/programs/{id}/edit', [ProgramController::class,'edit'])->middleware(['auth:web'])->name('programs.edit');
Route::post('/programs/store', [ProgramController::class,'store'])->middleware(['auth:web'])->name('programs.store');
Route::put('/programs/{id}', [ProgramController::class,'update'])->middleware(['auth:web'])->name('programs.update');
Route::delete('/programs/{id}', [ProgramController::class,'destroy'])->middleware(['auth:web'])->name('programs.destroy');

Route::post('/virtual_links/store', [VirtualMeetingLinkController::class,'store'])->middleware(['auth:web'])->name('virtual_links.store');
Route::delete('/virtual_links/{id}', [VirtualMeetingLinkController::class,'destroy'])->middleware(['auth:web'])->name('virtual_links.destroy');

