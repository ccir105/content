<?php

Route::group(['middleware' => 'web', 'namespace' => 'Modules\Supplier\Http\Controllers'], function()
{
	Route::get('products/{service}','ServicesController@getProducts');

	Route::get('suppliers/{serviceSlug}','ServicesController@getProducts');

	Route::post('auth/login','AuthController@postLogin');

	Route::get('services/products','ServicesController@allWithProducts');
	
	Route::get('services','ServicesController@all');

	Route::get('search','SearchController@initSearch');
});



Route::group(['middleware' => ['web','api.request','jwt.auth'], 'namespace' => 'Modules\Supplier\Http\Controllers'], function (){
	Route::post('supplier','SupplierController@postSupplier');
	Route::post('supplier/{supplier}','SupplierController@putSupplier');
	Route::delete('supplier/{supplier}','SupplierController@delete');
	Route::get('supplier/search','SupplierController@searchSupplier');
	Route::post('image/upload','SupplierController@uploadProfile');

});

Route::bind('supplier',function($supplier) {
	$supplier = Modules\Supplier\Supplier::find( $supplier );
	if( !is_null( $supplier ) ){
		return $supplier;
	}else {
		throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
	}
});

Route::get('test',function(){
	return Modules\Supplier\Product::withSomething(['1','2']);
});

