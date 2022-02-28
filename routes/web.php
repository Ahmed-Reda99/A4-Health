<?php

use App\Http\Controllers\OfferController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
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