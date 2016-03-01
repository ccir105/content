<?php namespace Modules\UserManagement\Http\Controllers;
use App\Traits\CrudTrait;
use App\User;
use Modules\UserManagement\Entities\Role;
use Modules\UserManagement\Repositories\UserRepository;
use Modules\UserManagement\Http\Requests\UserRequest;
use Pingpong\Modules\Routing\Controller;

class UserManagementController extends Controller {

	use CrudTrait;

	public function __construct(User $user)
	{
		$this->setInstance($user);
	}

	public function all(){
		return $this->getInstance()->with('roles')->get();
	}

	public function getRoles(){
		return Role::all();
	}

	public function update( UserRequest $request, User $user ){
		$repo = new UserRepository();
		return $this->find($repo->update($user, $request->all()));
	}

	public function create( UserRequest $request){
		$repo = new UserRepository();
		return $this->find($repo->create($request->all()));
	}

	public function find(User $user){
		return $user->load('roles');
	}

}