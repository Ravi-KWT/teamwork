<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTheme extends Model
{
    protected $table='user_theme';

    public $timestamps = false;
    
    protected $fillable = ['user_id','class'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
