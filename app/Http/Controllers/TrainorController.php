<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Trainor;
use App\Http\Requests;

class TrainorController extends Controller
{
    public function index()
    {
        return Trainor::all();
    }

    public function show($trainor_id)
    {
        return Trainor::findOrFail($trainor_id);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'fname' => 'required|string',
            'mname' => 'string',
            'lname' => 'required|string',
            'email' => 'required|email'
        ]);

        $query = new Trainor;
        $query->fname = $request->fname;
        $query->mname = $request->mname;
        $query->lname = $request->lname;
        $query->email = $request->email;
        $query->created_by = $request->session()->get('full_name');
        $query->save();
        
   
        return 201;
    }
    
    public function update(Request $request, $trainor_id)
    {
        $this->validate($request, [
            'fname' => 'required|string',
            'mname' => 'string',
            'lname' => 'required|string',
            'email' => 'required|email'
        ]);

        $query = Trainor::findOrFail($trainor_id);
        $query->fname = $request->fname;
        $query->mname = $request->mname;
        $query->lname = $request->lname;
        $query->email = $request->email;
        $query->save();

        return 200;
    }

    public function delete($trainor_id)
    {
        $query = Trainor::findOrFail($trainor_id);
        $query->deleted_at = Carbon::now();
        $query->save();

        return 200;
    }

    public function undo_delete($trainor_id)
    {
        $query = Trainor::findOrFail($trainor_id);
        $query->deleted_at = NULL;
        $query->save();

        return 200;
    }

    public function permanent_delete($trainor_id)
    {
        $query = Trainor::findOrFail($trainor_id);
        $query->delete();

        return 200;
    }
}