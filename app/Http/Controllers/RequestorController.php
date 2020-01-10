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

				// send to designated trainor
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
						'mail_template'       => 'trainor.training_alert',
						'title'               => 'NOTICE OF CONFIRMED TRAINING REQUEST',
						'message'             => '',
						'cc'                  => null,
						'attachment'          => null
					]);
				}

				// send to admins
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
                        'mail_template'       => 'admin.training_alert',
                        'title'               => 'NOTICE OF CONFIRMED TRAINING REQUEST',
                        'message'             => '',
                        'cc'                  => null,
                        'attachment'          => null,
                        'redirect_url'        => 'http://localhost/fleet_training_request/admin/training_requests'
                    ]);
				}
				
				// send to requestor
				$batch_mails->save_to_batch([
					'email_category_id'   => null,
					'subject'             => 'NOTICE OF TRAINING DETAILS',
					'sender'              => config('mail.from.address'),
					'recipient'           => $check->email,
					'training_request_id' => $training_request_id,
					'mail_template'       => 'customer.training_details',
					'title'               => 'NOTICE OF TRAINING DETAILS',
					'message'             => '',
					'cc'                  => null,
					'attachment'          => null
				]);

				$dealer = DB::table('dealer_details')
					->leftJoin('dealers', 'dealer_details.dealer_id', '=', 'dealers.dealer_id')
					->where('dealer_details.training_request_id',$training_request_id)
					->get();
				// To dealer
				foreach($dealer as $value){
					$batch_mails->save_to_batch([
						'email_category_id' => null,
						'subject'           => 'NOTICE OF CONFIRMED TRAINING REQUEST',
						'sender'            => config('mail.from.address'),
						'recipient'         => $value->email,
						'title'             => 'NOTICE OF CONFIRMED TRAINING REQUEST',
						'mail_template'		=> 'dealer.training_alert',
						'redirect_url'      => null,
						'cc'                => null,
						'attachment'        => null,
						'training_request_id' => $training_request_id
					]); 
				}
			
				// to dealer sales
				$dealer_sales = DB::table('persons')
					->where('person_type','dealer_sales')
					->get();
					
				foreach ($dealer_sales as $value) {
					$batch_mails->save_to_batch([
						'subject'             => 'NOTICE OF CONFIRMED TRAINING REQUEST',
						'sender'              => config('mail.from.address'),
						'recipient'           => $value->email,
						'title'               => 'NOTICE OF CONFIRMED TRAINING REQUEST',
						'mail_template'       => 'ipc.training_alert',
						'cc'                  => null,
						'attachment'          => null,
						'training_request_id' => $training_request_id
					]);
				}

				$approvers = DB::table('approval_statuses as at')
					->leftJoin('persons as pr', 'at.person_id','=','pr.person_id')
					->where('at.training_request_id',$training_request_id)
					->get();

				foreach ($approvers as $value) {
					$batch_mails->save_to_batch([
						'subject'             => 'NOTICE OF CONFIRMED TRAINING REQUEST',
						'sender'              => config('mail.from.address'),
						'recipient'           => $value->email,
						'title'               => 'NOTICE OF CONFIRMED TRAINING REQUEST',
						'mail_template'       => 'approver.training_alert',
						'cc'                  => null,
						'attachment'          => null,
						'training_request_id' => $training_request_id
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
		$training_request = TrainingRequest::findOrFail($training_request_id);

        $data = [
            'base_url'            => url('/'),
            'training_request_id' => $training_request_id,
			'training_request'    => $training_request,
			'status'              => $training_request->status
        ];
        return response()->view('public_pages.cancel_request',compact('data'));
	}

	public function cancel_training_request(Request $request, BatchMails $batch_mails){
		
		$training_request_id = $request->training_request_id;
		$reason = $request->reason;
	
		$check = TrainingRequest::findOrFail($training_request_id);

		if ($check->status == 'approved') {
			$query = DB::table('training_requests')
				->where('training_request_id', $training_request_id)
				->update([
					'status'               => 'cancelled',
					'cancellation_remarks' => $reason
				]);
	
			if ($query) {
		
				/* email receivers
					1. Admin
					2. Dealer
					3. IPC
					4. Approver
					5. Customer
				*/

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
                        'subject'             => 'NOTICE OF CANCELLED TRAINING REQUEST',
                        'sender'              => config('mail.from.address'),
                        'recipient'           => $value->email,
                        'title'               => 'NOTICE OF CANCELLED TRAINING REQUEST',
                        'training_request_id' => $training_request_id,
                        'mail_template'       => 'admin.cancel_request',
                        'cc'           => null,
                        'attachment'   => null,
                        'redirect_url' => 'http://localhost/fleet_training_request/admin/training_requests'
                    ]);
				}
				
				// send to requestor
				$query = $batch_mails->save_to_batch([
					'email_category_id'   => null,
					'subject'             => 'NOTICE OF CANCELLED TRAINING REQUEST',
					'sender'              => config('mail.from.address'),
					'recipient'           => $check->email,
					'title'               => 'NOTICE OF CANCELLED TRAINING REQUEST',
					'training_request_id' => $training_request_id,
					'mail_template'       => 'customer.cancel_request',
					'cc'           => null,
					'attachment'   => null,
					'redirect_url' => 'http://localhost/fleet_training_request/admin/training_requests'
				]);	

				// send to dealer
				$dealer = DB::table('dealer_details')
					->leftJoin('dealers', 'dealer_details.dealer_id', '=', 'dealers.dealer_id')
					->where('dealer_details.training_request_id',$training_request_id)
					->get();
				// To dealer
				foreach($dealer as $value){
					$batch_mails->save_to_batch([
						'email_category_id'   => null,
						'subject'             => 'NOTICE OF CANCELLED TRAINING REQUEST',
						'sender'              => config('mail.from.address'),
						'recipient'           => $value->email,
						'title'               => 'NOTICE OF CANCELLED TRAINING REQUEST',
						'training_request_id' => $training_request_id,
						'mail_template'       => 'dealer.cancel_request',
						'cc'           => null,
						'attachment'   => null,
						'redirect_url' => 'http://localhost/fleet_training_request/admin/training_requests'
					]);	
				}


				// dealer sales
				$dealer_sales = DB::table('persons')
					->where('person_type','dealer_sales')
					->get();
					
				foreach ($dealer_sales as $value) {
					$batch_mails->save_to_batch([
						'email_category_id'   => null,
						'subject'             => 'NOTICE OF CANCELLED TRAINING REQUEST',
						'sender'              => config('mail.from.address'),
						'recipient'           => $value->email,
						'title'               => 'NOTICE OF CANCELLED TRAINING REQUEST',
						'training_request_id' => $training_request_id,
						'mail_template'       => 'ipc.cancel_request',
						'cc'           => null,
						'attachment'   => null,
						'redirect_url' => 'http://localhost/fleet_training_request/admin/training_requests'
					]);	
				}

				// approvers
				$approvers = DB::table('approval_statuses as at')
					->leftJoin('persons as pr', 'at.person_id','=','pr.person_id')
					->where('at.training_request_id',$training_request_id)
					->get();

				foreach ($approvers as $value) {
						$batch_mails->save_to_batch([
						'email_category_id'   => null,
						'subject'             => 'NOTICE OF CANCELLED TRAINING REQUEST',
						'sender'              => config('mail.from.address'),
						'recipient'           => $value->email,
						'title'               => 'NOTICE OF CANCELLED TRAINING REQUEST',
						'training_request_id' => $training_request_id,
						'mail_template'       => 'approver.cancel_request',
						'cc'           => null,
						'attachment'   => null,
						'redirect_url' => 'http://localhost/fleet_training_request/admin/training_requests'
					]);	
				}

				$content = [
					'type'    => 'success',
					'message' => 'Your request has been cancelled.'
				];
			}
			else {
				$content = [
					'type'    => 'info',
					'message' => 'Ooops! Your request has been already cancelled.'
				];

			}
		}
		else {
			$content = [
				'type'    => 'info',
				'message' => 'Sorry! It seems you\'ve been already made an action here.'
			];

		}

		return response()->json($content);

	}
	
	public function reschedule($training_request_id, BatchMails $batch_mails)
	{
		/* 
			Approver
			Customer
			Admin
			Dealer
			IPC
		*/
		$check = TrainingRequest::findOrFail($training_request_id);

		if ($check->status == 'approved') {
			$query = DB::table('training_requests')
				->where('training_request_id', $training_request_id)
				->update([
					'status' => 'reschedule'
				]);

			if ($query) {

				// send to admin 
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
						'subject'             => 'Reschedule Training Program',
						'sender'              => config('mail.from.address'),
						'recipient'           => $value->email,
						'title'               => 'Reschedule Training Program',
						'training_request_id' => $training_request_id,
						'mail_template'       => 'admin.reschedule',
						'cc'           => null,
						'attachment'   => null,
						'redirect_url' => 'http://localhost/fleet_training_request/admin/training_requests'
					]);
				}

				// to fleet customer
				$query = $batch_mails->save_to_batch([
					'email_category_id'   => config('constants.default_notification'),
					'subject'             => 'Rescheduled Training Program',
					'sender'              => config('mail.from.address'),
					'recipient'           => $check->email,
					'title'               => 'Rescheduled Training Program',
					'mail_template'       => 'customer.reschedule',
					'training_request_id' => $training_request_id
				]);			

				// send to dealer
				$dealer = DB::table('dealer_details')
					->leftJoin('dealers', 'dealer_details.dealer_id', '=', 'dealers.dealer_id')
					->where('dealer_details.training_request_id',$training_request_id)
					->get();
				// To dealer
				foreach($dealer as $value){
					$batch_mails->save_to_batch([
						'subject'             => 'Rescheduled Training Program',
						'sender'              => config('mail.from.address'),
						'recipient'           => $value->email,
						'title'               => 'Rescheduled Training Program',
						'training_request_id' => $training_request_id,
						'mail_template'       => 'dealer.reschedule'
					]);	
				}

				// dealer sales
				$dealer_sales = DB::table('persons')
					->where('person_type','dealer_sales')
					->get();
					
				foreach ($dealer_sales as $value) {
					$batch_mails->save_to_batch([
						'subject'             => 'Rescheduled Training Program',
						'sender'              => config('mail.from.address'),
						'recipient'           => $value->email,
						'title'               => 'Rescheduled Training Program',
						'training_request_id' => $training_request_id,
						'mail_template'       => 'ipc.reschedule',
					]);	
				}

				// approvers
				$approvers = DB::table('approval_statuses as at')
					->leftJoin('persons as pr', 'at.person_id','=','pr.person_id')
					->where('at.training_request_id',$training_request_id)
					->get();

				foreach ($approvers as $value) {
						$batch_mails->save_to_batch([
						'subject'             => 'Rescheduled Training Program',
						'sender'              => config('mail.from.address'),
						'recipient'           => $value->email,
						'title'               => 'Rescheduled Training Program',
						'training_request_id' => $training_request_id,
						'mail_template'       => 'approver.reschedule'
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