<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    public $timestamps = true;

    protected $table='task_users';

    protected $fillable = ['task_id','user_id'];
}
