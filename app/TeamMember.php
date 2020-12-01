<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    
    protected $fillable = [
	    'teamlead_id',
		'member_id',
		'department_id'
    ];
    public function member(){
    	return $this->belongsTo('App\User','member_id');
    }
    public function teamlead(){
    	return $this->belongsTo('App\User','teamlead_id');
    }
    public function department(){
    	return $this->belongsTo('App\Department','department_id');
    }

    // public function teaml(){
    //     return $this->belongsTo('App\Department','team_members','department_id');
    // }

}
