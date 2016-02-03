<?php namespace Modules\Supplier\SearchStrategy;

class SearchBuilder{
    public $strategy = [];

    private $request;

    private $suppliersIds;

    public function __construct($request = array())
    {
        $this->request = $request;
    }

    public function add($strategy){
        $this->strategy[] = $strategy;
    }

    public function getResults(){
        \DB::enableQueryLog();
        foreach ($this->strategy as $strategy) {

            $result =  $strategy->search( $this->request , $this->suppliersIds);

            if( $result ){
                $this->suppliersIds = $result;
            }
        }
       // print_r(\DB::getQueryLog());
        return $this->suppliersIds;
    }
}
