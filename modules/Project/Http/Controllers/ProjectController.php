<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 2/29/16
 * Time: 11:42 AM
 */

namespace Modules\Project\Http\Controllers;
use App\Http\Requests\Request;
use Modules\Project\Entities\Project;
use Modules\Project\Http\Requests\ProjectRequest;
use Pingpong\Modules\Routing\Controller;
use App\Traits\CrudTrait;

class ProjectController extends Controller
{
    use CrudTrait;

    public function __construct(Project $project)
    {
        $this->setInstance($project);
    }

    public function assignProject($project, $user){
        return ['status' => $project->assign($user) ];
    }

    public function revokeProject($project, $user){
        return ['status' => $project->revoke($user) ];
    }

    public function update( ProjectRequest $request, $model ){
        $this->setInstance( $model );
        return $this->save( $request->all() );
    }

    public function remove( $model ){
        return ['status' => $this->setInstance( $model )->delete() ];
    }

    public function create(ProjectRequest $request){
        return $this->save($request->all());
    }
}
