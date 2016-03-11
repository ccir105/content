<?php namespace Modules\Threads\Entities;
   
use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Project\Entities\BelongsContract;

class Thread extends Model implements BelongsContract{

    protected $fillable = ['status','type','description','title'];

    const BUG = 'bug';
    const INFORMATION = 'information';

    const ACTIVE_THREAD = '1';
    const PENDING_THREAD = '2';
    const COMPLETED_THREAD = '3';

    static public $status = [
        self::ACTIVE_THREAD => "Active",
        self::PENDING_THREAD => "Pending",
        self::COMPLETED_THREAD => "Completed",
    ];

    static public $type = [
        self::BUG => "Bug",
        self::INFORMATION => "Information",
    ];

    public function getStatusAttribute($value)
    {
        return self::$status[$value];
    }
    public function project()
    {
        return $this->belongsTo('Modules\Project\Entities\Project','project_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function comments()
    {
        return $this->morphMany('App\Comment','commentable');
    }

    public function assignedTo()
    {
        return $this->belongsToMany('App\User','assigned_threads','thread_id','assigned_to')->withTimestamps();
    }

    public function resources()
    {
        return $this->morphMany('App\Resource','imagable');
    }

    public function belongs(User $user)
    {
        if( $this->project->belongs($user) )
        {
            return true;
        }
    }

    public function isAssigned(User $user)
    {
        $userIds = $this->assignedTo->lists('id')->toArray();

        return in_array($user->id , $userIds);
    }
}