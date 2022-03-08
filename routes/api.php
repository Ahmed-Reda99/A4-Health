<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Models\Admin;


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


///////////////////////////////////////////////////////////////////////////////////////////////

// route for patient and doctor login

Route::post('/login', function (Request $request) { 
   
    $user = User::where('username', $request->username)->first();
 
    if (! $user || ! Hash::check($request->password, $user->password)) {
        
        return ["error"=>"username or password is incorrect"];
    }

    
    $userType = $user->doctor? "doctor" : "patient";
    
    if($userType == "patient") return ["token"=>$user->patient->createToken($request->device_name)->plainTextToken];
    return ["token"=>$user->doctor->createToken($request->device_name)->plainTextToken];
    
});

// Route::get("/doctors", [DoctorController::class, "index"])->middleware('auth:patient');
// Route::get("/doctors/{doctor_id}/appointments", [AppointmentController::class, "index"])->middleware('auth:doctor');

/////////////////////////////////////////// Admin routes ///////////////////////////////////////////

Route::post("/dashboard/login", function(Request $request){
    $admin = Admin::where("username", $request->username)->first();


    if (! $admin || ! Hash::check($request->password, $admin->password)) {
        
        return ["error"=>"username or password is incorrect"];
    }

    return ["token"=>$admin->createToken($request->device_name)->plainTextToken];

});


Route::get("/dashboard/doctors",[AdminController::class, "listAllDoctors"])->middleware('auth:admin');
Route::post("/dashboard/doctors/store",[AdminController::class, "addNewDoctor"])->middleware('auth:admin');
Route::delete("/dashboard/doctors/{doc_id}",[AdminController::class, "deleteDoctor"])->middleware('auth:admin');

Route::get("/dashboard/specializations",[AdminController::class, "listAllSpecializations"])->middleware('auth:admin');
Route::post("/dashboard/specializations/store",[AdminController::class, "addNewSpecialization"])->middleware('auth:admin');
Route::delete("/dashboard/specializations/{specialization_id}",[AdminController::class, "deleteSpecialization"])->middleware('auth:admin');

Route::get("/dashboard/patients",[AdminController::class, "listAllPatients"])->middleware('auth:admin');
Route::delete("/dashboard/patients/{patient_id}",[AdminController::class, "deletePatient"])->middleware('auth:admin');

Route::get("/dashboard/reviews",[AdminController::class, "listAllReviews"])->middleware('auth:admin');
Route::delete("/dashboard/reviews/{review_id}",[AdminController::class, "deleteReview"])->middleware('auth:admin');

Route::put("/dashboard/updatePassword", [AdminController::class, "updatePassword"])->middleware('auth:admin');

