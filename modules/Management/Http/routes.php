<?php

Route::group(['middleware' => 'web', 'prefix' => 'api', 'namespace' => 'Modules\Management\Http\Controllers'], function()
{
	Route::group(['prefix' => 'admin'],function() {
		Route::get('user','AdminUserController@getUserList');
		Route::get('roles', 'AdminUserController@getRoles');
		Route::get('user/{user}','AdminUserController@getUser');
		Route::post('user','AdminUserController@newUser');
		Route::post('user/{user}','AdminUserController@editUser');
		Route::delete('user/{user}','AdminUserController@deleteUser');
	});
});