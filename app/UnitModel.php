<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitModel extends Model
{
    protected $fillable = ['model_name','description','image','sequence_no'];
    protected $primaryKey = 'unit_model_id';
    public $timestamps = false;
}
