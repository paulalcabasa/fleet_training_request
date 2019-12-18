<?php

namespace App\Console\Commands;

use App\Email;
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
                $bar->setFormat('debug');
                $bar->setProgressCharacter('|');
				$mail = $this->mail->send([
					'email_category_id' => $value['email_category_id'],
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
						'redirect_url' => $value['redirect_url']
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