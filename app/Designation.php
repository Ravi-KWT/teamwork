<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Designation extends Model
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
        $this->hasMany('App\People');
    }

    protected $fillable = ['name'];
}
