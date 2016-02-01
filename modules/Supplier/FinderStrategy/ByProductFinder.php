<?php namespace Modules\Supplier\FinderStrategy;

class ByProductFinder extends SupplierFinderContract{

    public function find() {
        $request = $this::$request;
        if( $request->has('products') ){
            $suppliersIds = $this->repo->findByProductBySuppliers( $request->get('products'),$this::$supplierIds );
            if( $suppliersIds){
                $this->setSupplierIds($suppliersIds);
            }
        }

        return $this->next->find();
    }
}