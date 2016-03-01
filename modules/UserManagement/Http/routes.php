<?php

Route::group(['middleware' => 'web', 'prefix' => 'api', 'namespace' => 'Modules\UserManagement\Http\Controllers'], function()
{
	Route::group(['prefix' => 'admin'],function() {
		Route::get('user','UserManagementController@all');
		Route::get('roles', 'UserManagementController@getRoles');
		Route::get('user/{user}','UserManagementController@find');
		Route::post('user','UserManagementController@create');
		Route::post('user/{user}','UserManagementController@update');
		Route::delete('user/{user}','UserManagementController@delete');
	});
});

Route::bind('user',function($userId)
{
	if($user = App\User::find($userId))
	{
		return $user;
	}
	else
	{
		throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
	}
});