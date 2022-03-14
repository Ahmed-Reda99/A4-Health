<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Notifications\PatientNotification;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use App\Models\Reservation;
use Illuminate\Support\Facades\Notification;

class AppointmentController extends Controller
{
    
    public function index($doctor_id)
    {
        $doctor_id = auth()->guard('doctor')->user()->id;
        $doctor = Doctor::find($doctor_id);
        return $doctor->appointments;
    }

    
    public function create($doctor_id)
    {
        // return view("doctors.appointments.create", ["doc_id"=>$doctor_id]);
        return date("Y-m-d");
    }

    
    public function store(Request $request, $doctor_id)
    {
                
        try {

            $doctor_id = auth()->guard('doctor')->user()->id;

            $request->validate([
                "date"=>"bail|required|date_format:Y-m-d",
                "start_time"=>Rule::unique('appointments')->where(function ($query){
                global $request;
                return $query->where('doctor_id', $request->doctor_id)->where('date', $request->date);
            })]);

            $request->validate([
                "start_time"=>"bail|required|date_format:H:i",
                "patient_limit"=>"bail|numeric",
                "examination_time"=>"bail|required|numeric",
                
            ]);
            $appoint = new Appointment;
            $appoint->start_time = $request->start_time;
            $appoint->date = $request->date;
            $appoint->patient_limit = $request->patient_limit;
            $appoint->examination_time = $request->examination_time;
            $appoint->doctor_id = $doctor_id;
            $appoint->save();

        } catch (ValidationException $e) {
            return $e->errors();
        }

        return 
        [
            'response' => "inserted"
        ];
    }

    
    // public function show($doctor_id, $appointment_id)
    // {
    //     //check if appointment belongs to this doctor id
    //     $appoint = Appointment::find($appointment_id);
    //     return ["date"=>$appoint->date,
    //             "start_time"=>$appoint->start_time,
    //             "patients"=>$appoint->patient_limit,
    //             "examination_time"=>$appoint->examination_time,
    //             "doctor"=>$appoint->doctor->user->fname ." ". $appoint->doctor->user->lname
    //         ];
    // }

    
    public function edit($doctor_id, $appointment_id)
    {
        //return view("doctors.appointments.edit", ["appointment_id"=>$appointment_id]);
    }


    public function update(Request $request, $doctor_id, $appointment_id)
    {
        
        
        try {
            $doctor_id = auth()->guard('doctor')->user()->id;
            $request->validate([
                "start_time"=>Rule::unique('appointments')->where(function ($query){
                    global $request;
                    return $query->where('doctor_id', $request->doctor_id)->where('date', $request->date);
                    
                })
            ]);
            
            $request->validate([
                "start_time"=>"bail|required|date_format:H:i",
                "date"=>"bail|required|date_format:Y-m-d",
                "patient_limit"=>"bail|numeric",
                "examination_time"=>"bail|required|numeric"
                
            ]);
            
            $appoint = Appointment::find($appointment_id);
            $appoint->start_time = $request->start_time;
            $appoint->date = $request->date;
            $appoint->patient_limit = $request->patient_limit;
            $appoint->examination_time = $request->examination_time;
            $appoint->save();
            

        } catch (ValidationException $e) {
            return $e->errors();
        }

        return "updated";

    }


    public function destroy($doctor_id, $appointment_id)
    {
        
        $doctor_id = auth()->guard('doctor')->user()->id;
        $reservations = Reservation::where('appointment_id',$appointment_id)->get();
        foreach($reservations as $oneReservation)
        {
            Notification::send($oneReservation->patient,new PatientNotification($oneReservation->appointment));
        }
        Appointment::destroy($appointment_id);

        return "deleted";
    }
}
