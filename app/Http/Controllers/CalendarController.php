<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use MaddHatter\LaravelFullcalendar\Calendar;
use Illuminate\Http\Request;

use App\Schedule;
use App\Http\Requests;

class CalendarController extends Controller
{
	public function event_title($params)
	{
		if (isset($params['training_request_id'])) return 'Training Program';
		return $params['reason'];
	}

	public function events()
	{
		return Schedule::with('training_request.training_program')->get();
	}

	public function event($schedule_id)
	{
		return Schedule::with('training_request.training_program')
			->where('schedule_id', $schedule_id)
			->first();
	}

	public function save_event(Request $request)
	{
		$this->validate($request, [
			'start_date' => 'required|date',
			'end_date'   => 'required|date',
			'reason'     => 'required|string'
		]);

		$query = new Schedule([
			'start_date' => $request->start_date,
			'end_date'   => $request->end_date,
			'reason'     => $request->reason,
			'created_by' => $request->session()->get('full_name')
		]);

		$query->save();

		return response()->json($query);
	}

	public function delete_event($schedule_id)
	{
		return response()->json(Schedule::findOrFail($schedule_id)->delete());
	}

    // public function events()
    // {
	// 	$data = Schedule::all();
	// 	$events = [];
	// 	foreach ($data as $key => $value) {
	// 		$events[] = \Calendar::event(
	// 			$this->event_title([
	// 				'training_request_id' => $value['training_request_id'],
	// 				'reason'              => $value['reason']
	// 			]), //event title
	// 			true, //full day event?
	// 			Carbon::parse($value['start_date'])->toDateString(), //start time (you can also use Carbon instead of DateTime)
	// 			Carbon::parse($value['end_date'])->toDateString(), //end time (you can also use Carbon instead of DateTime)
	// 			$value['schedule_id'], //optionally, you can specify an event ID
	// 			['#7CB342'] // Color
	// 		);
	// 	}

	// 	$calendar = \Calendar::addEvents($events)
	// 		->setOptions([
	// 			'navLinks' => true,
	// 			// 'editable' => true,
	// 		]);

    //     return view('admin.calendar', compact('calendar'));
    // }
}
