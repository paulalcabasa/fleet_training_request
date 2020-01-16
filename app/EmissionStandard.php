<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmissionStandard extends Model
{
    protected $fillable = ['name','status','created_by','created_at','updated_by','updated_at'];
    protected $primaryKey = 'emission_standard_id';
    public $timestamps = false;
}
