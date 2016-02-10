<?php namespace Modules\Supplier\SearchStrategy;
 use Modules\Supplier\Repository\SupplierRepository;
 use Modules\Supplier\Repository\Supplier;

class ByZipCode extends SearchStrategyContract {

    public $repo;

    public function __construct()
    {
        $this->repo = new SupplierRepository();
    }

    //this assume the country id is present so the isValid is overridden

    public function isValid($request)
    {
       if( isset( $request['country_id'] ) && isset( $request['zip_code'] ) ){
            return true;
       }

        return false;
    }

    public function search( $request, $supplierIds = array() )
    {
        
    }

    public function dataKey()
    {
        return "zip_code";
    }
}