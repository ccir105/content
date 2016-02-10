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

        $zipCode = null;

        if( isset( $request[ 'zip_code' ] ) ){
            $zipCode = $request['zip_code'];
        }

        $suppliers = $this->repo->findByCountry($countryId , $supplierIds ,$zipCode);

        return $suppliers;
    }

    public function isValid($request)
    {
        if( isset( $request['country_id'] ) || isset( $request['zip_code'] ) ){
            return true;
        }

        return false;
    }

    public function dataKey(){
        return "country_id";
    }
}