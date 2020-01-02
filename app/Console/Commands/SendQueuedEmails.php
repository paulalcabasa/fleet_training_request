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
                        'trainor_designations.person'
                    ])
                    ->first();

                $contact_person    = $training_request['contact_person'];
                $company_name      = $training_request['company_name'];
                $participants      = $training_request['customer_participants'];
                $training_programs = $training_request['training_request_programs'];
                $venue             = $training_request['training_venue'];
                $unit_model        = $training_request['unit_model']->model_name;
                $date              = $training_request['training_date'];
                $time              = $training_request['training_time'];
                $trainors          = $training_request['trainor_designations'];
                $date = Carbon::parse($date)->format('F d, Y');
                $bar->setFormat('debug');
                $bar->setProgressCharacter('|');
				$mail = $this->mail->send([
					'mail_template' => $value['mail_template'],
					'subject'           => $value['subject'],
					'sender'            => config('mail.from.address'),
					'recipient'         => $value['recipient'],
					'cc'                => $value['cc'],
					'attachment'        => $value['attachment'],
					'content'           => [
						'title'        => $value['title'],
						'message'      => $value['message'],
						'accept_url'   => $value['accept_url'],
						'deny_url'     => $value['deny_url'],
                        'redirect_url' => $value['redirect_url'],
                        'training_request' => [
                            'contact_person'    => $contact_person,
                            'company_name'      => $company_name,
                            'participants'      => $participants,
                            'training_programs' => $training_programs,
                            'venue'             => $venue,
                            'unit_model'        => $unit_model,
                            'date'              => $date,
                            'time'              => $time,
                            'trainors'          => $trainors,
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