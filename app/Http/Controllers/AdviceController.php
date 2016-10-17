<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\AddNewAdvice;
use App\Advice;
use Auth;
use Res;

class AdviceController extends Controller
{
	public $repo;

    public $user;

	public function __construct()
	{
		$this->repo = app('AdviceRepo');

        $this->user = Auth::user();
	}

    public function save( AddNewAdvice $request, $advice = null )
    {        
        $eqAdvice = is_null( $advice ) ? Advice::setUserId(4) : null;

    	$this->repo->setEloquent($eqAdvice);

    	return $this->repo->save( $request->all() , $advice );
    }

    public function delete( Advice $advice )
    {
    	return $this->repo->delete( $advice ) ? Res::success([],'Deleted') : Res::fail([],'Error');
    }

    public function get()
    {
      $data = $this->repo->paginate(20)->toArray();
      return $data['data'];
    }

    public function getByPriority()
    {
        return $this->repo->getGroupedByPriority(4);
    }
}
