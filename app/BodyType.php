<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BodyType extends Model
{
    protected $fillable = ['name','status','created_by','created_at','updated_by','updated_at'];
    protected $primaryKey = 'body_type_id';
    public $timestamps = false;

    public function unit_model()
    {
        return $this->belongsTo('App\UnitModel', 'unit_model_id', 'unit_model_id');
    }

}
