<?php

Route::group(['middleware' =>['api'/*,'jwt.auth','my.auth'*/], 'prefix' => 'api', 'namespace' => 'Modules\UserManagement\Http\Controllers'], function()
{
	Route::group(['prefix' => 'admin','middleware' => ['role:admin']],function() {
		Route::get('user','UserManagementController@all');
		Route::get('roles', 'UserManagementController@getRoles');
		Route::get('user/{user}','UserManagementController@find');
		Route::post('user','UserManagementController@create');
		Route::post('user/{user}','UserManagementController@update');
		Route::delete('user/{user}','UserManagementController@delete');
		Route::get('user/{user}/assign-role/{role}','UserManagementController@assignRole');
		Route::get('user/{user}/revoke-role/{role}','UserManagementController@revokeRole');
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

Route::bind('role',function($roleId)
{
	if($role = \Modules\UserManagement\Entities\Role::find($roleId))
	{
		return $role;
	}

	throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
});
