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
use Modules\Project\Entities\Page;
use Modules\Project\Entities\Project;
use Modules\Project\Http\Requests\ProjectRequest;
use Pingpong\Modules\Routing\Controller;
use App\Traits\CrudTrait;
use Res;

class PageController extends Controller
{
    use CrudTrait;

    public function __construct(Page $page)
    {
        $this->setInstance($page);
    }

    public function update( ProjectRequest $request, $model )
    {
        $project = Project::find($request->get('project_id'));

        if($this->belongs( $project, \Auth::user() ) )
        {
            $this->setInstance( $model );
            return Res::success($this->save( $request->all() ));
        }

    }

    public function remove(Guard $auth, $model )
    {
        if( $this->belongs( $auth, $model ) )
        {
            return Res::success($this->setInstance( $model )->delete());
        }
    }

    public function create(ProjectRequest $request)
    {
        if( $request->has('project_id') )
        {
            $project = Project::find($request->get('project_id'));

            if($this->belongs($project,\Auth::user()))
            {
                return Res::success($this->save($request->all()));
            }
        }
    }

    public function find(Guard $auth, $model )
    {
        if( $this->belongs( $model, $auth->user() ) ){
            return Res::success($model->getEntities());
        }
    }
}
