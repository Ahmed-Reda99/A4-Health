<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Middleware\DoctorMiddleware;
use App\Http\Middleware\PatientMiddleware;
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

// Route::middleware(['auth:sanctum',DoctorMiddleware::class])->get('/user', function (Request $request) {

//     // return $request->user()->tokenCan('role:patient');
//     // return $request->user();
//     return auth()->user();
// });

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get("/doctors", [DoctorController::class, "index"])->middleware(DoctorMiddleware::class);
    Route::get('/patients',[PatientController::class,"index"])->middleware(PatientMiddleware::class);
    //add more Routes here
    
});

Route::get("login",function(){
    return "you must login first";
})->name('login');


// Route::get("/doctors", [DoctorController::class, "index"])->middleware(['auth:sanctum','type.doctor']);

Route::post('/login', function (Request $request) { 
    $request->validate([
        'username' => 'required',
        'password' => 'required'
    ]);
    $user = User::where('username', $request->username)->first();
 
    if (! $user || ! Hash::check($request->password, $user->password)) {
        
        return ["error"=>"username or password is incorrect"];
    }
    // if(!$user->doctor)
    // {
    //     return ["error"=>"Authntication Error"];
    // }
    $userType = $user->doctor? "role:doctor" : "role:patient";
    // return $token = $request->bearerToken();
    
    
    return ["token"=>$user->createToken("mobile",[$userType])->plainTextToken];
});