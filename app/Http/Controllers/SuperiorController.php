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
                        'subject'             => 'NOTICE OF APPROVED TRAINING REQUEST',
                        'sender'              => config('mail.from.address'),
                        'recipient'           => $training_request->email,
                        'training_request_id' => $query->training_request_id,
                        'mail_template'       => 'customer.confirm',
                        'title'               => 'NOTICE OF APPROVED TRAINING REQUEST',
                        'message'             => null,
                        'cc'                  => null,
                        'attachment'          => null,
                        'accept_url'          => config('app.pub_url')  . "customer/confirm_request/".$training_request->training_request_id,
                        'deny_url'            => config('app.pub_url')  . "customer/cancellation_request/".$training_request->training_request_id,
                        'redirect_url'        => config('app.pub_url')  . "customer/reschedule_request/".$training_request->training_request_id,
                        
/*                         'accept_url'          => route('customer_confirmation', ['training_request_id' => $training_request->training_request_id]),
                        'deny_url'            => route('customer_cancellation', ['training_request_id' => $training_request->training_request_id]),
                        'redirect_url'        => route('customer_reschedule', ['training_request_id' => $training_request->training_request_id]) */
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
        $approval = ApprovalStatus::with('approver')->findOrFail($approval_status_id);

        $data = [
            'base_url' => url('/'),
            'approval_status_id' => $approval_status_id,
            'status' => $approval->status
        ];
        return response()->view('public_pages.disapprove',compact('data'));
      
    }

    public function disapprove_request(Request $request, BatchMails $batch_mails)
    {
        $approval = ApprovalStatus::with('approver')->findOrFail($request->approval_status_id);

        if ($approval->status == 'pending') {
            // Disapprove request
            if ($approval) {
                $updated = DB::table('approval_statuses')
                    ->where('approval_status_id', $approval->approval_status_id)
                    ->update([
                        'status' => 'denied',
                        'remarks' => $request->reason
                    ]);
                
                $delete_sched = DB::table('schedules')->where('training_request_id',$approval->training_request_id)->delete();
         

                DB::table('training_requests')
                    ->where('training_request_id', $approval->training_request_id)
                    ->update([
                        'status' => 'denied'
                    ]);
            }
            else {
                $content = [
                    'type'    => 'error',
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
                        //'email_category_id' => config('constants.superior_disapproval'),
                        'subject'             => 'NOTICE OF DISAPPROVAL',
                        'sender'              => config('mail.from.address'),
                        'recipient'           => $value->email,
                        'mail_template'       => 'admin.denied_request',
                        'title'               => 'NOTICE OF DISAPPROVAL',
                        'training_request_id' => $approval->training_request_id,
                     /*    'message'           => 'Sorry! The request has been <strong>disapproved</strong> by one of your approver '. $approval->approver->approver_name .'.<br/>
                            The training program will not be granted. Thank you.', */
                        'cc'           => null,
                        'attachment'   => null,
                        'redirect_url' => 'http://localhost/fleet_training_request/admin/training_requests'
                    ]);
                }
                
                $content = [
                    'type'    => 'success',
                    'message' => 'Request has been disapproved.'
                ];
              
            }
            else {
                $content = [
                    'type'    => 'info',
                    'message' => 'This request has been already disapproved.'
                ];
               
            }
        }
        else {
            $content = [
                'type'    => 'info',
                'message' => 'This request has been already disapproved.'
            ];
            
        } 

        return response()->json($content);
    }
}