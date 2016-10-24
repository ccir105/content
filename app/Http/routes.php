<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function()
{
	Route::controllers([
		'password' => 'Auth\PasswordController'
	]);

	Route::post('reset/validate', 'MainController@validateResetToken');

	Route::post('reset/login', 'MainController@loginAfterReset');
});

Route::group(['middleware' => ['api']], function ()
{
	Route::controllers([
		'auth' => 'Auth\AuthController',
	]);
	

	Route::post('email/unique','MainController@isUniqueEmail');

	Route::group(['middleware' => ['my.auth']],function()
	{
		Route::get('me',function(){
			if( Auth::check())
			{
				return Auth::user();
			}
			return Res::fail([],'Failed');
		});
		
		Route::get('advice', 'AdviceController@get');

		Route::get('advice/grouped','AdviceController@getByPriority');

		Route::post('advice/{advice?}','AdviceController@save');

		Route::delete('advice/{advice}','AdviceController@delete');

		Route::post('me/{settingKey}/update','HomeController@updateMe');

		Route::get('page', 'PageController@getMine');

		Route::delete('page/{page}','PageController@remove');

		Route::post('page/{page?}','PageController@save');
	});
});

Route::bind('advice', function($id)
{
	if($advice = App\Advice::find($id))
	{
		return $advice;
	}
	else{
		throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
	}
});

Route::bind('page',function($id)
{
	$repo = app('PageRepo');

	if( $page = $repo->find( $id ) )
	{
		return $page;
	}
	else
	{
		throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
	}
});



Route::bind('settingKey', function($value)
{
	if( in_array( $value, App\User::getFillables() ) )
	{
		return $value;
	}
	else
	{
		throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
	}
});