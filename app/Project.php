<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Project extends Model
{
 	use Sluggable;

   	public $timestamps = true;
    
    protected $fillable = ['name','category_id','client_id','description','price_types','notes','status','start_date','end_date','fix_hours','projectlead_id'];

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

    public function setStartDateAttribute($value)
    {   
        if(!empty($value))
        {
           

            $this->attributes['start_date'] = $value;       
            
        }
        else
        {
            $this->attributes['start_date'] = NULL;
        }
    }

    public function setEndDateAttribute($value)
    {   
        if(!empty($value))
        {
            $this->attributes['end_date'] = $value;
        }
        else
        {
            $this->attributes['end_date'] = NULL;
        }
    }

    public function getCreatedAtAttribute($value)    {        
        $value = date('U', strtotime($value));        
        return $value * 1000;    
    }     
    // public function getUpdatedAtAttribute($value)    {        
    //     $value = date('U', strtotime($value));        
    //     return $value * 1000;    
    // }


    
    


 //    public function category()
	// {
	// 	return $this->belongsTo('ProjectCategory','category_id');
	// }

 //    public function users()
 //    {
 //        return $this->hasMany('User','user_id');
 //    }

    public function projectManager(){
        return $this->hasOne('App\UserProfile','user_id','projectlead_id');
    }
    public function users()
    {
        return $this->belongsToMany('App\User','project_users','project_id','user_id');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }


    public function milestones()
    {
        return $this->hasMany('App\Milestone');
    }

    public function logs()
    {
        return $this->hasMany('App\LogTime');
    }


    public function latestLogs()
    {
        return $this->hasOne('App\LogTime')->latest();
    }

    public function company()
    {
        return $this->belongsTo('App\Company','client_id','id');
    }

    public function category()
    {
        return $this->belongsTo('App\ProjectCategory','category_id','id');
    }
  
    

  // }
  //   public function tasks(){
  //       return $this->hasMany('Task','project_id');
  //   }
}
