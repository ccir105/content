<?php namespace Modules\Supplier\SearchStrategy;
use Modules\Supplier\Repository\SupplierRepository;

class ByProducts extends SearchStrategyContract {

    public $repo;

    public function __construct()
    {
        $this->repo = new SupplierRepository();
    }

    public function search( $request, $supplierIds = array() )
    {

        $products = $this->getData($request);
        if(!is_array($products))
            $products = [$products];

        $suppliers = $this->repo->searchByProducts( $products, $supplierIds );
       
        return $suppliers;
    }

    public function dataKey()
    {
        return "product";
    }
}