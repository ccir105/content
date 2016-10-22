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

	public function __construct(Request $request)
    {
        $this->user = Auth::user();

        if( $request->route('advice') && Auth::user()->id != $request->route('advice')->user_id )
        {
            return Res::fail([],'This resource doesn\'t belongs to you');
        }

        $this->repo = app('AdviceRepo');

    }

    public function save( AddNewAdvice $request, $advice = null )
    {        

        $eqAdvice = is_null( $advice ) ? Advice::setUserId($this->user->id) : null;

    	$this->repo->setEloquent($eqAdvice);

    	return $this->repo->save( $request->all() , $advice );
    }

    public function delete( Advice $advice )
    {
    	return $this->repo->delete( $advice ) ? Res::success([],'Deleted') : Res::fail([],'Error');
    }

    public function get()
    {
      $data = $this->repo->paginate($this->user->id , 20)->toArray();
      return $data['data'];
    }

    public function getByPriority()
    {
        $advices = $this->repo->getGroupedByPriority($this->user->id);
        return count($advices) == 0 ? '{}' : $advices;
    }
}
