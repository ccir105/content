<?php namespace Modules\Project\Http\Controllers;

use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Modules\Project\Entities\FieldGroup;
use Modules\Project\Entities\FieldValue;
use Pingpong\Modules\Routing\Controller;
use Modules\Project\Repositories\MainRepository;
use Modules\Project\Http\Requests\FieldRequest;
use Res;

class FieldController extends Controller {

    use CrudTrait;

    /**
     * MainController constructor.
     * @param Request $request
     * @param MainRepository $repo
     */

    public function __construct()
    {
        $this->model = new FieldValue();
    }

    public function create( FieldRequest $request ,$project, $page )
    {
        if(!$request->get('group_id'))
        {
            $request->merge(['page_id'=>$page->id]);
        }

        $this->model->fill($request->all());

        return Res::success($this->save( $request->all() ));

    }

    public function update( FieldRequest $request, $project, $page, $field)
    {
        if(!$request->get('group_id'))
        {
            $request->merge(['page_id'=>$page->id]);
        }

        $this->setInstance( $field );

        return Res::success( $this->save( $request->all() ) );
    }

    public function remove( $project, $field)
    {
        return $this->delete($field);
    }
}