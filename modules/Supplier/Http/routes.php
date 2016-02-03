<?php

Route::group(['middleware' => 'web', 'namespace' => 'Modules\Supplier\Http\Controllers'], function()
{
	Route::get('products/{service}','ServicesController@getProducts');

	Route::get('suppliers/{serviceSlug}','ServicesController@getService');

//	Route::get('search','SearchController@searchVendors');

	Route::get('/', function( ) {
		return ["name" => "Narendra"];
	});

	Route::get('{id}', function(){
		return ["name" => "Suman"];
	});
});


