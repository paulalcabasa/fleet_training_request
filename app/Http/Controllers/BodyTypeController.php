<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\BodyType;

class BodyTypeController extends Controller
{
    
    public function index()
    {
        return response()->json(BodyType::all());
    }

    public function show($body_type_id)
    {
        return response()->json(BodyType::findOrFail($body_type_id));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
			'name' => 'required|string',
            'status' => 'required'
        ]);
        
        $query = new BodyType;
		$query->name = $request->name;
	    $query->status = $request->status;
		$query->save();

		return response()->json($query);
    }

    public function update(Request $request, $body_type_id) {
        $this->validate($request, [
			'name' => 'required|string',
            'status' => 'required'
        ]);

        $query = BodyType::findOrFail($body_type_id);
		$query->name = $request->name;
	    $query->status = $request->status;
		$query->save();

		return response()->json($query);
    }
}