<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramFeature extends Model
{
    protected $fillable = ['training_program_id','feature'];
    protected $primaryKey = 'program_feature_id';
    public $timestamps = false;

    public function training_program()
    {
        return $this->belongsTo('App\TrainingProgram', 'training_program_id', 'training_program_id');
    }

}
