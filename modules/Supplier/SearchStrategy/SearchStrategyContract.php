<?php namespace Modules\Supplier\SearchStrategy;

use Modules\Supplier\Repository\ServiceRepository;
use Modules\Supplier\Repository\SupplierRepository;

abstract class SearchStrategyContract {
    abstract public function search( $request, $vendorIds = array() );

    public function isValid($request){
        return isset( $request[ $this->dataKey() ] ) ? true : false;
    }

    public function getData($request){
        return $request[$this->dataKey()];
    }

    abstract public function dataKey();
}