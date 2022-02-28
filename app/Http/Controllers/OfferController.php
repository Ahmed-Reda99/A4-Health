<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        $offers = Offer::where("doctor_id","=",$id)->get();
        return $offers;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        
        try
        {
            $this->validate($request, [
                'name' => Rule::unique('offers')->where(function ($query){
                    global $request;
                    return $query->where('doctor_id','=',$request->id );
                })
            ]);
            $newOffer = new Offer;
            $newOffer->name = $request->name;
            $newOffer->doctor_id = $id;
            $newOffer->discount = $request->discount;
            $newOffer->save();
        }catch(ValidationException $ex)
        {
            return $ex->errors();
        }
        return "inserted";
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        //unknown
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function edit(Offer $offer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id,$offer_id)
    {
        //
        try
        {
            $this->validate($request, [
                'name' => Rule::unique('offers')->where(function ($query){
                    global $request;
                    return $query->where('doctor_id','=',$request->id );
                })
            ]);
            $newOffer = Offer::find($offer_id);
            $newOffer->name = $request->name;
            $newOffer->doctor_id = $id;
            $newOffer->discount = $request->discount;
            $newOffer->save();
        }catch(ValidationException $ex)
        {
            return $ex->errors();
        }
        return "updated";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$offer_id)
    {
        //
        Offer::find($offer_id)->delete();
        return "deleted";
    }
}
