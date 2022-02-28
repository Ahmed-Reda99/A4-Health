<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use App\Models\User;
use App\Models\User_phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // apply resource
        $patients =  Patient::all();
        $patientsData = array();
        foreach ($patients as $patient) 
        {
            array_push($patientsData,$patient->user);
        }
        return $patientsData;
        // return view('patients.index',["users"=>$patients]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        //return view("patients.store");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try
        {
            $this->validate($request, [
                'username' => 'required|unique:users|min:4',
                'password' => 'required|min:8',
                'fname' => 'required|min:4',
                'lname' => 'required|min:4',
            ]);
            $user = new User;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            $user->gender = $request->gender;
            $user->save();
            $user_phone = new User_phone;
            $user_phone->user_id = $user->id;
            $user_phone->phone = $request->phone;
            $user_phone->save();
            $patien = new Patient;
            $patien->id = $user->id;
            $patien->save();
        }catch(ValidationException $ex)
        {
            return $ex->errors();
        }
        
        return "inserted";
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $patient = Patient::find($id);
        $patient = [
            'fname'=>$patient->user->fname,
            'lname'=>$patient->user->lname,
            'gender'=>$patient->user->gender,
            'phone'=>$patient->user->phones
        ];
        // return view("patients.show",["user"=>$patient]);
        return $patient;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        //
        //return view(patients.edit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = User::find($id);

        $user->password = Hash::make($request->password);
        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->save();
        return "updated";

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Patient::find($id)->delete();
        User::find($id)->delete();
        // return redirect('/patients');
        return "deleted";
        
    }
}
