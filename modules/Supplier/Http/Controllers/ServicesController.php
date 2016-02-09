<?php namespace Modules\Supplier\Http\Controllers;

use Modules\Supplier\Http\Requests\SupplierContactRequest;
use Modules\Supplier\Repository\ServiceRepository;
use Modules\Supplier\Service;
use Pingpong\Modules\Routing\Controller;
use Modules\Supplier\Country;

class ServicesController extends Controller {

    private $serviceRepository;

    public function __construct( ServiceRepository $serviceRepository )
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * @param $serviceClass
     * @return mixed
     */
    public function getProducts($serviceClass)
    {
        return $this->serviceRepository->getProducts($serviceClass);
    }

    /**
     * @param $serviceSlug
     * @return mixed
     */
    public function getSuppliers($serviceSlug) {
        return $this->serviceRepository->suppliersByService( $serviceSlug );
    }

    public function allWithProducts() {
        return $this->serviceRepository->getWithAllProducts( );
    }

    public function all() {
        return $this->serviceRepository->getAll( );
    }

    public function contact( SupplierContactRequest $request,$service ){
        $service->setRequest($request);
        return $service->sendEmail();
    }

    public function getCountries(){
        return Country::all();
    }
}