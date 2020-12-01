<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserExperience extends Model
{
    protected $table='user_experiences';
    
    protected $fillable = ['user_id','company_name', 'from', 'to','salary','reason'];

    public function users()
    {
    	$this->belongsTo('App\User');
    }

    public function setFromAttribute($value)
    {   
        if(!empty($value))
        {
            $this->attributes['from'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
        }
        else
        {
            $this->attributes['from'] = NULL;
        }
    }

    public function setToAttribute($value)
    {   
        if(!empty($value))
        {
            $this->attributes['to'] =\Carbon\Carbon::parse($value)->format('Y-m-d');
        }
        else
        {
            $this->attributes['to'] = NULL;
        }
    }

    public function getFromAttribute($value)
    {
         return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }
    public function getToAttribute($value)
    {
         return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }
}
