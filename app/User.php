<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    use EntrustUserTrait;



    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['type'];

    public function getTypeAttribute(){

    }
    {
        return $this->exists;
    }

    public function setPasswordAttribute($value){
        $this->attributes['password'] = \Hash::make($value);
    }

    public function projects(){
        return $this->belongsToMany('Modules\Project\Entities\Project', 'projects_users','user_id');
    }

    public function threads()
    {
        return $this->hasMany('Modules\Threads\Entities\Thread','user_id','id');
    }

    public function isMyThread( $thread)
    {
        $ids = $this->threads->lists('id')->toArray();
        return in_array($thread->id, $ids);
    }

    public function assignedToMe()
    {
        return $this->belongsToMany('Modules\Threads\Entities\Thread','assigned_threads','assigned_to');
    }

    public function assignedByMe()
    {
        return $this->belongsToMany('Modules\Threads\Entities\Thread','assigned_threads','assigned_by');
    }

    public function myThreads( $project )
    {
        $myThreads = $this->threads;
        $assignedThreads = $this->assignedToMe()->where('project_id',$project->id)->get();
        return $myThreads->merge($assignedThreads);
    }
}
