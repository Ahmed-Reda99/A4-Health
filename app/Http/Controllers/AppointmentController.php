<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Carbon;

class AppointmentController extends Controller
{
    
    public function index($doctor_id)
    {
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
        $appoint = new Appointment;
        $appoint->start_time = $request->start_time;
        // $appoint->date = Carbon::now()->toDateString(); //yyyy:mm:dd need to change timezone can be found in config/app.php
        $appoint->date = date("Y-m-d");
        $appoint->patient_limit = $request->patient_limit;
        $appoint->examination_time = $request->examination_time;
        $appoint->doctor_id = $doctor_id;
        $appoint->save();

        return "inserted";

    }

    
    public function show($doctor_id, $appointment_id)
    {
        $appoint = Appointment::find($appointment_id);
        return ["date"=>$appoint->date,
                "start_time"=>$appoint->start_time,
                "patients"=>$appoint->patient_limit,
                "examination_time"=>$appoint->examination_time,
                "doctor"=>$appoint->doctor->user->fname ." ". $appoint->doctor->user->lname
            ];
    }

    
    public function edit($doctor_id, $appointment_id)
    {
        //return view("doctors.appointments.edit", ["appointment_id"=>$appointment_id]);
    }


    public function update(Request $request, $doctor_id, $appointment_id)
    {
        $appoint = Appointment::find($appointment_id);
        $appoint->start_time = $request->start_time;
        $appoint->date = $request->date;
        $appoint->patient_limit = $request->patient_limit;
        $appoint->examination_time = $request->examination_time;
        $appoint->save();

        return "updated";

    }


    public function destroy($doctor_id, $appointment_id)
    {
        Appointment::destroy($appointment_id);

        return "deleted";
    }
}
