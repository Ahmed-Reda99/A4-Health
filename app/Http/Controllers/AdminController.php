<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Doctor;
use App\Http\Controllers\DoctorController;
use App\Models\Patient;
use App\Models\Feedback;

class AdminController extends Controller
{
    // Function to update the admin password

    public function updateMyPassword(Request $request)
    {
        if(!Hash::check($request->oldpass, auth()->user()->password)) return ["message"=>"old password is wrong"];
        
        $admin = Admin::where("username", auth()->user()->username)->first();
        $admin->password = Hash::make($request->newpass);
        $admin->save();
        return ["message"=>"password updated successfully."];

    }

    //////////////////// functions to manipulate doctors data //////////////////////////////////

    public function listAllDoctors()
    {
        $doctors = Doctor::all();
        $data = collect($doctors)->map(function($doctor)
        { 
            
            return 
            [
                'id'               =>   $doctor->id,
                'fname'            =>   $doctor->user->fname,
                'lname'            =>   $doctor->user->lname,
                'gender'           =>   $doctor->user->gender,
                'title'            =>   $doctor->title,
                'specialization'   =>   $doctor->specialization->name,
                'city'             =>   $doctor->city,
                'street'           =>   $doctor->street,
            ];
        });    
        return $data;
    }

    
    public function addNewDoctor(Request $request)
    {
        return (new DoctorController)->store($request);
    }

    
    public function deleteDoctor($doc_id)
    {
        return (new DoctorController)->destroy($doc_id);
    }

    //////////////////// functions to manipulate specializations data //////////////////////////////////

    public function listAllSpecializations()
    {
        return (new SpecializationController)->index();
    }

    
    public function addNewSpecialization(Request $request)
    {
        return (new SpecializationController)->store($request);
    }


    public function updateSpecialization(Request $request, $specialization_id)
    {
        return (new SpecializationController)->update($request, $specialization_id);
    }
    
    public function deleteSpecialization($specialization_id)
    {
        return (new SpecializationController)->destroy($specialization_id);
    }

    //////////////////// functions to manipulate patients data //////////////////////////////////

    public function listAllPatients()
    {
        $patients = Patient::all();
        $data = collect($patients)->map(function($patient)
        { 
            
            return 
            [
                'id'       =>   $patient->id,
                'fname'    =>   $patient->user->fname,
                'lname'    =>   $patient->user->lname,
                'gender'   =>   $patient->user->gender,
                'phones'   =>   $patient->user->phones
            ];
        });    
        return $data;
    }

    public function deletePatient($patient_id)
    {
        return (new PatientController)->destroy($patient_id);
    }

    //////////////////// functions to manipulate reviews data //////////////////////////////////

    public function listAllReviews()
    {
        $allReviews = Feedback::all();
        $data = collect($allReviews)->map(function($review)
        { 
            $doctor = Doctor::find($review->doctor_id);
            $patient = Patient::find($review->patient_id);
            
            return 
            [
                'id'       =>  $review->id,
                'rate'     =>  $review->rate,
                'message'  =>  $review->message,
                'doctor'   =>  $doctor->user->fname." ".$doctor->user->lname,
                'patient'  =>  $patient->user->fname." ".$patient->user->lname
                
            ];
        });    
        return $data;
    }

    public function deleteReview($review_id)
    {
        return (new FeedbackController)->destroy($review_id);
    }


}
