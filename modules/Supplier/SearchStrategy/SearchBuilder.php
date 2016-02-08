<?php namespace Modules\Supplier\SearchStrategy;

use Modules\Supplier\Supplier;

class SearchBuilder{
    public $strategy = [];

    private $request;

    private $suppliersIds = null;

    public function __construct($request = array())
    {
        $this->request = $request;
    }

    public function add(SearchStrategyContract $strategy){
        $this->strategy[] = $strategy;
    }

    public function getResults(){

        foreach ($this->strategy as $strategy) {

            $result =  $strategy->search( $this->request , $this->suppliersIds);

            if($result == false){

                $this->suppliersIds = false;
                break;
            }

            $this->suppliersIds = $result;
        }

        return ($this->suppliersIds) ? Supplier::whereIn('id',$this->suppliersIds)->with('profile')->paginate(5) : ['empty'];
    }
}
