<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DesignatedTrainor extends Model
{
    protected $fillable = ['training_request_id','assigned_by', 'person_id'];
    protected $primaryKey = 'trainor_designation_id';
    public $timestamps = false;

    public function training_request()
    {
        return $this->belongsTo('App\TrainingRequest', 'training_request_id', 'training_request_id');
    }

    public function person()
    {
        return $this->belongsTo('App\Person', 'person_id', 'person_id');
    }
}
