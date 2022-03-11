<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }


    public function callback()
    {
        $user = Socialite::with('facebook')->user();
        return response()->json($user);
    }

}
