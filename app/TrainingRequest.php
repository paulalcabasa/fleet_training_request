<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingRequest extends Model
{
    protected $fillable = [
        'company_name',
        'office_address',
        'contact_person',
        'email',
        'position',
        'contact_number',
        'training_date',
        'training_venue',
        'training_address',
        'training_program_id', // Training Program
        'unit_model_id', // Focus of training
        'training_time',
        'remarks'
    ];
    protected $primaryKey = 'training_request_id';
    public $timestamps = false;

    public function training_request_programs(){
        return $this->hasMany('App\TrainingRequestPrograms', 'training_request_id', 'training_request_id');
    }

    public function unit_model()
    {
        return $this->belongsTo('App\UnitModel', 'unit_model_id', 'unit_model_id');
    }

    public function email()
    {
        return $this->belongsTo('App\Email', 'training_request_id', 'training_request_id');
    }

    public function approval_statuses()
    {
        return $this->hasMany('App\ApprovalStatus', 'training_request_id', 'training_request_id');
    }

    public function customer_dealers()
    {
        return $this->hasMany('App\CustomerDealer', 'training_request_id', 'training_request_id');
    }

    public function customer_models()
    {
        return $this->hasMany('App\CustomerModel', 'training_request_id', 'training_request_id');
    }

    public function customer_participants()
    {
        return $this->hasMany('App\CustomerParticipant', 'training_request_id', 'training_request_id');
    }

    public function trainor_designations()
    {
        return $this->hasMany('App\DesignatedTrainor', 'training_request_id', 'training_request_id');
    }

    public function dealer_details()
    {
        return $this->hasOne('App\DealerDetail');
    }
}