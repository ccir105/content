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
//*/
//Auth::loginUsingId(App\User::find(45)->id); //admin
//Auth::loginUsingId(App\User::find(42)->id); //client
Auth::loginUsingId(App\User::find(48)->id); //client
//Auth::loginUsingId(App\User::find(298)->id); //manager
//Auth::loginUsingId(App\User::find(300)->id); //manager
//Auth::loginUsingId(App\User::find(299)->id); //developer
//268 project thread  403

Route::get('/', function () {
    return view('welcome');
});

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

Route::group(['middleware' => ['web']], function () {
    //
});

Route::get('test',function(){
    $u = new App\Elastic\User();
    $u->name = "Hello";
    $u->email = "asda@asd.com";
    $u->save();
});