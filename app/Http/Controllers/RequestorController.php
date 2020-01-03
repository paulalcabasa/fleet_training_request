<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\Person;
use App\UserAccess;
use App\TrainingRequest;
use App\Services\BatchMails;
use App\Http\Requests;

class RequestorController extends Controller
{
	public function confirm($training_request_id, BatchMails $batch_mails)
	{
		$check = TrainingRequest::findOrFail($training_request_id);
	
		if ($check->status == 'approved') {
	
			$query = DB::table('training_requests')
				->where('training_request_id', $training_request_id)
				->update([
					'status' => 'confirmed'
				]);
	
			if ($query) {
				 $trainors = DB::table('designated_trainors as dt')
					 ->leftJoin('persons as p', 'dt.person_id','=','p.person_id')
					 ->where([
						 ['dt.training_request_id', '=', $training_request_id]
					 ])->get();
				
				foreach ($trainors as $value) {
					
					$batch_mails->save_to_batch([
						'email_category_id'   => null,
						'subject'             => 'NOTICE OF CONFIRMED TRAINING REQUEST',
						'sender'              => config('mail.from.address'),
						'recipient'           => $value->email,
						'training_request_id' => $training_request_id,
						'mail_template'       => 'ipc.training_alert',
						'title'               => 'NOTICE OF CONFIRMED TRAINING REQUEST',
						'message'             => '',
						'cc'                  => null,
						'attachment'          => null
					]);
				}

				$user_access = UserAccess::select('et.email')
                    ->leftJoin('email_tab as et', 'et.employee_id', '=', 'user_access_tab.employee_id')
                    ->where([
                        'system_id'    => config('app.system_id'),
                        'user_type_id' => 2
                    ])
                    ->get();
		
                foreach ($user_access as $value) {
					
                    $query = $batch_mails->save_to_batch([
                        'email_category_id'   => null,
                        'subject'             => 'NOTICE OF CONFIRMED TRAINING REQUEST',
                        'sender'              => config('mail.from.address'),
                        'recipient'           => $value->email,
                        'training_request_id' => $training_request_id,
                        'mail_template'       => 'ipc.training_alert',
                        'title'               => 'NOTICE OF CONFIRMED TRAINING REQUEST',
                        'message'             => '',
                        'cc'                  => null,
                        'attachment'          => null,
                        'redirect_url'        => 'http://localhost/fleet_training_request/admin/training_requests'
                    ]);
                }

				$content = [
					'type'    => 'success',
					'message' => 'Your request has been confirmed. We will see you on Training Program. Thank you!'
				];
				return response()->view('public_pages.message', compact('content'));
			}
			else {
				$content = [
					'type'    => 'info',
					'message' => 'This request has been already confirmed.'
				];
				return response()->view('public_pages.message', compact('content'));
			}
		}
		else {
			$content = [
				'type'    => 'info',
				'message' => 'Sorry! It seems you\'ve been already made an action here.'
			];
			return response()->view('public_pages.message', compact('content'));
		}
	}

	public function cancel($training_request_id, BatchMails $batch_mails)
	{
		$check = TrainingRequest::findOrFail($training_request_id);

		if ($check->requestor_confirmation == 'pending') {
			$query = DB::table('training_requests')
				->where('training_request_id', $training_request_id)
				->update([
					'requestor_confirmation' => 'cancelled'
				]);
	
			if ($query) {
				$user_access = UserAccess::select('et.email')
                    ->leftJoin('email_tab as et', 'et.employee_id', '=', 'user_access_tab.employee_id')
                    ->where([
                        'system_id'    => config('app.system_id'),
                        'user_type_id' => 2
                    ])
                    ->get();
    
                foreach ($user_access as $value) {
                    $query = $batch_mails->save_to_batch([
                        'email_category_id' => null,
                        'subject'           => 'Training Program',
                        'sender'            => config('mail.from.address'),
                        'recipient'         => $value->email,
                        'title'             => 'Training Program',
                        'message'           => $check->contact_person . ' of <strong>'. $check->company_name .'</strong><br/>
                            has been cancelled the training program.',
                        'cc'           => null,
                        'attachment'   => null,
                        'redirect_url' => 'http://localhost/fleet_training_request/admin/training_requests'
                    ]);
                }

				$content = [
					'type'    => 'success',
					'message' => 'Your request has been cancelled.'
				];
				return response()->view('public_pages.message', compact('content'));
			}
			else {
				$content = [
					'type'    => 'info',
					'message' => 'Ooops! Your request has been already cancelled.'
				];
				return response()->view('public_pages.message', compact('content'));
			}
		}
		else {
			$content = [
				'type'    => 'info',
				'message' => 'Sorry! It seems you\'ve been already made an action here.'
			];
			return response()->view('public_pages.message', compact('content'));
		}
	}
	
	public function reschedule($training_request_id, BatchMails $batch_mails)
	{
		$check = TrainingRequest::findOrFail($training_request_id);

		if ($check->requestor_confirmation == 'pending') {
			$query = DB::table('training_requests')
				->where('training_request_id', $training_request_id)
				->update([
					'requestor_confirmation' => 'reschedule'
				]);

			if ($query) {
				$user_access = UserAccess::select('et.email')
					->leftJoin('email_tab as et', 'et.employee_id', '=', 'user_access_tab.employee_id')
					->where([
						'system_id'    => config('app.system_id'),
						'user_type_id' => 2
					])
					->get();

				foreach ($user_access as $value) {
					$query = $batch_mails->save_to_batch([
						'email_category_id' => null,
						'subject'           => 'Reschedule Training Program',
						'sender'            => config('mail.from.address'),
						'recipient'         => $value->email,
						'title'             => 'Reschedule Training Program',
						'message'           => $check->contact_person . ' of <strong>'. $check->company_name .'</strong><br/>
							has been requesting for a reschedule of their training program. <br/>
							You may call him/her on this number: <strong>'.$check->contact_number.'</strong>',
						'cc'           => null,
						'attachment'   => null,
						'redirect_url' => 'http://localhost/fleet_training_request/admin/training_requests'
					]);
				}

				$content = [
					'type'    => 'success',
					'message' => 'Your request for a reschedule training program has been sent! Please wait for our response. Thank you.'
				];
				return response()->view('public_pages.message', compact('content'));
			}
			else {
				$content = [
					'type'    => 'info',
					'message' => 'Sorry! It seems you\'ve been already made an action here.'
				];
				return response()->view('public_pages.message', compact('content'));
			}
		}
		else {
			$content = [
				'type'    => 'info',
				'message' => 'Sorry! It seems you\'ve been already made an action here.'
			];
			return response()->view('public_pages.message', compact('content'));
		}
	}
}