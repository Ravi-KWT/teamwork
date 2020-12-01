<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    public $table = 'user_tokens';
    public $timestamps = true;

    protected $fillable = ['user_id','fcm_token'];

    public function user(){
    	return $this->belongsTo('App\User','user_id');
    }
}
