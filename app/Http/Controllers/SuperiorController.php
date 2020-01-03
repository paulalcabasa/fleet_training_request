<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Trainor;
use App\UserAccess;
use App\ApprovalStatus;
use App\TrainingRequest;
use App\Services\BatchMails;
use App\Http\Requests;

class SuperiorController extends Controller
{
    public function approve($approval_status_id, BatchMails $batch_mails)
    {
        $query = ApprovalStatus::findOrFail($approval_status_id);

        if ($query->status == 'pending') {
            $update = DB::table('approval_statuses')
                ->where('approval_status_id', $query->approval_status_id)
                ->update(['status' => 'approved']);

            if ($update) {
                // Check if all approvers approved the request
                $training_request_id = $query->training_request_id;

                $check = DB::table('approval_statuses')
                    ->where([
                        ['training_request_id', '=', $training_request_id],
                        ['status', '=', 'pending']
                    ])
                    ->count();

                $check_denied = DB::table('approval_statuses')
                    ->where([
                        ['training_request_id', '=', $training_request_id],
                        ['status', '=', 'denied']
                    ])
                    ->count();

                if ($check == 0 && $check_denied == 0) {
                    // Fetch training_request
                    $training_request = TrainingRequest::findOrFail($query->training_request_id);

                    $update_status = DB::table('training_requests')
                        ->where('training_request_id', $training_request_id)
                        ->update(['status' => 'approved']);

                    // To Requestor
                    $batch_mails->save_to_batch([
                        'email_category_id'   => config('constants.requestor_notification'),
                        'subject'             => 'Training Program',
                        'sender'              => config('mail.from.address'),
                        'recipient'           => $training_request->email,
                        'training_request_id' => $query->training_request_id,
                        'mail_template'       => 'customer.confirm',
                        'title'               => 'Training Program',
                        'message'             => 'Greetings! IPC Administrator has been approved your <strong>training request</strong>.<br/>
                            Training program will be held on: '. $training_request->training_address .' <br/>
                            at       '. Carbon::parse($training_request->training_date)->format('M d, Y D - h: i A'),
                        'cc'           => null,
                        'attachment'   => null,
                        'accept_url'   => route('customer_confirmation', ['training_request_id' => $training_request->training_request_id]),
                        'deny_url'     => route('customer_cancellation', ['training_request_id' => $training_request->training_request_id]),
                        'redirect_url' => route('customer_reschedule', ['training_request_id' => $training_request->training_request_id])
                    ]); 
                }

                $content = [
                    'type' => 'success',
                    'message' => 'You have successfully approved the request.'
                ];
                return response()->view('public_pages.message', compact('content'));
            }
            else {
                $content = [
                    'type' => 'info',
                    'message' => 'This request has been already approved.'
                ];
                return response()->view('public_pages.message', compact('content'));
            }
        }
        else {
            $content = [
                'type' => 'info',
                'message' => 'This request has been already approved.'
            ];
            return response()->view('public_pages.message', compact('content'));
        }
    }

    public function disapprove($approval_status_id, BatchMails $batch_mails)
    {
        // Check if request is already denied and approved: cancel action
        $approval = ApprovalStatus::with('approver')->findOrFail($approval_status_id);

        if ($approval->status == 'pending') {
            // Disapprove request
            if ($approval) {
                $updated = DB::table('approval_statuses')
                    ->where('approval_status_id', $approval->approval_status_id)
                    ->update(['status' => 'denied']);
            }
            else {
                $content = [
                    'type' => 'error',
                    'message' => 'Ooops! Something went wrong, file doesn\'t exists.'
                ];
                return response()->view('public_pages.message', compact('content'));
            }
    
            // Batch email
            if ($updated) {
    
                $user_access = UserAccess::select('et.email')
                    ->leftJoin('email_tab as et', 'et.employee_id', '=', 'user_access_tab.employee_id')
                    ->where([
                        'system_id'    => config('app.system_id'),
                        'user_type_id' => 2
                    ])
                    ->get();
    
                foreach ($user_access as $value) {
                    $query = $batch_mails->save_to_batch([
                        'email_category_id' => config('constants.superior_disapproval'),
                        'subject'           => 'Training Program',
                        'sender'            => config('mail.from.address'),
                        'recipient'         => $value->email,
                        'title'             => 'Training Program',
                        'message'           => 'Sorry! The request has been <strong>disapproved</strong> by one of your approver '. $approval->approver->approver_name .'.<br/>
                            The training program will not be granted. Thank you.',
                        'cc'           => null,
                        'attachment'   => null,
                        'redirect_url' => 'http://localhost/fleet_training_request/admin/training_requests'
                    ]);
                }
                
                $content = [
                    'type' => 'success',
                    'message' => 'You have successfully disapproved a request.'
                ];
                return response()->view('public_pages.message', compact('content'));
            }
            else {
                $content = [
                    'type' => 'info',
                    'message' => 'This request has been already disapproved.'
                ];
                return response()->view('public_pages.message', compact('content'));
            }
        }
        else {
            $content = [
                'type' => 'info',
                'message' => 'This request has been already disapproved.'
            ];
            return response()->view('public_pages.message', compact('content'));
        }
    }
}