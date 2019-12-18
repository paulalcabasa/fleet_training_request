<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Schedule;
use App\TrainingProgram;
use App\Approver;
use App\ApprovalStatus;
use App\TrainingRequest;
use App\Services\SendEmail;
use App\Services\BatchMails;
use App\Http\Requests;

class ApproveRequestController extends Controller
{
    public function update_request(Request $request, $training_request_id, SendEmail $mail, BatchMails $batch_mails) // to administrator
    {
        $training_request = DB::table('training_requests')->where('training_request_id', $training_request_id)->first();
        
        if ($training_request) {
            $query = DB::table('training_requests')->where('training_request_id', $training_request->training_request_id)
                ->update([
                    'status' => $request->request_status
                ]);
                
            // If $query == 1 email sent
            if ($query) {
                if ($request->request_status == 'denied') {
                    return response()->json($query);
                }
                else {
                    // insert data in schedules
                    $query                      = new Schedule;
                    $query->start_date          = $training_request->training_date;
                    $query->end_date            = $training_request->training_date;
                    $query->reason              = 'Training Program';
                    $query->training_request_id = $training_request->training_request_id;
                    $query->created_by          = $training_request->company_name . ' | ' . $training_request->contact_person;
                    $query->save();

                    $approvers = Approver::all();

                    foreach ($approvers as $value) {
                        $approval_status = new ApprovalStatus;
                        $approval_status->training_request_id = $training_request->training_request_id;
                        $approval_status->approver_id = $value['approver_id'];
                        $approval_status->save();

                        $approver_id = $approval_status->approver_id;
                        $approver = Approver::findOrFail($approver_id);
                      
                        $batch_mails->save_to_batch([
                            'email_category_id' => config('constants.superior_approval'),
                            'subject'           => 'Training Request Approval',
                            'sender'            => config('mail.from.address'),
                            'recipient'         => $approver->email,
                            'title'             => 'Training Request Approval',
                            'message'           => 'Greetings! '. $training_request->contact_person .' of <strong>'. $training_request->company_name .'</strong> is requesting for a <br/>
                            training program on '. Carbon::parse($training_request->training_date)->format('M d, Y'),
                            'cc'         => null,
                            'attachment' => null,
                            'accept_url' => route('superior_approval', ['approval_status_id' => $approval_status->approval_status_id]),
                            'deny_url'   => route('superior_disapproval', ['approval_status_id' => $approval_status->approval_status_id])
                        ]);
                    }  
                    return response()->json($query);
                }
            }
        }

    }

    public function update_approval_request($approval_status_id, $status)
    {
        $query = ApprovalStatus::findOrFail($approval_status_id);
        $query->status = $status;
        $query->save();

        return response()->json($query);
    }
}
