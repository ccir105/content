<?php

Route::group(['middleware' => 'web', 'namespace' => 'Modules\Supplier\Http\Controllers'], function()
{
	Route::get('products/{service}','ServicesController@getProducts');

	Route::get('suppliers/{serviceSlug}','ServicesController@getService');

	Route::post('auth/login','AuthController@postLogin');

//	Route::get('search','SearchController@searchVendors');

});

Route::group(['middleware' => ['web','api.request','jwt.auth'], 'namespace' => 'Modules\Supplier\Http\Controllers'], function (){
	Route::post('supplier','SupplierController@postSupplier');
	Route::post('supplier/{supplier}','SupplierController@putSupplier');
	Route::delete('supplier/{supplier}','SupplierController@delete');
	Route::get('supplier/search','SupplierController@searchSupplier');
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