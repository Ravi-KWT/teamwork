<?php

namespace App;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','roles','is_teamlead',
    ];

 
    // public function people()
    // {
    //     return $this->hasOne('People','user_id'); //Profile is your profile model
    // } 
    // public function people()
    // {
    //     return $this->belongsTo('People', 'user_id');
    // }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tasks()
    {
        return $this->belongsToMany('App\Task','task_users','task_id','user_id');
    }
    public function milestones()
    {
        return $this->belongsToMany('App\Milestone','milestone_users','milestone_id','user_id');
    }
    public function projects()
    {
        return $this->belongsToMany('App\Project','project_users','user_id','project_id');
    }
    
    public function userEducations()
    {
        $this->hasMany('App\UserEducation');
    } 

    public function userProfile()
    {
        $this->hasOne('App\UserProfile');
    } 

    public function people()
    {
        return $this->hasOne('App\People','user_id');
    }

    public function theme()
    {
        return $this->hasOne('App\UserTheme','user_id');
    }

    
    public function members(){
        return $this->hasMany('App\User','team_members','member_id','id');
    }

    public function departments(){
        return $this->hasMany('App\Department','team_members','department_id','id');   
    }
    public function team_member(){
        return $this->hasOne('App\TeamMember','member_id');
    }
    public function workLoads(){
        return $this->hasMany('App\Resource','member_id');
    }
    
    public function user_tokens(){
        return $this->hasMany('App\UserToken','id');
    }

    public function timers()
    {
        return $this->hasMany('App\Timer','user_id');
    }

    // public function sendPasswordResetNotification($token)
    // {
    //     $token = Str::random(60);
    //     $this->notify(new ResetPasswordNotification($token));
    // }
}
