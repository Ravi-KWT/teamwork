<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class TaskCategory extends Model
{
   use Sluggable;

   	public $timestamps = true;

    protected $fillable = ['name','project_id'];

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

    public function tasks()
	{
		return $this->hasMany('App\Task','category_id');
	}

    public function project()
    {
        return $this->belongsTo('App\Project');
    }
    
}
