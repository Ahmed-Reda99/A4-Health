<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\User_phone;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class DoctorController extends Controller
{
    
    public function index()
    { 
        // return auth()->user()->user->username;
        // auth('doctor')
        // auth()->guard('doctor')->id();
        // auth()->guard('doctor')->user()->username;
        // return Doctor::paginate(1);
        return Doctor::all();
    }

    
    public function create()
    {
        //return view(doctors.create);
    }

    
    public function store(Request $request)
    {   
        try {

            DB::beginTransaction();
    
            $user = (new UserController)->store($request);
            
            
            $doctor = new Doctor;
            $doctor->id = $user->id;
            
            
            
            $request->validate([
                "phone"               =>   'digits:11',
                'description'         =>   'bail|string|min:15|max:50',
                // 'img_name'            =>   'bail|image|mimes:jpeg,pmb,png,jpg|max:88453',
                'street'              =>   'bail|string|min:3|max:20',
                'city'                =>   'bail|required|string|min:4|max:15',
                'specialization_id'   =>   'bail|required',
                'fees'                =>   'bail|numeric|min:1',
                'title'               =>   'bail|required|in:"professor", "lecturer", "consultant", "specialist"'
            ]);

            (new UserPhoneController)->store($request->phone,$user->id);
            
            $doctor->description = $request->description;
            $doctor->img_name = $request->img_name;
            $doctor->street = $request->street;
            $doctor->city = $request->city;
            $doctor->specialization_id = $request->specialization_id ;
            $doctor->fees = $request->fees;
            $doctor->save();
    
            DB::commit();
            return "inserted";

        } catch(ValidationException $ex) {
            DB::rollBack();
            return $ex->errors();
        } catch(Throwable $th){
            DB::rollBack();
            return $user;
        } 
    }

    
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

    
    public function edit($id)
    {
        //return view(doctors.edit);
    }

    
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

    
    public function destroy($id)
    {
        return (new UserController)->destroy($id);
    }
}
