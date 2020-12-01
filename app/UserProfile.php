<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table='user_profile';
    
    protected $fillable = ['user_id'];

    public function users()
    {
    	$this->belongsTo('App\User','user_id');
    }
    public function project_lead(){
    	return $this->belongsTo('App\Project');
    }
}
