<?php
/**
 * Created by PhpStorm.
 * User: sishir
 * Date: 2/4/16
 * Time: 10:55 AM
 */

namespace Modules\Supplier\Http\Controllers;
use ClassPreloader\Config;
use Illuminate\Http\Request;
use Modules\Supplier\SearchStrategy\ByCountry;
use Modules\Supplier\SearchStrategy\ByProducts;
use Modules\Supplier\SearchStrategy\ByService;
use Modules\Supplier\SearchStrategy\SearchBuilder;
use Pingpong\Modules\Routing\Controller;

class SearchController extends Controller
{
    public $searchStrategys = [];

    public $builder;

    public $request;

    public function __construct(Request $request){
        $this->request = $request->toArray();
        $this->builder = new SearchBuilder($this->request);
    }

    public function registerSearch(){

        $this->searchStrategys = [
            new ByService(),
            new ByProducts(),
            new ByCountry()
        ];
    }

    public function initSearch(){

        $this->registerSearch();

        foreach($this->searchStrategys as $strategy){

            if($strategy->isValid($this->request)){
                $this->builder->add($strategy);
            }

        }

        return $this->builder->getResults();
    }
}