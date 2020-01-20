<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\BatchMails;
class CreateMailNotice extends Command
{

    protected $batch_mail;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notice:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Two day notice email of training details before training';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BatchMails $batch_mail)
    {
        parent::__construct();
       
        $this->batch_mail = $batch_mail;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $sql = "SELECT tr.training_request_id,
                    tr.training_date,
                    tr.email,
                    (DATEDIFF(tr.training_date,NOW())) training_date_age
                FROM  training_requests tr
                WHERE 1 = 1
                    AND tr.status = 'confirmed'
                GROUP BY tr.training_request_id,
                    tr.training_date
                HAVING training_date_age = 2";
        $lists = DB::select($sql);

        $bar = $this->output->createProgressBar(count($lists));
        // send to requestor
        foreach($lists as $row){
            
            $bar->setFormat('debug');
            $bar->setProgressCharacter('|');

            $this->batch_mail->save_to_batch([
                'email_category_id'   => null,
                'subject'             => 'NOTICE OF TRAINING DETAILS',
                'sender'              => config('mail.from.address'),
                'recipient'           => $row->email,
                'training_request_id' => $row->training_request_id,
                'mail_template'       => 'customer.training_details',
                'title'               => 'NOTICE OF TRAINING DETAILS',
                'message'             => '',
                'cc'                  => null,
                'attachment'          => null
            ]);

            
            $bar->advance();
        }

        $bar->finish();
    }
}
