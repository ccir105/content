<?php namespace Modules\Supplier\SearchStrategy;
use Modules\Supplier\Repository\SupplierRepository;

class ByCountry extends SearchStrategyContract {

    public $repo;

    public function __construct()
    {
        $this->repo = new SupplierRepository();
    }

    public function search( $request, $supplierIds = array() )
    {
        $countryId = $request['country_id'];

        $suppliers = $this->repo->findByCountry($countryId , $supplierIds );

        return $suppliers;
    }
}