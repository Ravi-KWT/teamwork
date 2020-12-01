<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MilestoneUser extends Model
{
     public $timestamps = true;

    protected $table='milestone_users';

    protected $fillable = ['milestone_id','user_id'];
}
