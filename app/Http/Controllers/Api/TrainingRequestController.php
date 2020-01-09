<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\UserAccess;
use App\ApprovalStatus;
use App\Person;
use App\TrainingRequest;
use App\Services\BatchMails;
use App\TrainingProgram;
use App\DealerDetail;
use Carbon\Carbon;
use App\Dealer;
class TrainingRequestController extends Controller
{	
	public function training_requests_statuses()
	{
		return response()->json([
			'all_requests' => TrainingRequest::count(),
			'pending_requests' => TrainingRequest::where('request_status', 'pending')->count(),
			'approved_requests' => TrainingRequest::where('request_status', 'approved')->count(),
			'denied_requests' => TrainingRequest::where('request_status', 'denied')->count()
		]);
	}

	public function approver_statuses($training_request_id)
	{
		$query = ApprovalStatus::where('training_request_id', $training_request_id)
			->with('approver')
			->get();
		
		return response()->json($query);
	}

	public function index()
	{
		return response()->json(
			TrainingRequest::with([
				'training_program',
				'unit_model',
				'trainor_designations.trainor'
			])
			->get()
		);
	}

	public function show($training_request_id)
	{
		$query = TrainingRequest::where('training_request_id', $training_request_id)
			->with([
				'customer_dealers',
				'customer_models',
				'customer_participants',
				'training_program',
				'unit_model'
			])
			->first();

		return response()->json($query);
	}

	public function store(Request $request, BatchMails $batch_mails)
	{
		$this->validate($request, [
			'company_name'     => 'required|string',
			'office_address'   => 'required|string',
			'contact_person'   => 'required|string',
			'email'            => 'required|string',
			'position'         => 'required|string',
			'contact_number'   => 'required',
			'training_date'    => 'required|date',
			'training_venue'   => 'required|string',
			'training_address' => 'required|string',
			'unit_model_id' => 'required|integer|exists:unit_models,unit_model_id',
		]);
		
		try {
			DB::beginTransaction();

			$input = $request->all();
			
			$input['training_date'] = Carbon::parse($input['training_date'])->toDateTimeString();
			$query = TrainingRequest::create($input);
			$training_request_id = $query->training_request_id;

			// Training programs
			foreach ($input['training_program_ids'] as $program_id) {
				DB::table('training_request_programs')->insert([
					'training_request_id' => $training_request_id,
					'training_program_id' => $program_id
				]);
			}

			// Save Dealers
			foreach ($input['selling_dealer'] as $dealer_id) {
				$dealer = DB::table('dealers')->where('dealer_id', $dealer_id)->first();
				DB::table('customer_dealers')->insert([
					'training_request_id' => $training_request_id,
					'dealer'              => $dealer->dealer,
					'branch'              => $dealer->branch
				]);
			}

			// Customer Participants
			foreach ($input['training_participants'] as $value) {
				DB::table('customer_participants')->insert([
					'training_request_id' => $training_request_id,
					'participant'         => $value['participant'],
					'quantity'            => $value['quantity']
				]);
			}

			// Unit Models
			foreach ($input['unit_models'] as $model) {
				DB::table('customer_models')->insert([
					'training_request_id' => $training_request_id,
					'model'               => $model
				]);
			}

			// Dealer Detail
			if ($request->requestorType == 'dealer') {
				$dealer_details = Dealer::findOrFail($request->dealer_info['dealership_name']);
				$_dealer_detail = new DealerDetail;
				$_dealer_detail->requestor_name = $request->dealer_info['requestor_name'];
				$_dealer_detail->dealership_name = $dealer_details->dealer . ' | ' . $dealer_details->branch;
				$_dealer_detail->dealer_id = $request->dealer_info['dealership_name'];
				$_dealer_detail->position = $request->dealer_info['position'];
				$_dealer_detail->email = $request->dealer_info['email'];
				$_dealer_detail->contact = $request->dealer_info['contact'];
				$_dealer_detail->training_request_id = $training_request_id;
				$_dealer_detail->save();


				// email To dealer
		
		 		$batch_mails->save_to_batch([
					'email_category_id' => null,
					'subject'           => 'Request Submitted',
					'sender'            => config('mail.from.address'),
					'recipient'         => $dealer_details->email,
					'title'             => 'Request Submitted!',
					'mail_template'		=> 'dealer.new_request',
					'redirect_url'      => null,
					'cc'                => null,
					'attachment'        => null,
					'training_request_id' => $training_request_id
				]);

			}

			DB::commit();

		//	$training_program = TrainingProgram::findOrFail($query->training_program_id);
			if ($query) {
				$user_access = UserAccess::select('et.email')
					->leftJoin('email_tab as et', 'et.employee_id', '=', 'user_access_tab.employee_id')
					->where([
						'system_id'    => config('app.system_id'),
						'user_type_id' => 2
					])
					->get();

				// To Administrator
				foreach ($user_access as $value) {
					$batch_mails->save_to_batch([
						'email_category_id'   => config('constants.admin_approval'),
						'subject'             => 'Requesting for a Training',
						'sender'              => config('mail.from.address'),
						'recipient'           => $value['email'],
						'title'               => 'Training Request',
						'mail_template'       => 'admin.new_request',
						'redirect_url'        => 'http://ecommerce5/fleet_training_request/admin/training_requests',
						'cc'                  => null,
						'attachment'          => null,
						'training_request_id' => $training_request_id
					]);
				}

				// To Requestor
				$batch_mails->save_to_batch([
					'email_category_id' => config('constants.default_notification'),
					'subject'           => 'Request Submitted',
					'sender'            => config('mail.from.address'),
					'recipient'         => $query->email,
					'title'             => 'Request Submitted!',
					'mail_template'		=> 'customer.new_request',
					'redirect_url'      => null,
					'cc'                => null,
					'attachment'        => null,
					'training_request_id' => $training_request_id
				]);


				
 

				// to dealer sales
				$dealer_sales = DB::table('persons')
					->where([
						'person_type' => 'dealer_sales'
					])
					->get();
					
				foreach ($dealer_sales as $value) {
					$batch_mails->save_to_batch([
						'subject'             => 'Request Submitted',
						'sender'              => config('mail.from.address'),
						'recipient'           => $value->email,
						'title'               => 'Request Submitted',
						'mail_template'       => 'ipc.new_request',
						'cc'                  => null,
						'attachment'          => null,
						'training_request_id' => $training_request_id
					]);
				}

				return $query;


				// dealer
				

				// dealer sales
			}
		} catch (Exception $e) {
			report($e);
			DB::rollBack();

			return false;
		}
	}
}