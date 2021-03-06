<?php

namespace App\Console\Commands;

use App\Email;
use App\UserAccess;
use App\ApprovalStatus;
use App\TrainingRequest;
use App\TrainingProgram;
use App\ProgramFeatures;
use App\Services\BatchMails;
use App\DealerDetail;
use App\Services\SendEmail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class SendQueuedEmails extends Command
{
    protected $mail;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SendEmail $mail)
    {
        parent::__construct();
        $this->mail = $mail;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pending_emails = Email::where('sent_at', NULL)->get();
        //$pending_emails = Email::where('email_id', 194)->get();
        $bar = $this->output->createProgressBar(count($pending_emails));
		if ($pending_emails) {
            foreach ($pending_emails as $value) {
               
                $training_request = TrainingRequest::where('training_request_id', $value['training_request_id'])
                    ->with([
                        'customer_dealers',
                        'customer_models',
                        'customer_participants',
                        'training_request_programs.training_program',
                        'training_request_programs.program_features',
                        'unit_model',
                        'trainor_designations.person',
                        'body_type',
                        'emission_standard'
                    ])
                    ->first();
                
                
                
                $contact_person     = $training_request['contact_person'];
                $company_name       = $training_request['company_name'];
                $participants       = $training_request['customer_participants'];
                $training_programs  = $training_request['training_request_programs'];
                $venue              = $training_request['training_venue'];
                $training_address   = $training_request['training_address'];
                $additional_request = $training_request['remarks'];
                $unit_model         = $training_request['unit_model']->model_name;
                
                $body_type = "";
                if($training_request['body_type'] != ""){
                    $body_type     = $training_request['body_type']->name;
                }

                $emission_standard = "";
                if($training_request['emission_standard'] != ""){
                    $emission_standard = $training_request['emission_standard']->name;
                }
            
                $date              = $training_request['training_date'];
                $time              = $training_request['training_time'];
                $trainors          = $training_request['trainor_designations'];
                $cancellation_remarks = $training_request['cancellation_remarks'];
                $contact_number = $training_request['contact_number'];
                $denied_aprovers = [];

                if($value['mail_template'] == 'admin.denied_request'){
                    $denied_aprovers = DB::table('approval_statuses AS at')
                        ->leftJoin('persons AS ps','ps.person_id','=','at.person_id')
                        ->where([
                            'at.training_request_id' => $value['training_request_id'],
                            'at.status' => 'denied'
                        ])
                        ->get();
                }

                $date = Carbon::parse($date)->format('F d, Y');
                $bar->setFormat('debug');
                $bar->setProgressCharacter('|');
				$mail = $this->mail->send([
					'mail_template' => $value['mail_template'],
					'subject'       => $value['subject'],
					'sender'        => config('mail.from.address'),
					'recipient'     => $value['recipient'],
                    'cc'            => $value['cc'],
                    'bcc'           => 'paul-alcabasa@isuzuphil.com',
					'attachment'    => $value['attachment'],
					'content'       => [
						'title'        => $value['title'],
						'message'      => $value['message'],
						'accept_url'   => $value['accept_url'],
						'deny_url'     => $value['deny_url'],
                        'redirect_url' => $value['redirect_url'],
                        'training_request' => [
                            'contact_person'       => $contact_person,
                            'company_name'         => $company_name,
                            'participants'         => $participants,
                            'training_programs'    => $training_programs,
                            'venue'                => $venue,
                            'unit_model'           => $unit_model,
                            'body_type'            => $body_type,
                            'emission_standard'    => $emission_standard,
                            'date'                 => $date,
                            'time'                 => $time,
                            'trainors'             => $trainors,
                            'denied_aprovers'      => $denied_aprovers,
                            'cancellation_remarks' => $cancellation_remarks,
                            'contact_number'       => $contact_number,
                            'training_address'     => $training_address,
                            'additional_request'   => $additional_request
                        ]
                    ],
                    'redirect_url' => $value['redirect_url']
				]);

                 $query = Email::findOrFail($value['email_id']);
                $query->sent_at = new \DateTime();
                $query->save();

                $bar->advance();
            }

            $bar->finish();
            return $this->info('All Emails Successfully Sent!');
        }
    }
}