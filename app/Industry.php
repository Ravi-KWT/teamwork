<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Industry extends Model
{
    public $timestamps = true;

    protected $table='industries';

    protected $fillable = ['name'];

    protected $sluggable = [
        'build_from' => 'name',
        'save_to'    => 'slug',
        'separator'  => '-',
        'on_update'  => true,
    ];

    public function companies()
    {
        return $this->hasMany('App\Company','industry_id');
    }
}
