<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OfferController extends Controller
{
    
    public function index($id)
    {
        //
        $offers = Offer::where("doctor_id","=",$id)->get();
        return $offers;
    }

    
    public function create()
    {
        //
    }

    
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

    
    public function show(Offer $offer)
    {
        //unknown
        
    }

    
    public function edit(Offer $offer)
    {
        //
    }

    
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

    
    public function destroy($id,$offer_id)
    {
        //
        Offer::find($offer_id)->delete();
        return "deleted";
    }
}
