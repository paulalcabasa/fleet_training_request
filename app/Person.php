<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
	protected $fillable = [
        'person_type',
        'email',
        'position',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'position',
        'prefix',
        'status'
    ];
	protected $primaryKey = 'person_id';
    public $timestamps = false;
    protected $table = "persons";

    public function designated_trainors()
    {
        return $this->hasMany('App\DesignatedTrainor', 'person_id', 'person_id');
    }
	
}
