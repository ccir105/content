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

    public function update( ProjectRequest $request, $project, $page)
    {
        $request->merge(['project_id' => $project->id]);
        $this->setInstance( $page );
        return Res::success($this->save( $request->all() ));
    }

    public function remove($project, $page )
    {
        return $this->delete($page);
    }

    public function create(ProjectRequest $request, $project)
    {
        $request->merge(['project_id' => $project->id ]);
        return Res::success($this->save($request->all()));
    }

    public function find(Guard $auth, $project, $page )
    {
        return Res::success($page->getEntities());
    }
}