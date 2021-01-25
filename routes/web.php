<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookingController;
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

Route::get('/bookings', [BookingController::class,'index'])->middleware(['auth:web']);
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
Route::post('/bookings', [BookingController::class, 'storeBooking'])->middleware(['auth']);
