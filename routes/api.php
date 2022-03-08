<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Search Page
Route::get("/doctors", [DoctorController::class, "index"]);
Route::post('/patients/reservations/{id}',[ReservationController::class,"store"])->middleware('auth:patient')->whereNumber('id');
Route::get("/doctors/{id}", [DoctorController::class, "show"])->whereNumber('id');
Route::get("/doctors/{doctor_id}/reviews", [FeedbackController::class, "index"]);

//Patient Profile
Route::get('/patients/{id}',[PatientController::class,"show"])->middleware('auth:patient')->whereNumber('id');
Route::patch('/patients/{id}',[PatientController::class,"update"])->middleware('auth:patient')->whereNumber('id');
Route::patch('/patients/password/{id}',[PatientController::class,"updatePassword"])->middleware('auth:patient')->whereNumber('id');
Route::get('/patients/reservations/{id}',[ReservationController::class,"index"])->middleware('auth:patient')->whereNumber('id');
Route::delete('/patients/reservations/{id}/{appointment_id}/{time}',[ReservationController::class,"destroy"]);


//Doctor Profile
Route::get("/doctors/{id}", [DoctorController::class, "show"])->middleware('auth:doctor')->whereNumber('id');
Route::put("/doctors/{id}", [DoctorController::class, "update"]);

Route::post("/doctors/{doctor_id}/appointments", [AppointmentController::class, "store"]);
Route::get("/doctors/{doctor_id}/appointments", [AppointmentController::class, "index"]);
Route::put("/doctors/{doctor_id}/appointments/{appointment_id}", [AppointmentController::class, "update"]);
Route::delete("/doctors/{doctor_id}/appointments/{appointment_id}", [AppointmentController::class, "destroy"]);

Route::get("/doctors/{doctor_id}/reviews", [FeedbackController::class, "index"]);


Route::get("login",function(){
    return "you must login";
})->name('login');


// Route::get("/doctors", [DoctorController::class, "index"])->middleware('auth:patient');


Route::post('/login', function (Request $request) { 
    // $request->validate([
    //     'email' => 'required|email',
    //     'password' => 'required',
    //     'device_name' => 'required',
    // ]);
    $user = User::where('username', $request->username)->first();
 
    if (! $user || ! Hash::check($request->password, $user->password)) {
        
        return ["error"=>"username or password is incorrect"];
    }
    $userType = $user->doctor? "doctor" : "patient";
    
    if($userType == "patient")
    {
        return ["token"=>$user->patient->createToken($request->device_name)->plainTextToken];
    }else
    {
        return ["token"=>$user->doctor->createToken($request->device_name)->plainTextToken];
    }
    
    
});