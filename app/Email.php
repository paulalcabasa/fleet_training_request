<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = [
        'email_category_id',
        'subject',
        'sender',
        'recipient',
        'title',
        'message',
        'cc',
        'attachment',
        'redirect_url',
        'accept_url',
        'deny_url',
        'sent_at',
        'mail_template',
        'training_request_id'
    ];
    protected $primaryKey = 'email_id';
    public $timestamps = false;

    // public function email_category()
    // {
    //     return $this->belongsTo();
    // }
}
