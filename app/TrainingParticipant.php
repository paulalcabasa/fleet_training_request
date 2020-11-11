<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingParticipant extends Model
{
    protected $fillable = ['first_name','middle_name','last_name','result','remarks','updated_at'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
