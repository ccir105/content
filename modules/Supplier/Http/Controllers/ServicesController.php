<?php namespace Modules\Supplier\Http\Controllers;

use Modules\Supplier\Repository\ServiceRepository;
use Pingpong\Modules\Routing\Controller;

class ServicesController extends Controller {

    private $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function getProducts($serviceClass)
    {
        return $this->serviceRepository->getProducts($serviceClass);
    }

    public function getSuppliers($serviceSlug){
        return $this->serviceRepository->suppliersByService($serviceSlug);
    }

}