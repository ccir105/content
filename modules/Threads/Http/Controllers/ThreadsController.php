<?php namespace Modules\Threads\Http\Controllers;

use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Modules\Project\Entities\Project;
use Modules\Threads\Entities\Thread;
use Modules\Threads\Http\Requests\ThreadRequest;
use Modules\Threads\Policy\ThreadControl;
use Modules\Threads\Policy\ThreadViewPolicy;
use Modules\Threads\Repositories\ThreadRepo;
use Pingpong\Modules\Routing\Controller;
use Res;
use Modules\Threads\Policy\ThreadPolicyFactory as PolicyFactory;

class ThreadsController extends Controller {

	protected $user;

	protected $repo;

	protected $policy;

	public function __construct(Guard $auth,ThreadRepo $repo)
	{
		$this->user = $auth->user();
		$this->repo = $repo;
	}

	public function create(ThreadRequest $request,Project $project)
	{

		$this->repo->setProject($project);

		return Res::success($this->repo->save($request->all()));

	}

	public function edit(ThreadRequest $request, Project $project, Thread $thread)
	{
		return Res::success($this->repo->edit($request->all(),$thread));
	}

	public function delete(Request $request, Project $project, Thread $thread)
	{
		$status = PolicyFactory::make($request,new ThreadControl());

		if($status)
		{
			return Res::success($this->repo->delete($thread));
		}

		return Res::fail("Not Authorized");
	}

	public function get(Request $request, Project $project)
	{
		if( PolicyFactory::make($request) )
		{
			$this->repo->setUser($this->user);

			return Res::success($this->repo->get($project));
		}

		return Res::fail("Not Authorized");
	}

	public function find(Request $request, Project $project, Thread $thread)
	{
		$threadPolicy = new ThreadViewPolicy();

		$threadPolicy->setThread($thread);

		if(PolicyFactory::make($request,$threadPolicy  ) )
		{
			$thread = $this->repo->find($thread)->toArray();

			unset($thread['assigned_to']);

			return Res::success($thread);
		}

		return Res::fail('Not Authorized');
	}

	public function assign(Request $request, Project $project, Thread $thread, User $user)
	{
		if( $project->belongs($user) )
		{
			$status = '';

			if( is_null( $thread->assignedTo()->where('assigned_to',$user->id)->first() ) )
			{
				$status = $thread->assignedTo()->attach( $user, ['assigned_by' => $this->user->id ]);
			}

			return Res::success($status,'Success Fully Assigned');
		}

		return Res::fail("","Project Does Not Belongs To User",FAIL);
	}

	public function revoke(Request $request, Project $project, Thread $thread, User $user)
	{
		if( $project->belongs($user) )
		{
			$status = $thread->assignedTo()->detach( $user->id );

			return Res::success($status,'Successfully Revoked');
		}

		return Res::fail("","Project Does Not Belongs To User",FAIL);
	}
}