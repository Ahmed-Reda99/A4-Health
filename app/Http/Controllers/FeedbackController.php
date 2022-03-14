<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Validation\ValidationException;

class FeedbackController extends Controller
{

    
    public function index($doctor_id)
    {
        if(auth()->guard('doctor')->user())
        {
            $doctor_id = auth()->guard('doctor')->user()->id;
        }
        $data = Feedback::where('doctor_id', $doctor_id)->get();
        $Feedbacks = collect($data)->map(function($oneFeedback)
        {
            return
            [
                'patientName' => $oneFeedback->patient->user->fname,
                'rate' => $oneFeedback->rate,
                'message' => $oneFeedback->message
            ];
        });
        return $Feedbacks;
    }
    
    
    public function create($patient_id, $appointment_id, $time)
    {
        // return create view
    }
    
    
    public function store($patient_id, $appointment_id, $time, Request $request)
    {
        
        try {
            
            $request->validate([
                "rate"=>"bail|required|in:1,2,3,4,5"
            ]);
            
            $feedback = new Feedback;
            $feedback->patient_id = $patient_id;
            $feedback->doctor_id = Appointment::find($appointment_id)->doctor->id;
            $feedback->rate = $request->rate;
            $feedback->message = $request->message;
            $feedback->save();
            return 
            [
                'response' => "inserted"
            ];
           
        } catch (ValidationException $e) {
            return
            [
                'errors' => $e->errors()
            ];
        }

    }

    
    public function show(Feedback $feedback)
    {
        //
    }

    
    public function edit(Feedback $feedback)
    {
        //
    }

    
    public function update(Request $request, Feedback $feedback)
    {
        //
    }

    
    public function destroy($feedback_id)
    {
        Feedback::destroy($feedback_id);
        return 
        [
            'response' => "deleted"
        ];
    }
}
