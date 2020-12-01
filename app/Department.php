<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Department extends Model
{
	use Sluggable;

   	public $timestamps = true;

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


    public function peoples()
    {
       return $this->hasMany('App\People','department_id');
    }

    public function teamMembers(){
        return $this->hasMany('App\TeamMember','department_id','id');
    }
    public function teamHead(){
        return $this->hasOne('App\TeamMember','department_id');
    }

    
    protected $fillable = ['name'];
}
