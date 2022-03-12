<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User_phone;
use App\Models\User;

class PatientController extends Controller
{
    
    public function index(Request $request)
    {
        $patients =  Patient::all();
        $patientsData = array();
        foreach ($patients as $patient) 
        {
            array_push($patientsData,$patient->user);
        }
        return $patientsData;
        
    }

    

        public function store(Request $request)
    {
        
        try {
            $user = (new UserController)->store($request);
            $patien = new Patient;
            $patien->id = $user->id;
            $patien->save();
            //could return sacturm token
            return "inserted";

        } catch (ValidationException $ex) {
            return $ex->errors();
        } catch(Throwable $th){
            return $user;
        }
        
    }

    
    public function show($id)
    {
        //
        $id = auth()->guard('patient')->user()->id;
        $patient = Patient::find($id);
        $patient = [
            'fname'=>$patient->user->fname,
            'lname'=>$patient->user->lname,
            'gender'=>$patient->user->gender,
            'phone'=>$patient->user->phone
        ];
        return $patient;
    }    

    public function update(Request $request, $id)
    {
        try
        {
            $id = auth()->guard('patient')->user()->id;
            (new UserController)->update($request,$id);
        }catch(ValidationException $ex)
        {
            return $ex->errors();
        } catch(Throwable $th){
            return $th;
        }
        return "updated";

    }    
    public function destroy($id)
    {
        return (new UserController)->destroy($id);
    }
    
}
