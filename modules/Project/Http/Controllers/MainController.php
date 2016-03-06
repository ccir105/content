<?php namespace Modules\Project\Http\Controllers;

use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Pingpong\Modules\Routing\Controller;
use Modules\Project\Repositories\MainRepository;
use Modules\Project\Repositories\RepositoryFactory;
use Modules\Project\Http\Requests\ProjectRequest;
use Res;

class MainController extends Controller {

	use CrudTrait;

	protected $repoModel;

	/**
	 * MainController constructor.
	 * @param Request $request
	 * @param MainRepository $repo
     */

	public function __construct()
	{
		$model = RepositoryFactory::make();
		$this->repoModel = $model;
		$this->setInstance($model);
	}

	public function create( ProjectRequest $request )
	{
		$this->model->fill($request->all());
		if( $this->belongs($this->repoModel,\Auth::user()))
		{
			return Res::success($this->save( $request->all() ));
		}
	}

	public function update( ProjectRequest $request, $model )
	{
		if( $this->belongs($model,\Auth::user()))
		{
			$this->setInstance( $model );
			return Res::success( $this->save( $request->all() ) );
		}
	}
}