<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalStatus extends Model
{
    protected $fillable = ['training_request_id','approver_id'];
    protected $primaryKey = 'approval_status_id';
    public $timestamps = false;

    public function training_request()
    {
        return $this->belongsTo('App\TrainingRequest', 'training_request_id', 'training_request_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\Person', 'person_id', 'person_id');
    }
}
