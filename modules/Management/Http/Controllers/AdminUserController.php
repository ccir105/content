<?php namespace Modules\Management\Http\Controllers;

use Modules\Management\Repositories\UserRepository;
use Pingpong\Modules\Routing\Controller;
use App\User;
use Modules\Management\Entities\Role;

class AdminUserController extends Controller {

	public $repo;

	public function __construct()
	{
		$this->repo = new UserRepository();
	}

	public function getUserList()
	{
		return $this->repo->all();
	}

	public function getUser(){
		retrn
	}

	public function getRoles()
	{
		return Role::all();
	}
}