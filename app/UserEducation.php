<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model
{
    protected $table='user_educations';
    
    protected $fillable = ['user_id','qualification', 'college', 'university','passing_year','percentage'];

    public function users()
    {
    	$this->belongsTo('App\User','user_id');
    }
}
