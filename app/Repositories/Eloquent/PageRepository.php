<?php namespace App\Repositories\Eloquent;

use App\User;

use App\Page;

class PageRepository
{
	public function save( $input, $model = null)
	{
		if( is_null($model) )
		{
			$model = new Page;

			$model->user_id = $input['user_id'];
		}
		
		$model->fill($input);
		
		$model->save();
		
		return $model->fresh();
	}

	public function remove($page)
	{
		return $page->delete();
	}

	public function getUserPages($user)
	{
		return $user->myPages;
	}	

	public function find($id)
	{
		return Page::find($id);
	}
}