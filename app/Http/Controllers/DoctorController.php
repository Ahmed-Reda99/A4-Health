<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Reservation;
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
            $average = 0;
            if(!count($ratings) == 0)
            {
                $average = array_sum($ratings) / count($ratings);
            }
            $notAllowedTime = collect($doctor->appointments)->map(function($oneAppointment)
            {
                $reservations = Reservation::where('appointment_id',$oneAppointment->id)->get();
                $Times = array();
                foreach($reservations as $oneReservation)
                {
                    array_push($Times,$oneReservation->patient_time);
                }
                return 
                [
                    "id" => $oneAppointment->id,
                    "start_time" => $oneAppointment->start_time,
                    "date" => $oneAppointment->date,
                    "patient_limit" => $oneAppointment->patient_limit,
                    "examination_time" => $oneAppointment->examination_time,
                    "doctor_id" => $oneAppointment->doctor_id,
                    'times' => $Times,
                ];
                
            });
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
                'appointment' => $doctor->appointments,
                'notAllowedTime' => $notAllowedTime
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
                'description'         =>   'bail|required|min:15|max:50',
                'img_name'            =>   'bail|image|mimes:jpeg,pmb,png,jpg|max:88453',
                'street'              =>   'bail|required|min:3|max:20',
                'city'                =>   'bail|required|min:4|max:15',
                'specialization_id'   =>   'bail|required',
                'fees'                =>   'bail|required|numeric|min:1',
                'title'               =>   'bail|required|in:"professor", "lecturer", "consultant", "specialist"'
            ]);


            if (request()->hasFile('img_name')) //if user choose file
            {

                $file = request()->file('img_name'); //store  uploaded file to variable $file to 

                $extension = $file->getClientOriginalExtension();
                $filename = 'doctor-image' . '_' . time() . '.' . $extension;
                $file->storeAs('public/assets', $filename); //make folder assets in public/storage/assets and put file

            }

            $doctor->description = $request->description;
            $doctor->img_name = $filename;
            $doctor->street = $request->street;
            $doctor->city = $request->city;
            $doctor->specialization_id = $request->specialization_id ;
            $doctor->fees = $request->fees;
            $doctor->save();
    
            DB::commit();
            return 
            [
                'response' => "inserted"
            ];

        } catch(ValidationException $ex) {
            DB::rollBack();
            return
            [
                'errors' => $ex->errors()
            ];
        } catch(Throwable $th){
            DB::rollBack();
            if(gettype($user) == 'array') return
            [
                'errors' => $user
            ];
            throw $th;
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
            'id' => $doctor->id,
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
            'appointment' => $doctor->appointments,
            'image' => $this->showImage($id)

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
                'description'         =>   'bail|required|min:15|max:50',
                'street'              =>   'bail|required|min:3|max:20',
                'city'                =>   'bail|required|min:4|max:15',
                'fees'                =>   'bail|required|numeric|min:1',
                'title'               =>   'bail|required|in:"professor", "lecturer", "consultant", "specialist"'
            ]);
            $doctor = $user->doctor;
            $doctor->description = $request->description;
            $doctor->street = $request->street;
            $doctor->city = $request->city;
            $doctor->fees = $request->fees;
            $doctor->title = $request->title;
            $doctor->save();
            DB::commit();
            return "updated";
        } catch (ValidationException $e) {
            DB::rollBack();
            return
            [
                'errors' => $e->errors()
            ];
        } catch(Throwable $th){
            DB::rollBack();
            throw $th;
        }
        
    }

    
    public function destroy($id)
    {
        return (new UserController)->destroy($id);
    }


    public function showImage($doctorId)
    {
        $img_name = Doctor::find($doctorId)->img_name;
        $imgsrc= asset('storage/assets/'. $img_name);
        return response()->json($imgsrc);
    }


}
