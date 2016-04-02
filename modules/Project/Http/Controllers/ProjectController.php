<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 2/29/16
 * Time: 11:42 AM
 */

namespace Modules\Project\Http\Controllers;
use App\Http\Requests\Request;
use Illuminate\Contracts\Auth\Guard;
use Modules\Project\Entities\Project;
use Modules\Project\Http\Requests\ProjectRequest;
use Pingpong\Modules\Routing\Controller;
use App\Traits\CrudTrait;
use App\User;
use Res;

class ProjectController extends Controller
{
    use CrudTrait;

    protected $user;

    public function __construct(Project $project,Guard $auth)
    {
        $this->setInstance($project);
        $this->user = $auth->user();
    }

    public function assignProject($project, $user)
    {
        return Res::success( $project->assign($user) );
    }

    public function revokeProject($project, $user)
    {
        return Res::success( $project->revoke($user));
    }

    public function update( ProjectRequest $request, $model )
    {
        $this->setInstance( $model );
        return Res::success($this->save( $request->all() ));
    }

    public function create( ProjectRequest $request )
    {
        $project = $this->getInstance();

        $project->user_id = $this->user->id;

        $this->setInstance( $project );

        return Res::success($this->save($request->all()));
    }

    public function getByUser(User $user)
    {
        return Res::success($user->projects);
    }

    public function find(Project $project)
    {
        return Res::success($project->load('pages'));
    }

    public function createFromExisting(Project $project)
    {
        return Res::success($project->duplicate());
    }

    public function all(){

        $projects = $this->model->with('assignedUsers')->get();

        return Res::success($projects);
    }
}
