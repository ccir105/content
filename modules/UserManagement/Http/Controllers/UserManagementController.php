<?php namespace Modules\UserManagement\Http\Controllers;
use App\Traits\CrudTrait;
use App\User;
use Modules\UserManagement\Entities\Role;
use Modules\UserManagement\Repositories\UserRepository;
use Modules\UserManagement\Http\Requests\UserRequest;
use Pingpong\Modules\Routing\Controller;
use Res;
class UserManagementController extends Controller {

	use CrudTrait;

	public function __construct(User $user)
	{
		$this->setInstance($user);
	}

	public function all(){
		return Res::success( $this->getInstance()->with('roles')->get() );
	}

	public function getRoles(){
		return Res::success(Role::all());
	}

	public function update( UserRequest $request, User $user ){
		$repo = new UserRepository();
		return Res::success($this->find($repo->update($user, $request->all())));
	}

	public function create( UserRequest $request){
		$repo = new UserRepository();
		return Res::success($this->find($repo->create($request->all())));
	}

	public function find(User $user){
		return Res::success($user->load('roles'));
	}

	public function assignRole(User $user,$role)
	{
		if( $user->hasRole($role->name) )
		{
			return Res::fail([],'User Already Assigned',400);
		}
		return Res::success( $user->attachRole($role) );
	}

	public function revokeRole(User $user,$role)
	{
		if( !$user->hasRole($role->name) )
		{
			return Res::fail([],'User Doesnt have this role',400);
		}
		return Res::success( $user->detachRole($role));
	}
}