<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    protected $table='timers';
    public $timestamps = true;
    protected $fillable = ['user_id','project_id','task_id','running','billable','last_started_at','completed'];
    public function user()
    {
    	return $this->belongsTo('App\User','user_id');
    }
    public function intervals()
    {
    	return $this->hasMany('App\Interval','id');
    }

    public function task()
    {
        return $this->belongsTo('App\Task','task_id');
    }
}
