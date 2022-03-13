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
        $doctorsInformation = Doctor::all();
        $data = collect($doctorsInformation)->map(function($doctor)
        { 
            $ratings = array();
            foreach($doctor->feedbacks as $rating)
            {
               array_push($ratings,$rating->rate);
            }
            $average = array_sum($ratings) / count($ratings);
            return 
            [
                'id' => $doctor->id,
                'fname' => $doctor->user->fname,
                'lname' => $doctor->user->lname,
                'title' => $doctor->title,
                'specialization' => $doctor->specialization->name,
                'fees' => $doctor->fees,
                'rating' => $average,
                'city' => $doctor->city,
                'street' => $doctor->street,
                'gender' => $doctor->user->gender,
                'img_name' => $doctor->img_name,
                'appointment' => $doctor->appointments
            ];
        });    
        return $data;
    }

    
    public function store(Request $request)
    {   
        try {

            DB::beginTransaction();
    
            $user = (new UserController)->store($request);
            
            
            $doctor = new Doctor;
            $doctor->id = $user->id;
            
            
            $request->validate([
                'description'         =>   'bail|string|min:15|max:50',
                // 'img_name'            =>   'bail|image|mimes:jpeg,pmb,png,jpg|max:88453',
                'street'              =>   'bail|string|min:3|max:20',
                'city'                =>   'bail|required|string|min:4|max:15',
                'specialization_id'   =>   'bail|required',
                'fees'                =>   'bail|numeric|min:1',
                'title'               =>   'bail|required|in:"professor", "lecturer", "consultant", "specialist"'
            ]);

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
            return $th;
        } 
    }

    
    public function show($id)
    {
        if(auth()->guard('doctor')->user())
        {
            $id = auth()->guard('doctor')->user()->id;
        }
        $doctor = Doctor::find($id);
        $data = [
            'username'=>$doctor->user->username,
            'fname'=>$doctor->user->fname,
            'lname'=>$doctor->user->lname,
            'gender'=>$doctor->user->gender,
            'description'=>$doctor->description,
            'img_name'=>$doctor->img_name,
            'street'=>$doctor->street,
            'city'=>$doctor->city,
            'specialization'=>$doctor->specialization->name,
            'title' => $doctor->title,
            'fees'=>$doctor->fees,
            'phone'=>$doctor->user->phone,
            'appointment' => $doctor->appointments

        ];
        return $data;
    }    
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $id = auth()->guard('doctor')->user()->id;
            $user = (new UserController)->update($request,$id);
            $request->validate([
                "img_name" => "bail|required",
                "description" => "bail|required",
                "street" => "bail|required",
                "city" => "bail|required",
                "fees" => "bail|required"
            ]);
            $doctor = $user->doctor;
            $doctor->description = $request->description;
            $doctor->img_name = $request->img_name;
            $doctor->street = $request->street;
            $doctor->city = $request->city;
            $doctor->fees = $request->fees;
            $doctor->save();
            DB::commit();
            return "updated";
        } catch (ValidationException $e) {
            DB::rollBack();
            return $e->errors();
        } catch(Throwable $th){
            DB::rollBack();
            return $th;
        }
        
    }

    
    public function destroy($id)
    {
        return (new UserController)->destroy($id);
    }
}
