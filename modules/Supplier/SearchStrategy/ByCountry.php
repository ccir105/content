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
        $countryId = $this->getData($request);

        $suppliers = $this->repo->findByCountry($countryId , $supplierIds );

        return $suppliers;
    }

    public function dataKey(){
        return "country_id";
    }
}