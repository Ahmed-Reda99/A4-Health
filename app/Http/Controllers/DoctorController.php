<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\User_phone;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        // return auth()->user()->user->username;
        // auth('doctor')
        // auth()->guard('doctor')->id();
        // auth()->guard('doctor')->user()->username;
        // return Doctor::paginate(1);
        return Doctor::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //return view(doctors.create);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {

        
        try {
            
            $request->validate([
                "username"=>"required|min:4|unique:users",
                "password"=>"required|min:8",
                'fname' => 'required|min:4',
                'lname' => 'required|min:4',
            ],
            [
                "username.required"=>"hold on ma boi username is required",
                "username.min"=>"username must be more than 5 charachters",
                // "username.unique"=>"username already exists"
            ]);

            

            // instead of repeating the validation in store and update make a func in the user model
            
            DB::beginTransaction();

            $user = new User;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            $user->gender = $request->gender;
            $user->save();

            $userPhone = new User_phone;
            $userPhone->user_id = $user->id;
            $userPhone->phone = $request->phone;
            $userPhone->save();
            
            $doctor = new Doctor;
            $doctor->id = $user->id;
            $doctor->description = $request->description;
            $doctor->img_name = $request->img_name;
            $doctor->street = $request->street;
            $doctor->city = $request->city;
            $doctor->specialization_id = 1 ;
            $doctor->fees = $request->fees;
            $doctor->save();

            DB::commit();

        } catch (ValidationException $e) {
            DB::rollBack();
            return $e->errors();
        }
        
        return "inserted";

    }

    /**
     * Display the specified resource.
     *
     */
    public function show($id)
    {
        $user = User::find($id);
        
        $doctor = [
            'fname'=>$user->fname,
            'lname'=>$user->lname,
            'gender'=>$user->gender,
            'description'=>$user->doctor->description,
            'img_name'=>$user->doctor->img_name,
            'street'=>$user->doctor->street,
            'city'=>$user->doctor->city,
            'specialization'=>$user->doctor->specialization->name,
            'fees'=>$user->doctor->fees,
            'phone'=>$user->phones
        ];
        return $doctor;
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($id)
    {
        //return view(doctors.edit);
    }

    /**;l
     * Update the specified -=+
     *  in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request, $id)
    {
        
        
        try {
            
            $request->validate([
                "username"=>"bail|required|min:5|unique:users",
                "password"=>"bail|required|min:8"
            ],
            [
                "username.required"=>"hold on ma boi username is required",
                "username.min"=>"username must be more than 5 charachters",
                // "username.unique"=>"username already exists"
            ]);
            
            
            DB::beginTransaction();

            
            $user = User::find($id);
    
            $user->password = Hash::make($request->password);
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            $user->gender = $request->gender;
            $user->save();
    
            $doctor = $user->doctor;
    
            $doctor->description = $request->description;
            $doctor->img_name = $request->img_name;
            $doctor->street = $request->street;
            $doctor->city = $request->city;
            $doctor->specialization_id = 1 ;
            $doctor->fees = $request->fees;
            $doctor->save();
            

            DB::commit();

        } catch (ValidationException $e) {
            DB::rollBack();
            return $e->errors();
        }


        return "updated";
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        Doctor::destroy($id);
        User::destroy($id);

        return "deleted";
    }
}
