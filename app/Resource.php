<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['date','member_id','teamlead_id','work_load'];

    public function user(){
    	return $this->belongsTo('App\User','member_id','id');
    }
    public function teamlead(){
    	return $this->belongsTo('App\User','teamlead_id','id');
    }
    public function getDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }
}
