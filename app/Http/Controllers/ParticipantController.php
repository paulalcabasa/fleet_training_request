<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\TrainingRequest;
use App\TrainingParticipant;
use Carbon\Carbon;

class ParticipantController extends Controller
{
    public function index(Request $request)
    {
        $training_request_id = $request->training_request_id;
        $trainingRequest = TrainingRequest::where('training_request_id', $training_request_id)->first();
        $data = [
            'trainingRequest' => $trainingRequest
        ];
  
        return view('admin/participants', $data);
    }

    public function getParticipants(Request $request)
    {
        return TrainingParticipant::where('training_request_id', $request->training_request_id)->get();
 
    }

    public function store(Request $request)
    {
        
        $this->validate($request, [
			'first_name' => 'required|string',
			'last_name' => 'required|string'
        ]);
        
    
        $query = new TrainingParticipant;
		$query->training_request_id = $request->training_request_id;
		$query->first_name = $request->first_name;
		$query->middle_name = $request->middle_name;
		$query->last_name = $request->last_name;
	    $query->result = $request->result;
	    $query->remarks = $request->remarks;
	    $query->date_created = Carbon::now();
	    $query->created_by = $request->session()->get('employee_id');
		$query->save();

		return response()->json($query);
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
			'first_name' => 'required|string',
			'last_name' => 'required|string'
        ]);

        $query = TrainingParticipant::findOrFail($id);
		$query->first_name = $request->first_name;
		$query->middle_name = $request->middle_name;
		$query->last_name = $request->last_name;
	    $query->result = $request->result;
	    $query->remarks = $request->remarks;
	    $query->date_updated = Carbon::now();
	    $query->updated_by = $request->session()->get('employee_id');
        $query->save();
		return response()->json($query);
    }

    public function destroy($id)
    {
        $query = TrainingParticipant::findOrFail($id);
        $query->delete();
		return response()->json($query);
    }
    
}
