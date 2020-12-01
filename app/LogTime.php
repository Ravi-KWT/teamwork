<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogTime extends Model
{
    protected $table='log_times';

    public $timestamps = true;

    protected $fillable = ['user_id','task_id','project_id','date','start_time','end_time','hour','minute','description','billable'];

    public function setUserIdAttribute($value)
    {
        if(!empty($value))
        {
            $this->attributes['user_id'] = $value;
        }
        else
        {
            $this->attributes['user_id'] = Auth::user()->id;
        }
    }
    public function setDateAttribute($value)
    {
        if(!empty($value))
        {
            $this->attributes['date'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
        }
        else
        {
            $this->attributes['date'] = NULL;
        }
    }

    public function getDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }

    public function getStartTimeAttribute($value)
    {
        // $value=explode(':',$value);
        if ($value != null) {
            return \Carbon\Carbon::parse($value)->format('h:i A');
        }


    }
    public function getEndTimeAttribute($value)
    {
        // $value=explode(':',$value);
        if ($value != null) {
            return \Carbon\Carbon::parse($value)->format('h:i A');
        }

    }


    public function task()
    {
        return $this->belongsTo('App\Task');
    }
    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
