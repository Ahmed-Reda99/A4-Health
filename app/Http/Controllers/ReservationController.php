<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Feedback;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\FeedbackNptification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Notifications\ReservationAdded;
use App\Notifications\ReservationCancelled;

class ReservationController extends Controller
{
    
    public function index($id)
    {
        $id = auth()->guard('patient')->user()->id;
        $data = Reservation::where('patient_id', '=', $id)->get();
        $reservations = collect($data)->map(function($oneReservation)
        {
            return
            [
                'id' =>$oneReservation->id,
                'appointment_id' =>$oneReservation->appointment_id,
                'patient_time' => $oneReservation->patient_time,
                'doctorName' => $oneReservation->appointment->doctor->user->fname." ".$oneReservation->appointment->doctor->user->lname,
                'date' => $oneReservation->appointment->date,
                'status' => $oneReservation->status,
                'payment_status' => $oneReservation->payment_status
            ];
        });
        return $reservations;
    }    
    public function store($id,Request $request)
    {
        //possible validation
        try
        {
            DB::beginTransaction();

            $this->validate($request, [
                'appointment_id' => 'required',
                'patient_time' => Rule::unique('reservations')->where(function ($query){
                    global $request;
                    return $query->where('appointment_id','=',$request->appointment_id);
                })
            ]);

            $id = auth()->guard('patient')->user()->id;
            $newReservation = new Reservation;
            $newReservation->appointment_id = $request->appointment_id;
            $newReservation->patient_time = $request->patient_time;
            $newReservation->patient_id = $id;
            $newReservation->status = "pending";
            $newReservation->payment_status = "pending";
            $newReservation->save();

            DB::commit();

            $newReservation->appointment->doctor->notify(new ReservationAdded($newReservation));
            
        }catch(ValidationException $ex)
        {
            DB::rollBack();
            return $ex->errors();
        }
        return "done";
        
    }

    
    public function show($id,$appointment_id,$time)
    {
        
        $reservation = Reservation::where('patient_id','=',$id)
        ->where('appointment_id', '=', $appointment_id)
        ->where('patient_time', '=', $time)->first();
        $reservationData = [
            'id' =>$reservation->id,
            'patientName'=>$reservation->patient->user->fname,
            'doctorName'=>$reservation->appointment->doctor->user->fname,
            'date'=>$reservation->appointment->date,
            'time'=>$reservation->patient_time
        ];
        // return view("patients.show",["user"=>$patient]);
        return $reservationData;
    }
    
    public function update(Request $request, $id,$appointment_id,$time)
    {
        //
        try
        {
            $this->validate($request, [
                'patient_time' => Rule::unique('reservations')->where(function ($query){
                    global $request;
                    return $query->where('appointment_id','=',$request->appointment_id);
                })
            ]);
            Reservation::where('patient_id','=',$id)
            ->where('appointment_id', '=', $appointment_id)
            ->where('patient_time', '=', $time)
            ->update(['patient_time' => $request->patient_time]);
        }catch(ValidationException $ex)
        {
            return $ex->errors();
        }
        return "updated";
    }

    
    public function destroy($patient_id,$id)
    {
        //edit re_id
        $patient_id = auth()->guard('patient')->user()->id;

        $reservation = Reservation::find($id);
        if($reservation == "")
        {
            return "Not reservation ID";
        }
        if($reservation->patient->user->id != $patient_id)
        {
            return "Not yours reservation";
        }
        $reservation->appointment->doctor->notify(new ReservationCancelled($reservation));
        Reservation::destroy($id);
        return "deleted";
    }
    public function indexPatients($id,$appointment_id)
    {
        //possible validation
        $id = auth()->guard('doctor')->user()->id;
        $appointment = Appointment::find($appointment_id);
        if($appointment->doctor->user->id != $id)
        {
            return "Not Owned Appointment";
        }
        $reservations = Reservation::where('appointment_id',$appointment_id)->get();
        $data = collect($reservations)->map(function($oneReservation){
            return
            [
                'patientName' => $oneReservation->patient->user->fname." ".$oneReservation->patient->user->lname,
                'patientTime' => $oneReservation->patient_time,
                'status'=> $oneReservation->status,
                'payment_status' => $oneReservation->payment_status
            ];
        });
        return $data;
    }
    public function changeStatus($id,$reservation_id)
    {
        $doctor_id = auth()->guard('doctor')->user()->id;
        $reservation = Reservation::find($id);
        if($reservation->appointment->doctor->user->id != $doctor_id)
        {
            return "Not Owned Appointment";
        }
        $reservation->status = 'completed';
        $reservation->save();
        $patient = Patient::find($reservation->patient);
        Notification::send($patient,new FeedbackNptification($reservation->appointment));
        return "done";

    }
}
