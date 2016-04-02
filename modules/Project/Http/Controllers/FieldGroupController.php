<?php namespace Modules\Project\Http\Controllers;

use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Modules\Project\Entities\FieldGroup;
use Pingpong\Modules\Routing\Controller;
use Modules\Project\Repositories\MainRepository;
use Modules\Project\Http\Requests\ProjectRequest;
use Res;

class FieldGroupController extends Controller {

	use CrudTrait;

	/**
	 * MainController constructor.
	 * @param Request $request
	 * @param MainRepository $repo
     */

	public function __construct()
	{
		$this->model = new FieldGroup();
	}

	public function create( ProjectRequest $request ,$project, $page )
	{
		$request->merge(['page_id'=>$page->id]);

		$this->model->fill($request->all());

		return Res::success($this->save( $request->all() ));

	}

	public function update( ProjectRequest $request, $project, $page, $model)
	{
		$request->merge(['page_id'=>$page->id]);

		$this->setInstance( $model );

		return Res::success( $this->save( $request->all() ) );
	}

	public function remove($project,$fieldGroup)
	{
		return $this->delete($fieldGroup);
	}
}