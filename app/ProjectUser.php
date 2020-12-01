<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    public $timestamps = true;

    protected $table='project_users';

    protected $fillable = ['project_id','user_id'];
}
