<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class PatientController extends Controller
{
    
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

    
    public function create()
    {
        //

        //return view("patients.store");
    }

        public function store(Request $request)
    {
        
        DB::beginTransaction();

        $user = (new UserController)->store($request);
        
        
        
        try {
            
            $patien = new Patient;
            $patien->id = $user->id;
            $patien->save();

            $request->validate([
                "phone"  =>  "digits:11"
            ]);
            (new UserPhoneController)->store($request->phone,$user->id);

            DB::commit();
            return "inserted";

        } catch (ValidationException $ex) {
            DB::rollBack();
            return $ex->errors();
        } catch(Throwable $th){
            DB::rollBack();
            return $user;
        }
        
    }

    
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

    
    public function edit(Patient $patient)
    {
        //
        //return view(patients.edit);
    }

    
    // public function update(Request $request, $id)
    // {
    //     //
    //     try
    //     {
    //         $this->validate($request, [
    //             'password' => 'required|min:8',
    //             'fname' => 'required|min:4',
    //             'lname' => 'required|min:4'
    //         ]);
    //         $user = User::find($id);
    //         $user->password = Hash::make($request->password);
    //         $user->fname = $request->fname;
    //         $user->lname = $request->lname;
    //         $user->save();
    //     }catch(ValidationException $ex)
    //     {
    //         return $ex->errors();
    //     }
    //     return "updated";

    // }

    
    public function destroy($id)
    {
        return (new UserController)->destroy($id);
    }
}
