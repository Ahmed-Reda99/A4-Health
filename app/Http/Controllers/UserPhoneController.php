<?php

namespace App\Http\Controllers;

use App\Models\User_phone;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserPhoneController extends Controller
{
    
    public function index()
    {
        //
    }

    
    public function create()
    {
        //
    }

    
    public function store($phone, $user_id)
    {
        
        $userPhone = new User_phone;
        $userPhone->user_id = $user_id;
        $userPhone->phone = $phone;
        $userPhone->save();
        return "inserted";
    }

    
    public function show(User_phone $user_phone)
    {
        //
    }

    
    public function edit(User_phone $user_phone)
    {
        //
    }

    
    public function update(Request $request, User_phone $user_phone)
    {
        //
    }

    
    public function destroy(User_phone $user_phone)
    {
        //
    }
}
