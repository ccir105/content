<?php

Route::group(['middleware' => ['api','jwt.auth','my.auth'], 'prefix' => 'api', 'namespace' => 'Modules\Threads\Http\Controllers'], function()
{
		/**
		 * This route can be called from admin, client, and manager
		 * Admin user can access all the routes
		 */

		/**
		 * Client can create threads on assigned project
		 * Manager can create threads easily on his assigned project
		 * Developer needs permission to create threads
		 */

		Route::post('project/{project}/thread','ThreadsController@create');

		/**
		 * Client can edit his threads
		 * Manager can edit all the threads in his assigned project
		 * Developer can edit his threads
		 * He can also edit other developers threads based on the permission
		 */

		Route::post('project/{project}/thread/{thread}','ThreadsController@edit');

		/**
		 * Can only be deleted by project mangaer and client and admin
		 * Client can be delete his threads,
		 * but manager can delete all the threads
		 * which is created by developers and other project manager
		 */

		Route::delete('project/{project}/thread/{thread}','ThreadsController@delete');

		/**
		 * This route can be all type user
		 * Manger can see all the threads
		 * Developer can see only his assigned threads
		 * Client Can see his threads and his assigned threads
		 */
		Route::get('project/{project}/thread','ThreadsController@get');

		Route::get('project/{project}/thread/{thread}','ThreadsController@find');

		/**
		 * A manager can comment in all threads
		 * A client can post in this assigned threads, and his own threads
		 */

		Route::post('project/{project}/thread/{thread}/comment','CommentController@create');

		/**
		 * A manager can edit other comments
		 * A client can edit his comments only
		 * A developer can also edit hist comments only
		 * No no, he can aslo edit other commetns if he has permission
		 */

		Route::post('project/{project}/thread/{thread}/comment/{comment}','CommentController@edit');

		/**
		 * A manager can delete comments of developers
		 * A developer which has permsission to delete other comments he can delete them
		 * A own created comments can be deleted by the all user
		 */

		Route::delete('project/{project}/thread/{thread}/comment/{comment}','CommentController@delete');

		Route::get('project/{project}/thread/{thread}/assign/{user}',['middleware'=>'thread.view','uses'=>'ThreadsController@assign']);

		Route::get('project/{project}/thread/{thread}/revoke/{user}',['middleware'=>'thread.view','uses'=>'ThreadsController@revoke']);
});


Route::bind('thread',function($pageId){

	if($page = Modules\Threads\Entities\Thread::find($pageId))
	{
		return $page;
	}
	else
	{
		throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
	}
});