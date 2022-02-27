<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Models\Doctor;
use App\Http\Controllers\AppointmentController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::resource("/doctors", DoctorController::class);

// Route::get("/doctors", [DoctorController::class, "index"]);
// Route::get("/doctors/create", [DoctorController::class, "create"]);
// Route::post("/doctors", [DoctorController::class, "store"]);
// Route::get("/doctors/{id}", [DoctorController::class, "show"]);
// Route::get("/doctors/{id}/edit", [DoctorController::class, "edit"]);
// Route::put("/doctors/{id}", [DoctorController::class, "update"]);
// Route::delete("/doctors/{id}", [DoctorController::class, "destroy"]);

//////////////////////////////////////////////////////////////////////////////////////

Route::get("/doctors/{doctor_id}/appointments", [AppointmentController::class, "index"]);
Route::get("/doctors/{doctor_id}/appointments/create", [AppointmentController::class, "create"]);
Route::post("/doctors/{doctor_id}/appointments", [AppointmentController::class, "store"]);
Route::get("/doctors/{doctor_id}/appointments/{appointment_id}", [AppointmentController::class, "show"]);
Route::get("/doctors/{doctor_id}/appointments/{appointment_id}/edit", [AppointmentController::class, "edit"]);
Route::put("/doctors/{doctor_id}/appointments/{appointment_id}", [AppointmentController::class, "update"]);
Route::delete("/doctors/{doctor_id}/appointments/{appointment_id}", [AppointmentController::class, "destroy"]);

//////////////////////////////////////////////////////////////////////////////////////

Route::get('/token', function () {
    return csrf_token(); 
});

Route::fallback(function () {
    // return view("404");
    return view("404-2");
});

