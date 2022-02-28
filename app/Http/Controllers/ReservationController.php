<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return view("reservations.index",["data"=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        return view("reservations.store",["id"=>$id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id,Request $request)
    {
        //
        $newReservation = new Reservation;
        $newReservation->appointment_id = $request->appointment_id;
        $newReservation->patient_time = $request->patient_time;
        $newReservation->patient_id = $id;
        $newReservation->save();
        // return "Patient name :".$newReservation->patient->user->fname." Doctor Name :".$newReservation->appointment->doctor->user->fname;
        // return $newReservation->appointment->doctor->user->fname;
        return redirect("/patients/{$id}/reservations");
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        //
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
    public function update(Request $request, Reservation $reservation)
    {
        //
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
        return redirect("/patients/{$id}/reservations");
    }
}
