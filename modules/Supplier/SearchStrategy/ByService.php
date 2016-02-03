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
        $service = $request['service'];

        return $this->repo->getByService($service);
    }
}