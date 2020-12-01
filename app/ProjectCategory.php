<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class ProjectCategory extends Model
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

    protected $fillable = ['name'];

   public function projects()
    {
        return $this->hasMany('App\Project','category_id','id');
    }


}
