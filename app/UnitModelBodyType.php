<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitModelBodyType extends Model
{
    protected $fillable = ['unit_model_id','body_type_id'];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function body_type()
    {
        return $this->belongsTo('App\BodyType', 'body_type_id', 'body_type_id');
    }
}
