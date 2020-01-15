<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Schedule;
use App\TrainingProgram;
use App\Person;
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
        			// delete previous schedule
                	$delete_sched = DB::table('schedules')->where('training_request_id',$training_request_id)->delete();
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

                   // $approvers = Approver::all();
                    $approvers = DB::table('persons')->where('person_type','approver')->get();
                  
                    foreach ($approvers as $value) {
                        $approval_status = new ApprovalStatus;
                        $approval_status->training_request_id = $training_request->training_request_id;
                        $approval_status->person_id = $value->person_id; // users person id as approver id
                        $approval_status->save();

                     
                        $person = Person::findOrFail($value->person_id);
                        
                        $params = [
                            'email_category_id'   => config('constants.superior_approval'),
                            'training_request_id' => $training_request->training_request_id,
                            'mail_template'       => 'approver.validate',
                            'subject'             => 'NOTICE OF TRAINING REQUEST',
                            'sender'              => config('mail.from.address'),
                            'recipient'           => $person->email,
                            'title'               => 'NOTICE OF TRAINING REQUEST',
                            'cc'                  => null,
                            'attachment'          => null,
                            'accept_url'          => config('app.pub_url')  . "superior/approve/".$approval_status->approval_status_id,
                            'deny_url'          => config('app.pub_url')  . "superior/disapprove/".$approval_status->approval_status_id
                        ];

                        	
        
        
                        $batch_mails->save_to_batch($params);
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
