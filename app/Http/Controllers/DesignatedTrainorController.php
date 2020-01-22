<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Person;
use App\DesignatedTrainor;
use App\Http\Requests;

class DesignatedTrainorController extends Controller
{
    public function assigned_trainors($training_request_id)
    {
        return Person::with(['designated_trainors.training_request','designated_trainors' => function($query) use($training_request_id) {
                $query->where('training_request_id', '=', $training_request_id);
            }])
            ->where([
                ['status', 'active'],
                ['person_type', 'trainer'],
            ])
            ->get();
    }

    public function assign_trainor(Request $request)
    {
        $query = new DesignatedTrainor;
        $query->training_request_id = $request->training_request_id;
        $query->person_id = $request->person_id;
        $query->assigned_by = $request->session()->get('full_name');
        $query->save();

        return response()->json($query);
    }

    public function remove_trainor(Request $request)
    {
        $query = DB::table('designated_trainors')
            ->where([
                'training_request_id' => $request->training_request_id,
                'person_id' => $request->person_id
            ])
            ->delete();

        return response()->json($query);
    }
}
