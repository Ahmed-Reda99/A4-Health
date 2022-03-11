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
        // apply resource
        $patients =  Patient::all();
        $patientsData = array();
        foreach ($patients as $patient) 
        {
            array_push($patientsData,$patient->user);
        }
        return $patientsData;
        // return $request->user();
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
        $id = auth()->guard('patient')->user()->id;
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

    

    public function update(Request $request, $id)
    {
        try
        {
            DB::beginTransaction();
            $id = auth()->guard('patient')->user()->id;
            $this->validate($request,[
                'fname' => 'required|min:4',
                'lname' => 'required|min:4',
                'phone' => 'required',
            ]);
            $user = User::find($id);
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            foreach($request->phone as $onePhone)
            {
                User_phone::insertOrIgnore(['user_id'=>$id,'phone'=>$onePhone]);
            }
            $user->save();
            DB::commit();
        }catch(ValidationException $ex)
        {
            DB::rollBack();
            return $ex->errors();
        }
        return "updated";

    }
    function updatePassword(Request $request,$id)
    {
        try
        {
            $id = auth()->guard('patient')->user()->id;
            $this->validate($request,[
                'old_password' => 'required',
                'password' => 'required|confirmed',
            ]);
            $user = User::find($id);
            if (! Hash::check($request->old_password, $user->password)) {
        
                return ["error"=>"password is incorrect"];
            }
            $user->password = Hash::make($request->password);
            $user->save();
        }catch(ValidationException $ex)
        {
            return $ex->errors();
        }
        return "updated";
    }

    
    public function destroy($id)
    {
        return (new UserController)->destroy($id);
    }
    
}
