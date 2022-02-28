<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Http\Resources\StudentResource;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;

class StudentController extends Controller
{
    public function index()
    {
        $allStudents = Student::all();
        return  $allStudents->all();
    }

    public function showImage($studentId)
    {
        $picture_path = Student::where('id',$studentId)->first()->picture_path;
        $imgsrc= asset('storage/assets/'. $picture_path);
        return response()->json($imgsrc);
    }

    public function show($studentId)
    {
        $student = Student::find($studentId);
        return $student;
    }
    public function home($studentId)
    {
        $student = Student::find($studentId);
        $subjects = Subject::where('classroomId', '=', $student->classroomId)->get();
        return  $subjects;
    }

    public function store()
    {


        request()->validate([
            'picture_path' => 'image|mimes:jpeg,pmb,png,jpg|max:88453'
        ]);


        if (request()->hasFile('picture_path')) //if user choose file
        {

            $file = request()->file('picture_path'); //store  uploaded file to variable $file to 

            $extension = $file->getClientOriginalExtension();
            $filename = 'student-image' . '_' . time() . '.' . $extension;
            $file->storeAs('public/assets', $filename); //make folder assets in public/storage/assets and put file


        } else {

            $filename = 'storage/app/public/assets/image_tmp.jpeg';
        }

        $data = request()->all();

        Student::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'picture_path' =>  $filename,
            'classroomId' => $data['classroomId'],
            'government' => $data['government'],
            'city' => $data['city'],
            'street' => $data['street'],
        ]);
    }

    public function update($studentId)
    {
        request()->validate([
            'picture_path' => 'image|mimes:jpeg,pmb,png,jpg|max:88453'
        ]);


        if (request()->hasFile('picture_path')) //if user choose file
        {
            $file = request()->file('picture_path'); //store  uploaded file to variable $file to 
            $extension = $file->getClientOriginalExtension();
            $filename = 'student-image' . '_' . time() . '.' . $extension;
            $file->storeAs('public/assets', $filename); //make folder assets in public/storage/assets and put file

        } else {

            $filename =  Student::where('id', $studentId)->get('picture_path');
        }

        $data = request()->all();

        Student::where('id', $studentId)->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'picture_path' =>  $filename,
            'classroomId' => $data['classroomId'],
            'government' => $data['government'],
            'city' => $data['city'],
            'street' => $data['street'],
        ]);

        $student = Student::find($studentId);
        return ($student);
    }

    public function destroy($studentId)
    {
        Student::where('id', $studentId)->delete();
        // $allStudents = Student::all();
        // return  StudentResource::collection($allStudents);
    }
}
