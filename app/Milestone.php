<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Image;	
use Event;

class Milestone extends Model 
{

	public $timestamps = true;

	protected $fillable = ['name','description','notes','due_date','project_id','reminder'];

    public function setDueDateAttribute($value)
    {   
        if(!empty($value))
        {
            $this->attributes['due_date'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
        }
        else
        {
            $this->attributes['due_date'] = NULL;
        }
    }
    public function getDueDateAttribute($value)
    {
         $value = date('U', strtotime($value));
        return $value * 1000;
        // return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }

	public function project()
    {
        return $this->belongsToMany('App\Project');
    }
    public function users()
    {
        return $this->belongsToMany('App\User','milestone_users','milestone_id','user_id');
    }
    public function people()
    {
        return $this->belongsToMany('App\People','milestone_users','milestone_id','user_id');
    }

    public function getCreatedAtAttribute($value)
    {
        $value = date('U', strtotime($value));
        return $value * 1000;
    }
 
    public function getUpdatedAtAttribute($value)
    {
        $value = date('U', strtotime($value));
        return $value * 1000;
    }


}
