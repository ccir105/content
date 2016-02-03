<?php namespace Modules\Supplier\SearchStrategy;

use Modules\Supplier\Repository\ServiceRepository;
use Modules\Supplier\Repository\SupplierRepository;

abstract class SearchStrategyContract {
    abstract public function search( $request, $vendorIds = array() );
}