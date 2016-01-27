<?php

Route::group(['middleware' => 'web', 'prefix' => 'supplier', 'namespace' => 'Modules\Supplier\Http\Controllers'], function()
{
	Route::get('/', function( ) {
		return ["name" => "Narendra" ];
	});

	Route::get('{id}', function(){
		return ["name" => "Suman"];
	});
});