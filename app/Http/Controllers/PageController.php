<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Page;
use App\User;

use Res;

class PageController extends Controller
{
    protected $user;

	protected $repo;
	
	public function __construct()
	{
		$this->user = Auth::user();

        $this->repo = app('PageRepo');
	}

    public function mergeUser($input)
    {
        return array_merge($input, ['user_id' => $this->user->id ]);
    }

    public function save(Requests\PageRequest $request, $page = null)
    {
    	$page = is_null($page) ? null : $page;

    	if( $page = $this->repo->save($this->mergeUser( $request->all() ), $page) )
    	{
    		return $page;
    	}

    	return Res::fail([], 'Failed to save');
    }

    public function remove(Page $page)
    {
    	$res = $this->repo->remove( $page ) ? Res::success([],'deleted successfully') : Res::fail();

    	return $res;
    }

    public function getMine()
    {
    	return $this->repo->getUserPages( $this->user );
    }
}
