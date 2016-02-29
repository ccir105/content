<?php namespace Modules\Project\Http\Controllers;

use Illuminate\Http\Request;
use Pingpong\Modules\Routing\Controller;
use Modules\Project\Repositories\MainRepository;
use Modules\Project\Repositories\RepositoryFactory;
use Modules\Project\Http\Requests\ProjectRequest;

class MainController extends Controller {

	public $repo;

	/**
	 * MainController constructor.
	 * @param Request $request
	 * @param MainRepository $repo
     */
	public function __construct(Request $request){
		$this->repo = RepositoryFactory::make();
	}

	public function create( ProjectRequest $request ) {
		return $this->repo->save( $request->all() );
	}

	public function update( ProjectRequest $request, $model ){
		$this->repo->setInstance( $model );
		return $this->repo->save( $request->all() );
	}

	public function delete( $model ){
		return ['status' => $this->repo->setInstance( $model )->delete() ];
	}

	public function all(){
		return $this->repo->all();
	}

	public function get( $model ){
		return $this->repo->setInstance($model)->find();
	}
}