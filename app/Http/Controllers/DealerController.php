<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Dealer;
use App\Http\Requests;

class DealerController extends Controller
{
	public function index()
	{
		return response()->json(Dealer::all());
	}

	public function show($dealer_id)
	{
		return response()->json(Dealer::findOrFail($dealer_id));
	}

	public function store(Request $request)
	{
		$this->validation($request);
		$query = new Dealer;
		$query->dealer = $request->dealer;
		$query->branch = $request->branch;
		$query->email = $request->email;
		$query->save();

		return response()->json($query);
	}

	public function update(Request $request, $dealer_id)
	{
		$this->validation($request);
		$query = Dealer::findOrFail($dealer_id);
		$query->dealer = $request->dealer;
		$query->branch = $request->branch;
		$query->email = $request->email;
		$query->save();

		return response()->json($query);
	}

	public function delete($dealer_id)
	{
		$query = Dealer::findOrFail($dealer_id)->delete();

		return response()->json($query);
	}

	private function validation($request)
	{
		return $this->validate($request, [
			'dealer' => 'required|string',
			'branch' => 'required|string'
		]);
	}
}
