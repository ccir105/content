<?php namespace Modules\Project\Http\Controllers;

use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Pingpong\Modules\Routing\Controller;
use Modules\Project\Repositories\MainRepository;
use Modules\Project\Repositories\RepositoryFactory;
use Modules\Project\Http\Requests\ProjectRequest;

class MainController extends Controller {

	use CrudTrait;

	/**
	 * MainController constructor.
	 * @param Request $request
	 * @param MainRepository $repo
     */

	public function __construct(){
		$model = RepositoryFactory::make();
		$this->setInstance($model);
	}

	public function create( ProjectRequest $request ) {
		return $this->save( $request->all() );
	}

	public function update( ProjectRequest $request, $model ){
		$this->setInstance( $model );
		return $this->save( $request->all() );
	}

	public function delete( $model ){
		return ['status' => $this->setInstance( $model )->delete() ];
	}
}