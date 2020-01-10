<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\BatchMails;
use App\TrainingRequest;
use Carbon\Carbon;
use App\Http\Requests;
use App\Schedule;
use Illuminate\Support\Facades\DB;
use App\UserAccess;

class RescheduleController extends Controller
{
    public function reschedule(Request $request, $training_request_id, BatchMails $batch_mails)
    {

        $training_date =  Carbon::parse($request->training_date)->toDateTimeString();
        $training_time = $request->training_time;
        $training_request = TrainingRequest::findOrFail($training_request_id);
        $training_request->training_date = $training_date;
        $training_request->training_time = $training_time;
        $training_request->status = 'approved';
        $training_request->save();

        
        // delete previous schedule
        $delete_sched = DB::table('schedules')->where('training_request_id',$training_request_id)->delete();

        // insert data in schedules
        $schedule                      = new Schedule;
        $schedule->start_date          = $training_date;
        $schedule->end_date            = $training_date;
        $schedule->reason              = 'Reschedule Training Program';
        $schedule->training_request_id = $training_request_id;
        $schedule->created_by          = $training_request->company_name . ' | ' . $training_request->contact_person;
        $schedule->save();
    

        if ($training_request) {
            /* 
                fleet customer
                approvers
                admin
                dealer
                ipc
            */
            // to fleet customer
            $query = $batch_mails->save_to_batch([
                'email_category_id'   => config('constants.default_notification'),
                'subject'             => 'Rescheduled Training Program',
                'sender'              => config('mail.from.address'),
                'recipient'           => $training_request->email,
                'title'               => 'Rescheduled Training Program',
                'mail_template'       => 'customer.confirm_new_details',
                'training_request_id' => $training_request_id,
                'accept_url'          => route('customer_confirmation', ['training_request_id' => $training_request_id]),
                'deny_url'            => route('customer_cancellation', ['training_request_id' => $training_request_id]),

            ]);

            /* // to admin
            $user_access = UserAccess::select('et.email')
                ->leftJoin('email_tab as et', 'et.employee_id', '=', 'user_access_tab.employee_id')
                ->where([
                    'system_id'    => config('app.system_id'),
                    'user_type_id' => 2
                ])
                ->get();
        
            foreach ($user_access as $value) {
                $query = $batch_mails->save_to_batch([
                    'subject'             => 'Rescheduled Training Program',
                    'sender'              => config('mail.from.address'),
                    'recipient'           => $value->email,
                    'title'               => 'Rescheduled Training Program',
                    'training_request_id' => $training_request_id,
                    'mail_template'       => 'admin.rescheduled_training_details'
                ]);
            } */
				
			

          /*   // send to dealer
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
                    'mail_template'       => 'dealer.rescheduled_training_details'
                ]);	
            } */


          /*   // dealer sales
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
                    'mail_template'       => 'ipc.rescheduled_training_details',
                ]);	
            } */

            /* // approvers
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
                    'mail_template'       => 'approver.rescheduled_training_details'
                ]);	
            }
 */
            return response()->json($training_request);
        }
    }
}