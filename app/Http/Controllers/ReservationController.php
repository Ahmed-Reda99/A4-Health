<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Notifications\ReservationAdded;
use App\Notifications\ReservationCancelled;

class ReservationController extends Controller
{
    
    public function index($id)
    {
        //
        $data = Reservation::where('patient_id', '=', $id)->get();
        // return view("reservations.index",["data"=>$data]);
        return $data;
    }

    
    public function create($id)
    {
        //
        // return view("reservations.store",["id"=>$id]);
    }

    
    public function store($id,Request $request)
    {
        //possible validation
        try
        {
            DB::beginTransaction();

            $this->validate($request, [
                'patient_time' => Rule::unique('reservations')->where(function ($query){
                    global $request;
                    return $query->where('appointment_id','=',$request->appointment_id);
                })
            ]);

            $newReservation = new Reservation;
            $newReservation->appointment_id = $request->appointment_id;
            $newReservation->patient_time = $request->patient_time;
            $newReservation->patient_id = $id;
            $newReservation->status = "pending";
            $newReservation->save();

            DB::commit();

            $newReservation->appointment->doctor->notify(new ReservationAdded($newReservation));
            
        }catch(ValidationException $ex)
        {
            DB::rollBack();
            return $ex->errors();
        }
        // return redirect("/patients/{$id}/reservations");
        return "inserted";
        
    }

    
    public function show($id,$appointment_id,$time)
    {
        
        $reservation = Reservation::where('patient_id','=',$id)
        ->where('appointment_id', '=', $appointment_id)
        ->where('patient_time', '=', $time)->first();
        $reservationData = [
            'patientName'=>$reservation->patient->user->fname,
            'doctorName'=>$reservation->appointment->doctor->user->fname,
            'date'=>$reservation->appointment->date,
            'time'=>$reservation->patient_time
        ];
        // return view("patients.show",["user"=>$patient]);
        return $reservationData;
    }

    
    public function edit(Reservation $reservation)
    {
        //
    }

    
    public function update(Request $request, $id,$appointment_id,$time)
    {
        //

        // $reservation = Reservation::where('patient_id','=',$id)
        // ->where('appointment_id', '=', $appointment_id)
        // ->where('patient_time', '=', $time)->first();
        // $reservation->patient_time = $request->patient_time;
        // $reservation->save();
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

    
    public function destroy($id,$appointment_id,$time)
    {
        $reservation = Reservation::where('patient_id','=',$id)
        ->where('appointment_id', '=', $appointment_id)
        ->where('patient_time', '=', $time)->first();

        $reservation->appointment->doctor->notify(new ReservationCancelled($reservation));
        Reservation::where('patient_id','=',$id)
        ->where('appointment_id', '=', $appointment_id)
        ->where('patient_time', '=', $time)->delete();

        return "deleted";
    }
}
