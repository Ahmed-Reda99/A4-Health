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
use App\Http\Controllers\OfferController;
use App\Http\Controllers\SpecializationController;

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

// any error gets here !
Route::get("unauthenticated",function(){
    return ['message'=>"unauthenticated"];
})->name('unauthenticated');



/////////////////////////////////////////// Patient routes ///////////////////////////////////////////
Route::group(['middleware' => 'auth:patient'], function () {
    Route::get('/patients/{id}',[PatientController::class,"show"])->whereNumber('id'); //
    Route::put('/patients/{id}',[PatientController::class,"update"])->whereNumber('id');//
    Route::post('/patients/{id}/reservations',[ReservationController::class,"store"])->whereNumber('id');//
    Route::get('/patients/{id}/reservations',[ReservationController::class,"index"])->whereNumber('id');//
    Route::delete('/patients/{id}/reservations/{reservation_id}',[ReservationController::class,"destroy"])->whereNumber('id','reservation_id');//
    Route::post('/patients/{id}/feedback',[FeedbackController::class,"store"])->whereNumber('id');//
    
    //payment Route

    Route::get('/patients/{id}/reservations/{reservation_id}/pay',[PaymentController::class,"payFees"])->whereNumber('id');
    Route::get('/patients/{id}/reservations/{reservation_id}/pay/now/{sessionID}',[PaymentController::class,"executePaymentgetway"])->whereNumber('id');
    
});

/////////////////////////////////////////// Doctor routes ///////////////////////////////////////////
Route::group(['middleware' => 'auth:doctor'], function () {
    Route::get("/doctors/{id}",[DoctorController::class,"show"])->whereNumber('id');//
    Route::put("/doctors/{id}",[DoctorController::class,"update"])->whereNumber('id');//
    Route::post("/doctors/{doctor_id}/appointments", [AppointmentController::class, "store"])->whereNumber('doctor_id');//
    Route::get("/doctors/{doctor_id}/appointments", [AppointmentController::class, "index"])->whereNumber('doctor_id');//
    Route::put("/doctors/{doctor_id}/appointments/{appointment_id}", [AppointmentController::class, "update"])->whereNumber('doctor_id','appointment_id');//
    Route::delete("/doctors/{doctor_id}/appointments/{appointment_id}", [AppointmentController::class, "destroy"])->whereNumber('doctor_id','appointment_id');//
    Route::get("/doctors/{doctor_id}/reservations", [ReservationController::class, "indexPatients"])->whereNumber('doctor_id','appointment_id');//
    Route::put("/doctors/{doctor_id}/reservations/{reservation_id}", [ReservationController::class, "changeStatus"])->whereNumber('doctor_id','reservation_id');//
    Route::get("/doctors/{doctor_id}/feedback", [FeedbackController::class, "index"])->whereNumber('doctor_id');//
    Route::get('/doctors/{id}/offers',[OfferController::class,"index"]);
    Route::post('/doctors/{id}/offers',[OfferController::class,"store"]);
    Route::put('/doctors/{id}/offers/{offer_id}',[OfferController::class,"update"]);
    Route::delete('/doctors/{id}/offers/{offer_id}',[OfferController::class,"destroy"]);

});

/////////////////////////////////////////// User routes ///////////////////////////////////////////


Route::group(['middleware' => 'auth:patient'], function () {
    Route::get("/patients/notifications", [UserController::class, "displayAllNotifications"]);
    Route::put('/patients/{id}/password',[UserController::class,"updatePassword"])->whereNumber('id');
});

Route::group(['middleware' => 'auth:doctor'], function () {
    Route::get("/doctors/notifications", [UserController::class, "displayAllNotifications"]);
    Route::put('/doctors/{id}/password',[UserController::class,"updatePassword"])->whereNumber('id');
});

Route::post('/patients',[PatientController::class,"store"]); //
Route::get("/doctors", [DoctorController::class, "index"]); //
Route::get("/doctors/{id}/info", [DoctorController::class, "show"]); //
Route::get("/doctors/{doctor_id}/reviews", [FeedbackController::class, "index"]);//
Route::get("/specializations", [SpecializationController::class, "index"]);



Route::get('/patients/{id}/reservations/{reservation_id}/pay/done',[PaymentController::class,"changeStatus"]);
Route::get('/patients/reservations/pay/Erorr',[PaymentController::class,"Erorr"]);

// Routes for mobile verification in sign up

Route::get("/", function(){
    return view("verify");
});
Route::post("/verify",[UserController::class, "verifyPhone"]);
Route::delete("/deleteUnverifiedUser/{id}", [UserController::class, "destroy"])->where('id', '[0-9]+'); 




///////////////////////////////////////////////////////////////////////////////////////////////

// route for patient and doctor login

Route::post('/login', function (Request $request) { 

   // Catch Excation is required
    // $request->validate([
    //     'username' => 'required',
    //     'password' => 'required',
    //     'device_name' => 'required'
    // ]);
    $user = User::where('username', $request->username)->first();
 
    if (! $user || ! Hash::check($request->password, $user->password)) {
        
        return ["error"=>"username or password is incorrect"];
    }
    $userType = $user->doctor? "doctor" : "patient";
    
    if($userType == "patient")
    {
        return ["token"=>$user->patient->createToken("HP")->plainTextToken,
                "type"=>$userType,
                "id"=>$user->id];
    }else
    {
        return ["token"=>$user->doctor->createToken("HP")->plainTextToken,
                "type"=>$userType,
                "id"=>$user->id];
    }
    
    
});
Route::get("login",function(){
    return "you must login";
})->name('login');

// route for logging out

Route::middleware('auth:sanctum')->get('/logout', function(){

    auth()->user()->currentAccessToken()->delete();
    return "Logged Out Successfully";

});
Route::get('/token', function () {
    return csrf_token();
});

/////////////////////////////////////////// Admin routes ///////////////////////////////////////////

Route::post("/dashboard/login", function(Request $request){
    $admin = Admin::where("username", $request->username)->first();


    if (! $admin || ! Hash::check($request->password, $admin->password)) {
        
        return ["error"=>"username or password is incorrect"];
    }

    return ["token"=>$admin->createToken("HP")->plainTextToken];

});


// mutliple routes that use the same controller and just mention the method name

Route::controller(AdminController::class)->middleware('auth:admin')->group(function () {
    
    Route::put("/dashboard/updatePassword", "updateMyPassword");

    Route::get("/dashboard/doctors", "listAllDoctors");
    Route::post("/dashboard/doctors", "addNewDoctor");
    Route::delete("/dashboard/doctors/{doc_id}", "deleteDoctor");

    Route::get("/dashboard/specializations", "listAllSpecializations");
    Route::post("/dashboard/specializations", "addNewSpecialization");
    Route::get("/dashboard/specializations/{specialization_id}", "showSpecialization");
    Route::put("/dashboard/specializations/{specialization_id}", "updateSpecialization");
    Route::delete("/dashboard/specializations/{specialization_id}", "deleteSpecialization");

    Route::get("/dashboard/patients", "listAllPatients");
    Route::delete("/dashboard/patients/{patient_id}", "deletePatient");

    Route::get("/dashboard/reviews", "listAllReviews");
    Route::delete("/dashboard/reviews/{review_id}", "deleteReview");

});


// email routes
Route::get("redirect/facebook", [SocialController::class, "redirect"]);

Route::get("callback/facebook", [SocialController::class, "callback"]);


// Arwa's routes
Route::post("/arwa",[AdminController::class, "insertAdminInsteadOfTheOneArwaForgotHisPassowrd"]);