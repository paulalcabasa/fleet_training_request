<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;
use App\Person;
use App\Http\Requests;

class PersonController extends Controller
{
    public function index()
    {
        return response()->json(Person::where([
            ['status' , 'active']
        ])->get());
        //return response()->json(Approver::with('approval_statuses')->oldest()->get());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'position' => 'required|string',
        ]);

        $query              = new Person;
        $query->first_name  = $request->first_name;
        $query->middle_name = $request->middle_name;
        $query->last_name   = $request->last_name;
        $query->email       = $request->email;
        $query->person_type = $request->person_type;
        $query->position    = $request->position;
        $query->status      = 'active';
        $query->created_by  = $request->session()->get('employee_id');
        $query->save();
        
        return response()->json($query);
    }

    public function show($person_id)
    {
        return response()->json(Person::findOrFail($person_id));
    }

    public function update(Request $request, $person_id)
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'position' => 'required|string',
        ]);
        
        $query = Person::findOrFail($person_id);
        $query->first_name  = $request->first_name;
        $query->middle_name = $request->middle_name;
        $query->last_name   = $request->last_name;
        $query->email       = $request->email;
        $query->person_type = $request->person_type;
        $query->position    = $request->position;
        $query->updated_by  = $request->session()->get('employee_id');
        $query->save();
        
        return response()->json($query);
    }

    public function destroy($person_id)
    {
        $query = Person::findOrFail($person_id);
        $query->status = 'deleted';
        $query->save();

        return response()->json($query);
    }
}
