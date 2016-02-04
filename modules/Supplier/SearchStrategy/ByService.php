<?php namespace Modules\Supplier\SearchStrategy;
use Modules\Supplier\Repository\ServiceRepository;

class ByService extends SearchStrategyContract {

    public $repo;

    public function __construct()
    {
        $this->repo = new ServiceRepository;
    }

    public function search( $request, $vendorIds = array() )
    {
        $service = $this->getData($request);

        return $this->repo->getByService($service);
    }

    public function dataKey()
    {
       return "service";
    }
}