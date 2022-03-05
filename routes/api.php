<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\DoctorController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("login",function(){
    return "you must login";
})->name('login');


Route::get("/doctors", [DoctorController::class, "index"])->middleware('auth:patient');


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
    
    if($userType == "patient") return ["token"=>$user->patient->createToken($request->device_name)->plainTextToken];
    return ["token"=>$user->doctor->createToken($request->device_name)->plainTextToken];
    
});