<?php

Route::group(['middleware' => ['api','jwt.auth','my.auth'], 'prefix' => 'api', 'namespace' => 'Modules\Project\Http\Controllers'], function()
{
	/**
	 * Normal Auth Request
	 *
	 */

	Route::group(['middleware' => 'auth'],function()
	{
		Route::group(['prefix' => 'page'],function()
		{
			Route::get('{page}','PageController@find');
		});

		Route::get('project/{project}','ProjectController@find');

		Route::get('projects','ClientController@myProject');
	});


	Route::group(['middleware' => ['role:admin|project_manager']],function()
	{
		//page crud
		Route::group(['prefix' => 'page'],function()
		{
			Route::post('/','PageController@create');
			Route::post('{page}','PageController@update');
			Route::delete('{page}','PageController@delete');
		});

		//field group crud
		Route::group(['prefix' => 'field-group'],function()
		{
			Route::post('/','MainController@create');
			Route::post('{field_group}','MainController@update');
			Route::delete('{field_group}','MainController@delete');
			Route::get('{field_group}','MainController@find');
		});

		Route::group(['prefix' => 'field'],function()
		{
			Route::post('/','MainController@create');
			Route::post('{field}','MainController@update');
			Route::delete('{field}','MainController@delete');
			Route::get('{field}','MainController@find');
		});

		Route::group(['prefix' => 'project'],function(){
			Route::post('{project}','ProjectController@update');
			Route::get('{project}/assign/{user}','ProjectController@assignProject');
			Route::get('{project}/revoke/{user}','ProjectController@revokeProject');
		});
	});

	Route::group(['middleware'=>['role:admin']], function()
	{
		Route::group(['prefix' => 'project'],function()
		{
			Route::get('/','ProjectController@all');
			Route::get('create-from/{project}','ProjectController@createFromExisting');
			Route::post('/','ProjectController@create');
			Route::delete('{project}','ProjectController@delete');
			Route::get('user/{user}','ProjectController@getByUser');
		});
	});



	Route::post('page/{page}/save',['middleware' => ['role:client'],'uses'=>'ClientController@saveForm']);


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
