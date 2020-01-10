<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Services\FetchMailConfig;

class SendEmail
{
    public function __construct(FetchMailConfig $fetch_mail_config)
    {
        $mail_credentials = $fetch_mail_config->get_mail_credentials('Fleet Training Request');

        if ($mail_credentials) {
            config([
                'mail.username' => $mail_credentials->email,
                'mail.password' => $mail_credentials->email_password,
            ]);
        }
    }

    public function send($params)
    {
        $data = [
            'mail_template' => $params['mail_template'],
            'subject'	     => $params['subject'],
            'sender'	     => $params['sender'],
            'recipient'	     => $params['recipient'],
            'cc'	         => $params['cc'],
            'attachment'	 => $params['attachment'],
            'redirect_url'	 => $params['redirect_url'],
            'content'	     => $params['content']
        ];

 /*        if ($data['email_category_id'] == '2') $template = 'superior_email_template';
        else if ($data['email_category_id'] == '5') $template = 'email_template';
        else if ($data['email_category_id'] == '6') $template = 'requestor_email';
        else if ($data['email_category_id'] == '7') $template = 'default_email';
        else $template = 'default_email'; */

        return Mail::send('emails.' . $data['mail_template'], ['content' => $data['content']], function ($mail) use ($data) {
            $mail->from($data['sender'], 'Fleet Training Request System');
            $mail->to($data['recipient'])->subject($data['subject']);
            
            if (isset($data['cc'])) $mail->cc($data['cc']);
            if (isset($data['attachment'])) $mail->attach($data['attachment']);
        });
    }
}