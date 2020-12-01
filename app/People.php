<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Image;	
use Event;
use App\Department;

class People extends Model
{
	protected $table='user_profile';
	protected $fillable = ['fname','lname','mobile','gender','marital_status','dob','phone','join_date','adrs1','adrs2','city','state','country','zipcode','pan_number','department_id','designation_id','management_level','google','facebook','website','skype','linkedin','twitter','photo'];
	public function setDobAttribute($value)
    {   
        if(!empty($value))
        {
            $this->attributes['dob'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
        }
        else
        {
            $this->attributes['dob'] = NULL;
        }
    }


    public function setJoinDateAttribute($value)
    {   
        if(!empty($value))
        {
            $this->attributes['join_date'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
        }
        else
        {
            $this->attributes['join_date'] = NULL;
        }
    }

    public function getNameAttribute()
    {
        return ucwords(preg_replace('/\s+/', ' ',$this->fname.' '.$this->lname));
    }
    
    public function getDobAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }
    
    public function getJoinDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }

    
   

	
	public function user()
    {
        return $this->belongsTo('App\User');
    }
	// public function user()
	// {
	// 	return $this->belongsTo('User', 'user_id');
	// }


    public function setPhotoAttribute($file) {

        $source_path = upload_tmp_path($file);
        
        if ($file && file_exists($source_path)) 
        {
            upload_move($file,'people');
            Image::make($source_path)->resize(420, 498)->save($source_path);
            upload_move($file,'people','medium');
            Image::make($source_path)->resize(175, 130)->save($source_path);
            upload_move($file,'people','thumb');
             // @unlink($source_path);
            // $this->deleteFile();
        }
        $this->attributes['photo'] = $file;
            if ($file == '') 
            {
                $this->deleteFile();
                $this->attributes['photo'] = NULL;
            }
    }
    public function uploadImage($file){
        
         $source_path = upload_tmp_path($file);
        
        if ($file && file_exists($source_path)) 
        {
            upload_move($file,'people');
            Image::make($source_path)->resize(420, 498)->save($source_path);
            upload_move($file,'people','medium');
            Image::make($source_path)->resize(175, 130)->save($source_path);
            upload_move($file,'people','thumb');
             // @unlink($source_path);
            // $this->deleteFile();
        }
        $this->attributes['photo'] = $file;
            if ($file == '') 
            {
                $this->deleteFile();
                $this->attributes['photo'] = NULL;
            }
    }

	
	public function photo_url($type='original') 
	{
        if (!empty($this->photo) && file_exists(upload_tmp_path($this->photo)))
            return upload_url($this->photo,'people',$type);
        else
            return asset('img/user.png');
        
		// if (!empty($this->photo))
		// 	return upload_url($this->photo,'people',$type);
		// elseif (!empty($this->photo) && file_exists(tmp_path($this->photo)))
		// 	return tmp_url($this->photo);
		// else
		// 	return asset('img/user-profile.png');
	}
	public function deleteFile() 
	{
		upload_delete($this->photo,'people',array('original','thumb','medium'));
	}

	public function department()
    {
        return $this->belongsTo('App\Department','department_id');
    }
    public function designation()
    {
        return $this->belongsTo('App\Designation','designation_id');
    }
    public function projects()
    {
        return $this->hasMany('App\Project');
    }

}
	Event::listen('eloquent.deleting:People', function($model) {
		$model->deleteFile();
	});
