<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SocialController;
use App\Models\Admin;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;

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

/////////////////////////////////////////// General Routes ///////////////////////////////////////////

// this is the msg to return if the user is unauthenticated

Route::get("unauthenticated",function(){
    return ['message'=>"unauthenticated"];
})->name('unauthenticated');


// route for logging out

Route::middleware('auth:sanctum')->get('/logout', function(){
    
    auth()->user()->currentAccessToken()->delete();
    return "Logged Out Successfully";
    
});

Route::get("/notifications", [UserController::class, "displayAllNotifications"])->middleware('auth:user');


/////////////////////////////////////////////////////////////////////////////////////////
//Search Page
Route::get("/doctors", [DoctorController::class, "index"]);
Route::post('/patients/{id}/reservations',[ReservationController::class,"store"])->middleware('auth:patient')->whereNumber('id');
Route::get("/doctors/{id}/info", [DoctorController::class, "show"])->whereNumber('id');
Route::get("/doctors/{doctor_id}/reviews", [FeedbackController::class, "index"]);

//Patient Profile
Route::get('/patients/{id}',[PatientController::class,"show"])->middleware('auth:patient')->whereNumber('id');
Route::patch('/patients/{id}',[PatientController::class,"update"])->middleware('auth:patient')->whereNumber('id');
Route::patch('/patients/{id}/password',[PatientController::class,"updatePassword"])->middleware('auth:patient')->whereNumber('id');
Route::get('/patients/{id}/reservations',[ReservationController::class,"index"])->middleware('auth:patient')->whereNumber('id');
Route::delete('/patients/{id}/reservations/{appointment_id}/{time}',[ReservationController::class,"destroy"]);



Route::get('/patients/{id}/notifications',[PatientController::class,"showNotification"])->middleware('auth:patient')->whereNumber('id');


//Doctor Profile
Route::get("/doctors/{id}", [DoctorController::class, "show"])->middleware('auth:doctor')->whereNumber('id');
Route::put("/doctors/{id}", [DoctorController::class, "update"])->middleware('auth:doctor')->whereNumber('id');

Route::post("/doctors/{doctor_id}/appointments", [AppointmentController::class, "store"])->middleware('auth:doctor')->whereNumber('id');
Route::get("/doctors/{doctor_id}/appointments", [AppointmentController::class, "index"])->middleware('auth:doctor')->whereNumber('id');
Route::put("/doctors/{doctor_id}/appointments/{appointment_id}", [AppointmentController::class, "update"])->middleware('auth:doctor')->whereNumber('id');
Route::delete("/doctors/{doctor_id}/appointments/{appointment_id}", [AppointmentController::class, "destroy"])->middleware('auth:doctor')->whereNumber('id');

Route::get("/doctors/{doctor_id}/reservations/{appointment_id}", [ReservationController::class, "indexPatients"])->middleware('auth:doctor')->whereNumber('id');

Route::patch("/doctors/{doctor_id}/reservations/{appointment_id}/{patient_id}/{time}", [ReservationController::class, "changeStatus"])->middleware('auth:doctor')->whereNumber('id');

Route::get("/doctors/{doctor_id}/feedback", [FeedbackController::class, "index"])->middleware('auth:doctor')->whereNumber('id');

//payment Route

Route::get('/patients/{id}/reservations/{reservation_id}/pay',[PaymentController::class,"payFees"]);
Route::get('/patients/reservations/pay/done',[PaymentController::class,"changeStatus"]);
Route::get("login",function(){
    return "you must login";
})->name('login');

///////////////////////////////////////////////////////////////////////////////////////////////

// route for patient and doctor login

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

/////////////////////////////////////////// Admin routes ///////////////////////////////////////////

Route::post("/dashboard/login", function(Request $request){
    $admin = Admin::where("username", $request->username)->first();


    if (! $admin || ! Hash::check($request->password, $admin->password)) {
        
        return ["error"=>"username or password is incorrect"];
    }

    return ["token"=>$admin->createToken($request->device_name)->plainTextToken];

});


// mutliple routes that use the same controller and just mention the method name

Route::controller(AdminController::class)->middleware('auth:admin')->group(function () {
    
    Route::put("/dashboard/updatePassword", "updateMyPassword");

    Route::get("/dashboard/doctors", "listAllDoctors");
    Route::post("/dashboard/doctors", "addNewDoctor");
    Route::delete("/dashboard/doctors/{doc_id}", "deleteDoctor");

    Route::get("/dashboard/specializations", "listAllSpecializations");
    Route::post("/dashboard/specializations", "addNewSpecialization");
    Route::put("/dashboard/specializations/{specialization_id}", "updateSpecialization");
    Route::delete("/dashboard/specializations/{specialization_id}", "deleteSpecialization");

    Route::get("/dashboard/patients", "listAllPatients");
    Route::delete("/dashboard/patients/{patient_id}", "deletePatient");

    Route::get("/dashboard/reviews", "listAllReviews");
    Route::delete("/dashboard/reviews/{review_id}", "deleteReview");

});


//////////////////////////////////////////////////////////////

Route::get("redirect/facebook", [SocialController::class, "redirect"]);

Route::get("callback/facebook", [SocialController::class, "callback"]);

//////////////////////////////////////////////////////////////

Route::post("/verify",[UserController::class, "verifyPhone"]);
Route::delete("/deleteUnverifiedUser/{id}", [UserController::class, "destroy"])->where('id', '[0-9]+');