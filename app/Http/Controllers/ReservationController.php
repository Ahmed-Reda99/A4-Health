<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        $data = Reservation::where('patient_id', '=', $id)->get();
        // return view("reservations.index",["data"=>$data]);
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        // return view("reservations.store",["id"=>$id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id,Request $request)
    {
        //possible validation
        try
        {
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
            $newReservation->save();
        }catch(ValidationException $ex)
        {
            return $ex->errors();
        }
        // return redirect("/patients/{$id}/reservations");
        return "inserted";
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$appointment_id,$time)
    {
        //
        Reservation::where('patient_id','=',$id)
        ->where('appointment_id', '=', $appointment_id)
        ->where('patient_time', '=', $time)
        ->delete();
        // return redirect("/patients/{$id}/reservations");
        return "deleted";
    }
}
