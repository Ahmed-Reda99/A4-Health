<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
        //validation
        $user = new User;
        $user->username = $request->username;
        $user->password = $request->password;
        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->gender = $request->gender;
        $user->save();
        return redirect("/users");
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
        //
        User::find($id)->delete();
        return redirect('/users');
    }
}
