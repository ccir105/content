<?php namespace Modules\Supplier\Http\Controllers;

use Modules\Supplier\Repository\ServiceRepository;
use Modules\Supplier\Repository\SupplierRepository;
use Pingpong\Modules\Routing\Controller;
use Illuminate\Http\Request;

class SupplierController extends Controller {

	private $supplierRepository;

	public function __construct( SupplierRepository $supplierRepository)
	{
		$this->supplierRepository = $supplierRepository;
	}

	public function search( Request $request ){

		$searchBuilder = new SearchBuilder( $request->all() );
		
		if( $request->has('service') ){
			$searchBuilder->add( new ByService() );
		}

		if( $request->has('products') ){
			$searchBuilder->add( new ByProducts );
		}

		return $searchBuilder->getResults();
	}
}