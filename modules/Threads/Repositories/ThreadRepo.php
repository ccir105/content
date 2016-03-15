<?php namespace Modules\Threads\Repositories;
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/6/16
 * Time: 12:59 PM
 */
use App\User;
use Modules\Project\Entities\Project;
use Modules\Threads\Entities\Thread;

class ThreadRepo
{
    protected $thread;

    protected $user;

    protected $project;

    public function __construct()
    {
        $this->thread = new Thread();
        $this->setUser();
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    public function setUser(User $user = null)
    {
        $this->user = (is_null($user)) ? \Auth::user() : $user;

        return $this;
    }

    public function save($data)
    {
        $this->thread->user_id = $this->user->id;

        $this->thread->project_id = $this->project->id;

        if(!isset($data['status']))
        {
            $data['status'] = Thread::ACTIVE_THREAD;
        }

        if(!isset($data['type']))
        {
            $data['type'] = Thread::INFORMATION;
        }

        return $this->edit($data, $this->thread);
    }

    public function edit($data, $thread)
    {
        $thread->fill($data);

        if( $thread->save() )
        {
            return $thread;
        }
    }

    public function delete( $thread )
    {
        return $thread->delete();
    }

    public function find( $thread = null )
    {
        if ( is_integer( $thread ) )
        {
            $thread = $this->thread->find( $thread );
        }

        $thread =  is_null($thread) ? false : $thread->load(['comments' => function($query)use($thread){
            $query->where('commentable_id',$thread->id);
        },'comments.user']);

        if($thread)
        {
            unset($thread->assigned_to);
        }

        return $thread;
    }

    public function get(Project $project)
    {
        $threads = $this->user->myThreads($project);

        if($this->user->hasRole( ['admin','project_manager'] ) )
        {
            $threads =  $project->threads;
        }

        return $threads->load('assignedTo')->toArray();
    }
}