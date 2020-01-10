<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingRequestPrograms extends Model
{
    protected $fillable = [
        'training_request_id',
        'training_program_id'
    ];
    protected $primaryKey = 'program_id';
    public $timestamps = false;

    public function training_program()
    {
        return $this->belongsTo('App\TrainingProgram', 'training_program_id', 'training_program_id');
    }

    public function program_features()
    {
        return $this->belongsTo('App\ProgramFeature', 'training_program_id', 'training_program_id');
    }
    
}
