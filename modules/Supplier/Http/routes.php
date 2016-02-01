<?php

Route::group(['middleware' => 'web', 'namespace' => 'Modules\Supplier\Http\Controllers'], function()
{
	Route::get('products/{service}','ServicesController@getProducts');

	Route::get('suppliers/{serviceSlug}','ServicesController@getSuppliers');

	Route::get('/', function( ) {
		return ["name" => "Narendra" ];
	});

	Route::get('{id}', function(){
		return ["name" => "Suman"];
	});
});


