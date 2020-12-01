<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Auth;

class Task extends Model
{

	use Sluggable;

   	public $timestamps = true;

    public function setStartDateAttribute($value)
    {
        if(!empty($value))
        {
            $this->attributes['start_date'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
        }
        else
        {

            $this->attributes['start_date'] = \Carbon\Carbon::now();
        }
    }

    public function setDueDateAttribute($value)
    {
        if(!empty($value))
        {
            $this->attributes['due_date'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
        }
        else
        {
            $this->attributes['due_date'] = \Carbon\Carbon::now();
        }
    }
    // public function getStartDateAttribute($value)
    // {
    //     return \Carbon\Carbon::parse($value)->format('d-m-Y');
    // }
    //  public function getDueDateAttribute($value)
    // {
    //     return \Carbon\Carbon::parse($value)->format('d-m-Y');
    // }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'separator' => '-',
                'onUpdate' => true
            ]
        ];
    }

    protected $fillable = ['name','category_id','project_id','notes','start_date','due_date','priority','completed','assignedby'];



    public function category()
    {
        return $this->belongsTo('App\TaskCategory');
    }

   public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function users()
    {
        return $this->belongsToMany('App\User','task_users','task_id','user_id');
    }
    public function logtimes()
    {
        return $this->hasMany('App\LogTime')->orderBy('date','desc');
    }


    public function timers()
    {
        return $this->hasMany('App\Timer','task_id')->where('user_id',Auth::user()->id);
    }



}
