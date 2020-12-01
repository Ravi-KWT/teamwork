<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interval extends Model
{
    protected $table='intervals';

    public $timestamps = true;
    public function timer()
    {
    	return $this->belongsTo('App\Timer','timer_id');
    }
}
