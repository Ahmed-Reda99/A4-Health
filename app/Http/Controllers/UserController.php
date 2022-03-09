<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
                'gender'   => 'required|in:"male","female"'
            ]);
            
            $user = new User;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            $user->gender = $request->gender;
            $user->save();
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
}
