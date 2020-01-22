<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\EmissionStandard;

class EmissionController extends Controller
{
    public function index()
    {
        return response()->json(EmissionStandard::all());
    }

    public function show($emission_standard_id)
    {
        return response()->json(EmissionStandard::findOrFail($emission_standard_id));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
			'name' => 'required|string',
            'status' => 'required'
        ]);
        
        $query = new EmissionStandard;
		$query->name = $request->name;
	    $query->status = $request->status;
		$query->save();

		return response()->json($query);
    }

    public function update(Request $request, $emission_standard_id) {
        $this->validate($request, [
			'name' => 'required|string',
            'status' => 'required'
        ]);

        $query = EmissionStandard::findOrFail($emission_standard_id);
		$query->name = $request->name;
	    $query->status = $request->status;
		$query->save();

		return response()->json($query);
    }

}
