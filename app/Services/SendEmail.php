<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Services\FetchMailConfig;
use PHPMailer\PHPMailer\PHPMailer;
class SendEmail
{

 //   private $mail_credentials;
    
    public function __construct(FetchMailConfig $fetch_mail_config)
    {
      //  $this->$mail_credentials = $fetch_mail_config->get_mail_credentials('Fleet Training Request');
      /*   if ($mail_credentials) {
            config([
                'mail.username' => $mail_credentials->email,
                'mail.password' => $mail_credentials->email_password,
            ]);
        } */
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

        $mail = new PHPMailer();                            // Passing `true` enables exceptions
        
        $fetch_mail_config = new FetchMailConfig;
        $mail_credentials = $fetch_mail_config->get_mail_credentials('Fleet Training Request');

        try {
            
        
            // Server settings
            $mail->SMTPDebug = 0;                                	// Enable verbose debug output
            $mail->isSMTP();       
            $mail->CharSet    = "iso-8859-1";                      // Set mailer to use SMTP
            $mail->Host       = 'smtp.office365.com';              // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                              // Enable SMTP authentication
            $mail->Username   = $mail_credentials->email;           // SMTP username
            $mail->Password   = $mail_credentials->email_password;  // SMTP password
            $mail->SMTPSecure = 'tls';                             // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                               // TCP port to connect to

            //Recipients
            $mail->setFrom($mail_credentials->email, 'System Notification');
            $mail->addAddress($data['recipient']);            
            $mail->addBCC('paul-alcabasa@isuzuphil.com');

           // if (isset($data['cc'])) $mail->cc($data['cc']);
            if (isset($data['attachment'])) {
                $mail->addAttachment($data['attachment']);
            }
            $mail->isHTML(true); 																	// Set email format to HTML
            $mail->Subject = 'System Notification : ' . $data['subject'];
            $mail->Body    = view("emails." . $data['mail_template'] , $data); // . $row->email_address;
            //$mail->AddEmbeddedImage(config('app.project_root') . 'public/images/isuzu-logo.png', 'isuzu_logo');
            
            return $mail->send();
            
        } catch (Exception $e) {
            \Log::info("Mail not sent" . $e);
            return false;
        }


       /*  return Mail::send('emails.' . $data['mail_template'], ['content' => $data['content']], function ($mail) use ($data) {
            $mail->from($data['sender'], 'Fleet Training Request System');
            $mail->to($data['recipient'])->subject("System notification : " . $data['subject']);
            
            if (isset($data['cc'])) $mail->cc($data['cc']);
            if (isset($data['attachment'])) $mail->attach($data['attachment']);
        }); */
    }
}