<?php namespace Modules\Supplier\FinderStrategy;

use Illuminate\Http\Request;
use Modules\Supplier\Repository\ServiceRepository;
use Modules\Supplier\Repository\SupplierRepository;

abstract class SupplierFinderContract{

    protected $next;

    public static $supplierIds;

    public static $request;

    public static $repo;

    abstract public function find();

    public function setSupplierIds($ids = array()){
        $this::setSupplierIds = $ids;
    }

    public function setRequest(Request $request){
        $this::request = $request;
    }

    public function setNext(SupplierFinderContract $nextStategy){
        $this->next = $nextStategy;
    }

    public function setServiceRepository(SupplierRepository $repo){
        $this::repo = $repo;
    }
}
?>