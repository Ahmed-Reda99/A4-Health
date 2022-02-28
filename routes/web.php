<?php

use App\Http\Controllers\OfferController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Models\Doctor;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\FeedbackController;

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

Route::get("/doctors/{doctor_id}/reviews", [FeedbackController::class, "index"]);
Route::get("/patients/{patient_id}/reservations/{appointment_id}/{time}/review", [FeedbackController::class, "create"]);
Route::post("/patients/{patient_id}/reservations/{appointment_id}/{time}", [FeedbackController::class, "store"]);
// Route::delete("/doctors/{doctor_id}/reviews", [FeedbackController::class, "destroy"]); we need an id for the feedback


//Saied
Route::get('/users',[UserController::class,"index"]);
Route::get('/users/create',[UserController::class,"create"]);
Route::post('/users',[UserController::class,"store"]);
Route::get('/users/{id}',[UserController::class,"show"]);
Route::get('/users/{id}/edit',[UserController::class,"edit"]);
Route::patch('/users/{id}',[UserController::class,"update"]);
Route::delete('/users/{id}',[UserController::class,"destroy"]);

//
Route::get('/patients',[PatientController::class,"index"]);
// Route::get('/patients/create',[PatientController::class,"create"]);
Route::post('/patients',[PatientController::class,"store"]);
Route::get('/patients/{id}',[PatientController::class,"show"]);
// Route::get('/patients/{id}/edit',[PatientController::class,"edit"]);
Route::patch('/patients/{id}',[PatientController::class,"update"]);
Route::delete('/patients/{id}',[PatientController::class,"destroy"]);

//
// Route::get('/patients/{id}/reservations/create',[ReservationController::class,"create"]);
Route::post('/patients/{id}/reservations',[ReservationController::class,"store"]);
Route::get('/patients/{id}/reservations/{appointment_id}/{time}',[ReservationController::class,"show"]);
// Route::get('/patients/{id}/reservations/{id}/edit',[ReservationController::class,"edit"]);
Route::patch('/patients/{id}/reservations/{appointment_id}/{time}',[ReservationController::class,"update"]);
Route::delete('/patients/{id}/reservations/{appointment_id}/{time}',[ReservationController::class,"destroy"]);
Route::get('/patients/{id}/reservations',[ReservationController::class,"index"]);
Route::get('/doctors/{id}/reservations',[ReservationController::class,"indexDoctor"]);



//
// Route::get('/doctors/{id}/offers/create',[OfferController::class,"create"]);
Route::post('/doctors/{id}/offers',[OfferController::class,"store"]);
Route::get('/doctors/{id}/offers',[OfferController::class,"show"]);
// Route::get('/doctors/{id}/offers/{id}/edit',[OfferController::class,"edit"]);
Route::patch('/doctors/{id}/offers/{offer_id}',[OfferController::class,"update"]);
Route::delete('/doctors/{id}/offers/{offer_id}',[OfferController::class,"destroy"]);
Route::get('/doctors/{id}/offers',[OfferController::class,"index"]);


Route::get('/token', function () {
    return csrf_token();
});

//////////////////////////////////////////////////////////////////////////////////////

Route::fallback(function () {
    // return view("404");
    return view("404-2");
});