<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitModelEmissionType extends Model
{
    protected $fillable = ['unit_model_id','unit_model_emission_types'];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function emission_standard()
    {
        return $this->belongsTo('App\EmissionStandard', 'emission_standard_id', 'emission_standard_id');
    }
}
