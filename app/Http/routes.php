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

Route::group(['middleware' => ['web']], function(){
	Route::controllers([
		'password' => 'Auth\PasswordController'
	]);

	Route::post('reset/login', 'MainController@loginAfterReset');
});

Route::group(['middleware' => ['api']], function () {

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

		Route::post('advice', 'MainController@saveAdvice');

		Route::get('advice','MainController@myAdvice');

		Route::get('global','MainController@globalAdvice');

		Route::get('me/advices','MainController@myAdviceByPrority');

		Route::post('global/{global}','MainController@addToMyAdvice');

		Route::post('settings/{settingKey}','MainController@changeUserSettings');

		Route::post('update/{advice}/pending','MainController@updatePending');
	});
});

Route::bind('advice', function($id){
	if($advice = App\Advice::find($id)){
		return $advice;
	}
	else{
		throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
	}
});

Route::bind('global',function($adviceId){
	if($advice = App\Advice::where('type',1)->where('id',$adviceId)->first())
	{
		return $advice;
	}
	else
	{
		throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
	}
});

Route::bind('settingKey', function($value)
{
	if( preg_match('/^' .implode('|', App\User::getFillables() ) . '$/', $value))
	{
		return $value;
	}
	else
	{
		throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
	}
});