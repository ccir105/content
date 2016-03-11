<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 3/6/16
 * Time: 1:19 PM
 */

namespace Modules\Threads\Policy;


use App\User;
use Modules\Project\Entities\Project;
use Modules\Threads\Entities\Thread;
use Modules\UserManagement\Entities\Permission;

abstract class Policy
{
    protected $successor = null;

    static public $user;

    static public $project;

    protected $thread;

    final public function setSuccessor($handler)
    {
        if ($this->successor === null)
        {
            $this->successor = $handler;
        } else
        {
            $this->successor->setSuccessor($handler);
        }
    }

    final public function handle($request)
    {
        $response = $this->process($request);

        if ( ( $response === null ) && ( $this->successor !== null ) )
        {
            $response = $this->successor->handle($request);
        }

        return $response;
    }

    abstract protected function process( $request );

    public static function setUser(User $user)
    {
        self::$user = $user;
    }

    public static function setProject(Project $project)
    {
        self::$project = $project;
    }

    public function belongs()
    {
        return $this->project->belongs($this->user);
    }

    public function isManager()
    {
        return self::$user->hasRole('project_manager');
    }

    public function isClient()
    {
        return self::$user->hasRole('client');
    }

    public function isDeveloper()
    {
        return self::$user->hasRole('developer');
    }

    public function setThread( Thread $thread )
    {
        $this->thread = $thread;
    }
}