<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Notifications\MobileSmsNotification;
use App\Http\Controllers\PatientController;

class UserController extends Controller
{
    
    public function index()
    {
        //
        $users = User::all();
        return view('users.index',["users"=>$users]);
    }

    
    public function create()
    {
        return view("users.store");
    }

    
    public function store(Request $request)
    {
        
        try
        {
            
            $request->validate([
                'username' => 'required|min:4|unique:users',
                'password' => 'required|min:8',
                'fname'    => 'required|min:4',
                'lname'    => 'required|min:4',
                'gender'   => 'required|in:"Male","Female"',
                "phone"    => 'digits:11'
            ]);
            

            
            $user = new User;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            $user->gender = $request->gender;
            $user->save();
            (new UserPhoneController)->store('2'.$request->phone,$user->id);
            return $user;
            
        }catch(ValidationException $ex)
        {
            return $ex->errors();
        }

    }

    
    public function show($id)
    {
        //

        $user = User::find($id);
        return view("users.show",["user"=>$user]);
    }

    
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        User::destroy($id);
        return "deleted";
    }

    public function displayAllNotifications()
    {
        return auth()->user()->notifications;
    }


    public function verifyPhone(Request $request)
    {
        $user = (new PatientController)->store($request);

        if(gettype($user) == 'array') return $user;

        $code = $this->generateRandomString();
        $user->notify(new MobileSmsNotification($code));
        return ["code"=>$code,
                "id"=>$user->id];
    }


    private function generateRandomString($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


}
