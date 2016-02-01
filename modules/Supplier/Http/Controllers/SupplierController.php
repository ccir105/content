<?php namespace Modules\Supplier\Http\Controllers;

use Modules\Supplier\Repository\ServiceRepository;
use Modules\Supplier\Repository\SupplierRepository;
use Pingpong\Modules\Routing\Controller;

class SupplierController extends Controller {

	private $supplierRepository;

	public function __construct(SupplierRepository $supplierRepository)
	{
		$this->supplierRepository = $supplierRepository;
	}
}