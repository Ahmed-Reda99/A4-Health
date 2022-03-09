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

