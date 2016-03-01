<?php

Auth::loginUsingId(App\User::orderByRaw("RAND()")->first()->id);

Route::group(['middleware' => 'web', 'prefix' => 'api', 'namespace' => 'Modules\Project\Http\Controllers'], function()
{
	Route::group(['prefix' => 'page'],function(){
		Route::post('/','PageController@create');
		Route::post('{page}','PageController@update');
		Route::delete('{page}','PageController@delete');
		Route::get('/', 'PageController@all');
		Route::get('{page}','PageController@find');
	});

	Route::group(['prefix' => 'field-group'],function(){
		Route::post('/','MainController@create');
		Route::post('{field_group}','MainController@update');
		Route::delete('{field_group}','MainController@delete');
		Route::get('/', 'MainController@all');
		Route::get('{field_group}','MainController@get');
	});

	Route::group(['prefix' => 'field'],function(){
		Route::post('/','MainController@create');
		Route::post('{field}','MainController@update');
		Route::delete('{field}','MainController@delete');
		Route::get('/', 'MainController@all');
		Route::get('{field}','MainController@get');
	});


	Route::group(['prefix' => 'project'],function(){
		Route::post('/','ProjectController@create');
		Route::post('{project}','ProjectController@update');
		Route::delete('{project}','ProjectController@delete');
		Route::get('/', 'ProjectController@all');
		Route::get('{project}','ProjectController@find');
		Route::get('{project}/assign/{user}','ProjectController@assignProject');
		Route::get('{project}/revoke/{user}','ProjectController@revokeProject');
	});

});

Route::bind('page',function($pageId){

	if($page = Modules\Project\Entities\Page::find($pageId))
	{
		return $page;
	}
	else
	{
		throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
	}
});


Route::bind('field_group',function($fieldGroupId){

	if($fieldGroup = Modules\Project\Entities\FieldGroup::find($fieldGroupId))
	{
		return $fieldGroup;
	}
	else
	{
		throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
	}
});

Route::bind('field',function($fieldId){

	if($field = Modules\Project\Entities\FieldValue::find($fieldId))
	{
		return $field;
	}
	else
	{
		throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
	}
});


Route::bind('project',function($projectId){

	if($project = Modules\Project\Entities\Project::find($projectId))
	{
		return $project;
	}
	else
	{
		throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
	}
});
