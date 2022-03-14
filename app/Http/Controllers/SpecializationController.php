<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialization;

class SpecializationController extends Controller
{
    
    public function index()
    {
        return Specialization::all();
    }

    
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        $spec = new Specialization;
        $spec->name = $request->name;
        $spec->save();
        return "inserted";
    }

    
    public function show($id)
    {
        return Specialization::find($id);
    }

    
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        $spec = Specialization::find($id);
        $spec->name = $request->name;
        $spec->save();
        return "updated";
    }

    
    public function destroy($id)
    {
        Specialization::destroy($id);
        return "deleted";
    }
}
