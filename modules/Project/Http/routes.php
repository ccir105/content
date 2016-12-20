<?php

Route::group(['middleware' => ['api','jwt.auth','my.auth'], 'prefix' => 'api', 'namespace' => 'Modules\Project\Http\Controllers'], function()
{
	/**
	 * Normal Auth Request
	 *
	 */

	Route::group(['middleware' => ['auth','user.belongs_project']],function()
	{
		Route::get('project/{project}/page/{page}','PageController@find');

		Route::get('project/{project}','ProjectController@find');

		Route::get('projects','ClientController@myProject');
	});


	Route::group(['prefix' => 'project','middleware' => ['role:admin|project_manager','user.belongs_project']],function()
	{
		Route::post('{project}','ProjectController@update');
		Route::get('{project}/assign/{user}','ProjectController@assignProject');
		Route::get('{project}/revoke/{user}','ProjectController@revokeProject');

		Route::post('{project}/page','PageController@create');
		Route::post('{project}/page/{page}','PageController@update');
		Route::delete('{project}/page/{page}','PageController@remove');

		Route::post('{project}/page/{page}/field-group','FieldGroupController@create');
		Route::post('{project}/page/{page}/field-group/{field_group}','FieldGroupController@update');
		Route::delete('{project}/field-group/{field_group}','FieldGroupController@remove');


		Route::post('{project}/page/{page}/field','FieldController@create');
		Route::post('{project}/page/{page}/field/{field}','FieldController@update');
		Route::delete('{project}/field/{field}','FieldController@remove');
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
